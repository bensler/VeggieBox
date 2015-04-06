<?php

  # /app/controllers/orders_controller.php
  
  class OrdersController extends AppController {
  
    var $name = 'Orders';
    
    var $uses = array('Order', 'OrderLine', 'Offer', 'OfferLine', 'User');
    
    var $helpers = array('Users', 'Offers', 'Htmlx');
    
    var $components = array('Session', 'Offers', 'Orders', 'Users');
    
    var $customerActions = array('order', 'cancel', 'confirm');
        
    /** Creates a new order on the currently active (state == 200) offer or edits it
     * if already existing. Allowed for customers. */
    function order($id = null) {
    	if (empty($this->request->data)) {
    		$this->prepareOrderForm($id);
    	} else {
        $this->processOrderForm($this->request->data);
    	}
    }
    
    /** Every order creation or edit refers on the currently active offer. If there is
     * none no order creation or edit can take place.
     *  
     * (1) \/orders\/order
     *     edit current users order of the currently active offer
     *  (2) \/orders\/order\/x
     *     edit order x, 
     *     allowed if current user is admin or if x belongs to current user
     *       otherwise redirect to (1)
     *  (3) \/orders\/order\/?user_id=p&offer_id=o
     *     create order for user p and offer o or edit it if there is one
     *     allow if current user is p or admin 
     *       otherwise redirect to (1)
     */
    private function prepareOrderForm($id) {
    	$done = false;
    	$navi = 'orders';
      $user = $this->Auth->user();
    	 
    	$isAdmin = $this->Users->isAdmin($this->Auth);
    	$actualUser = $user;
    	$params = $this->params['url'];
    	$case3 = array_key_exists('user_id', $params);
      $activeOffer = $this->Offers->findCurrentActiveOffer($this->Offer, 3);
      $currentOrder = array();
      if ($id != null) {
        // case (2) /orders/order/'.$id
        $navi = 'offers';
        $currentOrder = $this->Order->findById($id);
        if ($currentOrder['Order']['offer_id'] != $activeOffer['Offer']['id']) {
        	if ($isAdmin) {
            $offerModel->recursive = 3;
	          $activeOffer = $this->Offer->findById($currentOrder['Order']['offer_id']);
         	} else {
         		$this->flash('Nach Bestellschluß sind keine Änderungen an der Bestellung mehr möglich.', '/', 5);
         		return;
         	}
        }
        $actualUser = $currentOrder['User'];
        $this->Session->write('Order.lastUser', $actualUser['id']);
        $this->mergeOrderIntoOffer($activeOffer, $currentOrder);
        $done = true;
      }
      if ((!$done) && $case3 && $isAdmin) {
        // case (3) /orders/order/?user_id=p&offer_id=o
        $navi = 'offers';
        $userId = $params['user_id'];
        $offerModel->recursive = 3;
        $activeOffer = $this->Offer->findById($params['offer_id']);
      	$currentOrder = $this->findCurrentActiveOrder($activeOffer, $params['user_id']);
       	$this->Session->write('Order.lastUser', $userId);
        if (empty($currentOrder)) {
          $actualUser = $this->User->findById($userId);
          $actualUser = $actualUser['User'];
        } else {
          $actualUser = $currentOrder['User'];
        }
        $this->mergeOrderIntoOffer($activeOffer, $currentOrder);
        $done = true;
      }
      if ($actualUser['id'] != $user['id']) {
      	if ($isAdmin) {
      		$user = $actualUser;
      	} else {
      		$this->redirect('/orders/order');
      		return;
      	}
      }
      if ((!$done) && (!empty($activeOffer))) {
        // case (1) /orders/order
      	$currentOrder = $this->findCurrentActiveOrder($activeOffer, $user['id']);
        $this->mergeOrderIntoOffer($activeOffer, $currentOrder);
      }
      $this->set('orderData', $currentOrder);
      $this->set('offerData', $activeOffer);
      $this->set('userData', $user);
      $this->set('isAdmin', $isAdmin);
      $this->set('navi', $navi);
    }
    
    private function processOrderForm(&$data) {
    	$user = $this->Auth->user();
    	$isAdmin = $this->Users->isAdmin($this->Auth);
  		$order = &$data['Order'];
    	$actualUserId = $order['user_id'];
    	$offerId = $order['offer_id'];
    	$this->Offer->recursive = 0;
   		$offer = $this->Offer->findById($offerId);
      $this->OfferLine->recursive = 0;
   		$offerLinesById = $this->getEntitiesById($this->OfferLine, array('offer_id' => $offer['Offer']['id']));
   		if ($offer['Offer']['state'] != 200) {
   			if (!$isAdmin) {
	 				$url = ($order['id'] != '') ? '/orders/confirm/'.$order['id'] : '/orders/order/';
	   			$this->flash('Ihre Bestellung konnte NICHT entgegen genommen werden, weil das Angebot nicht mehr aktiv ist.', $url, 5);
	   			return;
   			}
   		}
   		if ($order['id'] != '') {
   			$oldOrder = $this->Order->findById($order['id']);
   		  $actualUserId = $oldOrder['Order']['user_id'];
   		}
      if (($user['id'] != $actualUserId) && (!$isAdmin)) {
        $this->flash('Die Bestellung konnte NICHT entgegen genommen werden.', '/orders/order/', 5);
        return;
      }       
   		$this->makeOrderUnique(
        $actualUserId, $offerId, $order['id']
   		);
   		$order['user_id'] = $actualUserId;
      $order['price'] = 0;
   		$navi = $data['Order']['navi'];
      if ($this->Order->save($data)) {
       	$orderId = $this->Order->id;
        $order['id'] = $orderId;
        foreach($data['OrderLine'] as $orderLine) {
          $orderLine['order_id'] = $orderId;
          $save = false;
        	$existingOrderLine = array_key_exists('id', $orderLine);
        	$offerLine = $offerLinesById[$orderLine['offer_line_id']];
        	$soldOut = ($offerLine['sold_out'] == '1');
        	$fractionDigits = $orderLine['fraction_digits'];
        	$quantityOrdered = $orderLine['quantity_ordered'];
        	$quantityOrdered = str_replace('.', '', $quantityOrdered);
          $quantityOrdered = (float)number_format(
         	  (float)str_replace(',', '.', $quantityOrdered), $fractionDigits, '.', ''
         	);
         	$quantityOrdered = 100.0 * $quantityOrdered;
         	if ($isAdmin) {
         		unset($orderLine['quantity_ordered']);
         	}
         	if ($quantityOrdered > 0) {
         		if ($soldOut) {
              if ($existingOrderLine) {
                if ($isAdmin) {
                  $orderLine['quantity_delivered'] = $quantityOrdered;
                } else {
         			    $oldOrderLine = $this->OrderLine->findById($orderLine['id']);
                  $oldQuantityOrdered = $oldOrderLine['OrderLine']['quantity_ordered'];
         			    $orderLine['quantity_ordered'] = min($oldQuantityOrdered, $quantityOrdered);
                }
              }
         		} else {
              // !$soldOut
              $orderLine['quantity_delivered'] = $quantityOrdered;
              if ((!$isAdmin) ){ //|| (!$existingOrderLine)) {
         		    $orderLine['quantity_ordered'] = $quantityOrdered;
              }
         		}
            $save = true;
         	} else {
            // ($quantityOrdered <= 0)
         		if ($existingOrderLine) {
              if ($isAdmin) {
         		    $orderLine['quantity_delivered'] = 0;
                $save = true;
              } else {
                $this->Order->OrderLine->delete($orderLine['id']);          			
              }
         		}
         	}
          if ($save) {
            $this->Order->OrderLine->save($orderLine);
          }
          $this->Order->OrderLine->id = null;
        }
        $this->Order->save($data);
        $this->redirect('/orders/confirm/'.$orderId.'?navi='.$navi);
      } else {
      	$this->flash('Die Bestellung konnte nicht gespreichert werden, weil ein technisches Problem auftrat.', $this->referer(), 5);
      }
   	}
    
    function confirm($id) {
    	$this->loadOrder($id);
    }
    
    private function loadOrder($id) {
    	$currentOrder = $this->Order->findById($id);
    	$user = $currentOrder['User'];
    	$this->Offer->recursive = 3;
    	$activeOffer = $this->Offer->findById($currentOrder['Order']['offer_id']);
    	$this->mergeOfferIntoOrder($currentOrder, $activeOffer);
    	$this->Orders->calcOrderPrice($currentOrder);
    	$this->set('userData', $user);
    	$this->set('orderData', $currentOrder);
    	$this->set('isAdmin', $this->Users->isAdmin($this->Auth));
    	return $currentOrder;
    }
    
    function index() {
    	$this->redirect('/orders/order/');
    }
    
    function view($id) {
      $order = $this->loadOrder($id);
      $this->Session->write('Order.lastUser', $order['User']['id']);
    }
    	
    /** Creates an empty order on the currently active (state == 200) offer or removes all
     * associated OrderLines.
     * 
     *  (1) \/orders\/cancel
     *     clear current users order of the currently active offer,
     *     allowed for customers.
     *  (2) \/orders\/cancel\/x
     *     clear order x, 
     *     allowed if current user is admin or if x belongs to current user
     *       otherwise redirect to (1)
     * 
     *  */
    function cancel($id = null) {
    	$adminAction = false;
      $user = $this->Auth->user();

      $userId = $user['id'];
    	$isAdmin = $this->Users->isAdmin($this->Auth);
      $offer = $this->Offers->findCurrentActiveOffer($this->Offer, 1);
      if ($id == null) {
        // case (1) /orders/cancel/
	      if (empty($offer)) {
	        $this->flash('Eine Stornierung nach Bestellschluß ist leider nicht möglich', '/', 5);
	      	return;
	      }
      	$order = $this->findCurrentActiveOrder($offer, $user['id']);
      } else {
        // case (2) /orders/cancel/'.$id
      	$order = $this->Order->read(null, $id);
        if ($order['Order']['offer_id'] != $offer['Offer']['id']) {
        	if ($isAdmin) {
        		$this->Offer->recursive = 3;
        		$offer = $this->Offer->findById($order['Order']['offer_id']);
        	} else {
        		$this->flash('Eine Stornierung nach Bestellschluß ist leider nicht möglich', '/', 5);
        		return;
        	}
        }
      	if ($order['User']['id'] != $user['id']) {
          if ($isAdmin) {
          	$user = $order['User'];
            $this->Session->write('Order.lastUser', $user['id']);
          	$adminAction = true;
          } else {
            $this->redirect('/orders/');
            return;
          }
        } else {
        	if (($offer['Offer']['state'] > 200) && (!$isAdmin)) {
        		$this->flash('Eine Stornierung nach Bestellschluß ist leider nicht möglich', '/orders/', 5);
        		return;
        	}
        }
        $this->clearOrder($order);
      }
      if (empty($order)) {
        // create empty order
        $this->Order->id = null;
      	if (!$this->Order->save(array(
      	  'user_id' => $user['id'],
      	  'offer_id' => $offer['Offer']['id']
        ))) {
        	// TODO handle DB error
        }
      } else {
       	if (!$this->clearOrder($order)) {
          // TODO handle DB error
       	}
      }
      if ($adminAction) {
      	// admin action
        $this->redirect($this->referer('/'));
      } else {
        // customer action
        $this->redirect('/orders/confirm/'.$this->Order->id);
      }
    }
    
    private function clearOrder(&$order) {
      // remove OrderLines
      $success = true;
      $this->Order->id = $order['Order']['id'];
      foreach($order['OrderLine'] as $orderLine) {
        $success &= $this->Order->OrderLine->delete($orderLine['id']);
      }
      return $success;
    }

    function preview($offerId) {
      $this->Offer->id = $offerId;
      $this->Offer->recursive = 3;
      $offer = $this->Offer->read();
      foreach ($offer['OfferLine'] as &$offerLine) {
        $offerLine['OrderLine'] = array('id' => null, 'quantity_ordered' => null);
      }
      $this->set('offerData', $offer);
      $this->set('userData', $this->Auth->user());
      $this->set('navi', (($this->params['url']['navi'] == 'edit') ? 'edit/'.$offerId : ''));
    }
    
    /** Removes all orders belonging to userId AND offerId except of
     * the given orderId. */
    private function makeOrderUnique($userId, $offerId, $orderId) {
    	$orderIds = $this->Order->find('list', array(
    	  'fields' => array('Order.id'),
        'conditions' => array(
          'Order.user_id' => $userId,
          'Order.offer_id' => $offerId
    	  )
      ));
      foreach ($orderIds as $anOrderId) {
      	if ($anOrderId != $orderId) {
      		$this->Order->delete($anOrderId, false);
      	}
      }
    }
    
    private function findCurrentActiveOrder($offerData, $userId) {
    	return $this->Order->find('first', array(
        'order' => 'Order.id DESC',
        'conditions' => array(
          'Order.offer_id' => $offerData['Offer']['id'],
          'Order.user_id' => $userId
        )
      ));
    }
    
    /** Used for order form */
    private function mergeOrderIntoOffer(&$currentOffer, $currentOrder) {
    	$orderLinesByOfferLineId = array();
    	if (($currentOrder != null) && array_key_exists('OrderLine', $currentOrder)) {
	      foreach($currentOrder['OrderLine'] as $orderLine) {
	      	$orderLinesByOfferLineId[$orderLine['offer_line_id']] = $orderLine;
	      }
    	} 
      foreach($currentOffer['OfferLine'] as &$offerLine) {
        $offerLineId = $offerLine['id'];
        $offerLine['OrderLine'] = array();
        if (array_key_exists($offerLineId, $orderLinesByOfferLineId)) {
          $offerLine['OrderLine'] = $orderLinesByOfferLineId[$offerLineId];
        } else {
        	$offerLine['OrderLine'] = array('quantity_ordered' => null);
        }
      }
    }
    
    /** Used for order confirmation page */
    private function mergeOfferIntoOrder(&$currentOrder, $currentOffer) {
      $orderLinesByOfferLineId = array();
      foreach($currentOrder['OrderLine'] as &$orderLine) {
      	$orderLinesByOfferLineId[$orderLine['offer_line_id']] = $orderLine;
      }
      $orderLines = array();
      foreach($currentOffer['OfferLine'] as &$offerLine) {
        $offerLineId = $offerLine['id'];
        if (array_key_exists($offerLineId, $orderLinesByOfferLineId)) {
          $orderLine = $orderLinesByOfferLineId[$offerLineId];
          $orderLine['OfferLine'] = $offerLine;
          array_push($orderLines, $orderLine);
        }
      }
      $currentOrder['OrderLine'] = $orderLines;
      $currentOrder['Offer'] = $currentOffer['Offer'];
    }
  
  }

?>
