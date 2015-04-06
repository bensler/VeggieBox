<?php

	App::uses('CakeEmail', 'Network/Email');

	# /app/controllers/offers_controller.php

  class OffersController extends AppController {
		
    // code duplication from OffersHelper
		var $weekdays = array('So', 'Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa');
		 
    var $name = 'Offers';

    var $uses = array('Offer', 'OfferLine', 'Vegetable', 'Unit', 'User', 'Order');

    var $helpers = array('Offers', 'Users', 'Htmlx');
    
    var $components = array('Session', 'Users', 'Offers', 'Orders');

    function edit($id = null) {
      $errors = array();
      $lineIdsToRemove = array();
      if (empty($this->request->data)) {
      	$this->prepareEditForm($id);
      } else {
        $this->Offer->id = $id;
      	$this->validateEdit($errors, $lineIdsToRemove);
      	if (empty($errors)) {
      		if ($id == 0) {
      			$this->request->data['Offer']['state'] = '100';
      		}
	        if ($this->Offer->save($this->request->data)) {// TODO handle potential database error
            $action = $this->request['data'];
	          $id = $this->Offer->id;
	          foreach($this->request->data['OfferLine'] as $offerLine) {
            	if (($offerLine['price'] != '') && ($offerLine['vegetable_id'] != '-1')) {
	              $offerLine['offer_id'] = $id;
	              $this->Offer->OfferLine->save($offerLine); // TODO handle potential database error
	              $this->Offer->OfferLine->id = null;
	            }
	          }
	          foreach($lineIdsToRemove as $offerLineId => $mirDochEgal) {
              $this->Offer->OfferLine->delete($offerLineId, false);
	          	
	          }
	          if (array_key_exists('exit', $action)) {
	            $this->flash('Das Wochenangebot wurde gespeichert.', '/offers');
	          }
            if (array_key_exists('preview', $action)) {
              $this->redirect('/orders/preview/'.$id.'?navi=edit');
            }
            if (array_key_exists('edit', $action)) {
	          	// reload saved offer;
		          $this->Offer->recursive = 1;
		          $this->Offer->id = $id;
		          $this->request->data = $this->Offer->read();
	          }
	        }
      	} else {
      		// !empty(errors)
          $this->Offer->recursive = 0;
          $this->Offer->id = $id;
          $offer = $this->Offer->read();
          $this->request->data['Offer']['state'] = $offer['Offer']['state']; 
      	}
      }
      $this->Session->write('Offer.last', $id);
      $this->request->data['Vegetable'] = $this->getEntitiesById($this->Vegetable, array(), 'name');
      $this->prepareVegetableNames($this->request->data, $this->getEntitiesById($this->Unit, array(), 'name'));
      $this->set('offerData', $this->request->data);
      $this->set('errors', $errors);
    }
    
    private function prepareEditForm($id) {
      if ($id == null) {
        // create new offer, use latest offer as template
        $this->request->data = $this->Offer->find('first', array(
          'recursive' => 1,
          'order' => array('Offer.end_date DESC')
        ));
        $offer = &$this->request->data['Offer'];
        $offer['id'] = null;
        $offer['name'] .= ' (Kopie)';
        $offer['introduction'] = '';
        $offer['state'] = '100';
        $offerLines = array();
        foreach($this->request->data['OfferLine'] as $key => $offerLine) {
          $offerLine['id'] = null;
          $offerLines[$key] = $offerLine;
        }
        $this->request->data['OfferLine'] = $offerLines;
      } else {
        // load existing offer;
        $this->Offer->recursive = 1;
        $this->Offer->id = $id;
        $this->request->data = $this->Offer->read();
      }
    }
    
    function delete($id) {
      $this->Offer->id = $id;
      $offer = $this->Offer->read();
      if ($offer['Offer']['state'] == 200) {
      	$this->flash('Ein aktives Angebot kann nicht gelöscht werden.', '/offers', 0);
      } else {
      	if ($this->Offer->delete($id, false)) {
          $this->flash('Das Angebot wurde gelöscht.', '/offers');
      	} else {
          $this->flash('Das Angebot konnte nicht gelöscht werden.', '/offers', 0);
      	}
      }
    }
    
    private function validateEdit(&$errors, &$lineIdsToRemove) {
    	// TODO check state == 100
      $offer = &$this->request->data['Offer'];
      $offerLines = &$this->request->data['OfferLine'];
      $add = ($offer['id'] == '');
      $offer['name'] = trim($offer['name']);
    	$name = $offer['name'];
    	if ($name == '') {
    		$errors['errorName'] = 'Es muss ein Name eingegeben werden.';
    	} else {
    		$this->Offer->recursive = 0;
    		$duplicateNameOffer = $this->Offer->find('first', array('conditions' => array(
    		  'name' => $offer['name']
    		)));
    		if (
    		  (!empty($duplicateNameOffer))
    		  && ($add || ($offer['id'] != $duplicateNameOffer['Offer']['id']))
    	  ) {
          $errors['errorName'] = 'Der Name ist nicht eindeutig.';
    		}
    	}
    	foreach($offerLines as $key => $offerLine) {
    		if (($offerLine['price'] == '') && ($offerLine['vegetable_id'] == '-1')) {
    			if ($offerLine['id'] != '') {
    			  $lineIdsToRemove[$offerLine['id']] = '';
    			}
        	unset($offerLines[$key]);
        } else {
        	$lineErrors = array();
	        if ($offerLine['price'] == '') {
	        	array_push($lineErrors, 'Es muss ein Preis angegeben werden.');
	        }
	        $vegId = $offerLine['vegetable_id'];
          if ($vegId == '-1') {
            array_push($lineErrors, 'Es muss ein Gemüse ausgewählt werden.');
          }
          if (!empty($lineErrors)) {
            $errors[$key] = $lineErrors;
          }
        }
    	}
    	return $errors;
    }
    
    function index() {
    	$this->set('lastOffersId', $this->Session->read('Offer.last'));
      $this->set('offersData', $this->Offer->find('all', array(
        'order' => array('Offer.end_date DESC'),
        'limit' => 10
      )));
    }

    function activate($id) {
    	$this->Session->write('Offer.last', $id);
      $offer = $this->Offer->findById($id);
      $activeOffer = $this->Offers->findCurrentActiveOffer($this->Offer, 1);
      if (!empty($activeOffer)) {
      	if ($activeOffer['Offer']['id'] == $id) {
      		$this->flash('Dieses Angebot ist bereits aktiv.', '/offers', 0);
      	} else {
          $this->flash('Es ist bereits ein anderes Angebot aktiv.', '/offers', 0);
        }
        return;
      }
      if ($offer['Offer']['state'] != 100) {
        $this->flash('Es kann nur ein Angebot im Status \'in Vorbereitung\' aktiviert werden.', '/offers', 0);
        return;
      }
      $offer['Offer']['state'] = 200;
      $this->Offer->save($offer['Offer']);
	    $this->flash('Das Angebot wurde aktiviert.', '/offers/mail/'.$id);
    }
    
    function mail($id = null) {
      if (!empty($this->request->data)) {
        $id = $this->request->data['Offer']['id'];
      }
      $this->Session->write('Offer.last', $id);
      $offer = $this->Offer->findById($id);
      if (empty($this->request->data)) {
        $activeOffer = $this->Offers->findCurrentActiveOffer($this->Offer, 1);
        if ((!empty($activeOffer)) && ($activeOffer['Offer']['id'] != $offer['Offer']['id'])) {
          $this->flash('Es können nur Benachrichtigungs-Emails für das aktive Angebot versandt werden.', '/offers', 0);
        } else {
	        $this->set('offerData', $offer);
	        $this->set('userData', $this->User->find('all', array(
	          'order' => 'User.profile_id ASC, User.lname ASC'
	        )));
        }
      } else {
        $users = $this->getEntitiesById($this->User);
        foreach($this->request->data['User'] as $userId => $sendFlag) {
          if ($sendFlag['send'] == '1') {
            $this->sendMail($users[$userId], $offer['Offer']);
          }
        }
        $this->flash('Die Benachrichtigungs-Emails wurden versandt', '/offers');
      }
    }
    
    function orders($id) {
      $this->Session->write('Offer.last', $id);
      $usersWithEmptyOrder = array();
      $usersWithOrder = array();
      $this->Offer->recursive = 1;
      $offer = $this->Offer->findById($id);
      $offer = $offer['Offer'];
      $usersById = $this->getEntitiesById($this->User, array('User.profile_id = 1'));
      $this->Order->recursive = 2;
      $ordersById = $this->getEntitiesById($this->Order, array("Order.offer_id = {$id}"));
      foreach($ordersById as $orderId => $order) {
        $user = $order['User'];
        $userId = $user['id'];
        $orderLineCount = count($order['OrderLine']);
      	// filter admins
      	if (array_key_exists($userId, $usersById)) {
      	  if ($orderLineCount == 0) {
      	  	$user['comment'] = $order['comment'];
		        $usersWithEmptyOrder[$userId] = array('User' => $user, 'order_id' => $orderId);
          } else {
            $usersWithOrder[$userId] = array('User' => $user, 'order_id' => $orderId);
	        }
        }
      	unset($usersById[$userId]);
      }
      $this->set('usersWhoForgot', $usersById);
      $this->set('usersWithEmptyOrder', $usersWithEmptyOrder);
      $this->set('usersWithOrder', $usersWithOrder);
      $this->set('offerData', $offer);
    }
    
    function close($id) {
      $this->Session->write('Offer.last', $id);
      $this->Offer->save(array("id" => $id, "state" => 300));
      $this->redirect('/offers/');
    }

    function pdf($id) {
      $this->Session->write('Offer.last', $id);
      $usersById = $this->getEntitiesById($this->User, array('User.profile_id = 1'));
      $vegetablesById = $this->getEntitiesById($this->Vegetable);
      $unitsById = $this->getEntitiesById($this->Unit);
      $this->Order->recursive = 2;
      $ordersById = $this->getEntitiesById($this->Order, array("Order.offer_id = {$id}"));
      
      $quantitiesByOfferLineId = array();
      $this->Offer->recursive = 1;
      $offer = $this->Offer->findById($id);
      foreach($offer['OfferLine'] as $offerLine) {
      	$quantitiesByOfferLineId[$offerLine['id']] = array(
          'vegetable_id' => $offerLine['vegetable_id'], 
          'comment' => $offerLine['comment'], 
      	  'quantity_delivered' => 0
      	);
      }

      App::import('Vendor', 'fpdf/fpdf');
      $pdf = new FPDF();
      foreach($ordersById as $orderId => $order) {
      	$this->Orders->calcOrderPrice($order);
      	$order['price'] = $order['Order']['price'];
        $user = $order['User'];
        $userId = $user['id'];
        $orderLineCount = count($order['OrderLine']);
        // filter admins
        if ((array_key_exists($userId, $usersById)) && ($orderLineCount > 0)) {
          $this->printOrder($pdf, $order, $vegetablesById, $unitsById, $quantitiesByOfferLineId);
        }
      }
      $this->printSummary($pdf, $quantitiesByOfferLineId, $vegetablesById, $unitsById);
      $pdf->Output();
    }
    
    private function toIso($str) {
    	return mb_convert_encoding($str, "ISO-8859-15", "UTF-8");
    }
    
    private function printOrder($pdf, $order, $vegetablesById, $unitsById, &$quantitiesByOfferLineId) {
    	$user = $order['User'];
    	$orderComment = $order['comment'];
      $pdf->AddPage();
      $pdf->SetFont('Helvetica', 'B', 16);
      $pdf->Cell(0, 10, $this->toIso($this->Users->getSalutationPhrase($user).' ('.$user['username'].')'));
      $pdf->Ln();
      $pdf->Cell(0, 10, $this->toIso($user['address']));
      $pdf->Ln();
      $pdf->Cell(0, 10, $this->toIso($user['zip'].' '.$user['city']));
      $pdf->Ln();
      $pdf->Cell(0, 10, $this->toIso('Tel: '.$user['telephone']));
      $pdf->Ln(20);
      $pdf->SetFont('Times', '', 16);
      if ($orderComment != '') {
        $pdf->MultiCell(0, 8, $this->toIso($orderComment));
        $pdf->Ln(10);
      }
      $pdf->SetFont('Helvetica', 'B', 16);
      $pdf->Cell(30, 10, 'bestellt', 'LRTB', 0, 'C');
      $pdf->Cell(30, 10, 'geliefert', 'LRTB', 0, 'C');
      $pdf->Cell(70, 10, $this->toIso('Gemüsesorte'), 'LRTB', 0, 'C');
      $pdf->Cell(35, 10, 'Preis/Einh.', 'LRTB', 0, 'C');
      $pdf->Cell(0, 10, 'Preis', 'LRTB', 0, 'C');
      $pdf->Ln();
      $pdf->SetFont('Helvetica', '', 14);
      foreach ($order['OrderLine'] as $orderLine) {
      	$offerLine = $orderLine['OfferLine'];
      	$comment = $offerLine['comment'];
        $vegetable = $vegetablesById[$offerLine['vegetable_id']];
        $unit = $unitsById[$vegetable['unit_id']];
        $unitName = $unit['name'];
        $quantityOrdered = (int)$orderLine['quantity_ordered'];
        $quantityDelivered = (int)$orderLine['quantity_delivered'];
        $quantitiesByOfferLineId[$offerLine['id']]['quantity_delivered'] += $quantityDelivered;
        
        $oldY = $pdf->GetY();
        $pdf->SetLeftMargin(70);
        $pdf->setRightMargin(70);
        $pdf->Write(8, $this->toIso($vegetable['name']));
        if ($comment != '') {
          $pdf->SetFont('Helvetica', '', 12);
          $x = $pdf->GetX();
          $pdf->SetY(1 + $pdf->GetY());
          $pdf->SetX($x);
          $pdf->Write(6, $this->toIso(' ('.$comment.')'));
        	$pdf->SetFont('Helvetica', '', 14);
        }
        $newY = $pdf->GetY();
        $pdf->SetLeftMargin(10);
        $pdf->setRightMargin(10);
        $pdf->SetY($oldY);
        $rowHight = (($newY - $oldY) + 8);
        $pdf->SetX(10);
        
        $pdf->Cell(30, 8, number_format(
          ($quantityOrdered / 100.0), (int)$unit['fraction_digits'], ',', '.'
        ).' '.$unitName, '', 0, 'R');
        $pdf->SetX(10);
        $pdf->Cell(30, $rowHight, '', 'LRTB');
        
        $pdf->Cell(30, 8, number_format(
          ($quantityDelivered / 100.0), (int)$unit['fraction_digits'], ',', '.'
        ).' '.$unitName, '', 0, 'R');
        $pdf->SetX(40);
        $pdf->Cell(30, $rowHight, '', 'LRTB');
        
        $pdf->Cell(70, $rowHight, '', 'LRTB');
        
        $pdf->Cell(35, $rowHight, '', 'LRTB');
        $pdf->SetX(140);
        $pdf->Cell(35, 8, $offerLine['price'].' '.chr(128).'/'.$unitName, '', 0, 'R');
        
        $pdf->Cell(0, $rowHight, '', 'LRTB');
        $pdf->SetX(175);
        $pdf->Cell(0, 8, number_format(
          ($orderLine['price'] / 100.0), 2, ',', '.'
        ).' '.chr(128), '', 0, 'R');
        $pdf->SetY($newY + 8);
        $pdf->setX(10);
      }
      $pdf->SetX(175);
      $pdf->Cell(0, 10, number_format(
        ($order['price'] / 100.0), 2, ',', '.'
      ).' '.chr(128), 'LRTB', 0, 'R');
      $pdf->Ln();
    }
    
    private function printSummary($pdf, $quantitiesByOfferLineId, $vegetablesById, $unitsById) {
      $pdf->AddPage();
      $pdf->SetFont('Helvetica', 'B', 20);
      $pdf->Cell(0, 10, 'Zusammenfassung');
      $pdf->Ln(20);
      $pdf->SetFont('Helvetica', '', 14   );
      foreach ($quantitiesByOfferLineId as $vegetable) {
      	$quantityDelivered = $vegetable['quantity_delivered'];
      	$comment = $vegetable['comment'];
        $vegetable = $vegetablesById[$vegetable['vegetable_id']];
        $unit = $unitsById[$vegetable['unit_id']];
        $unitName = $unit['name'];
        
        $oldY = $pdf->GetY();
        if (($oldY + 8) > ($pdf->h-$pdf->bMargin)) {
        	$pdf->AddPage();
          $oldY = $pdf->GetY();
        }
        $pdf->SetLeftMargin(10);
        $pdf->setRightMargin(40);
        $pdf->Write(8, $this->toIso($vegetable['name']));
        $newY = $pdf->GetY();
        $pdf->SetLeftMargin(10);
        $pdf->SetRightMargin(10);
        $pdf->SetY($oldY);
        $pdf->SetX(10);
        $rowHight = (($newY - $oldY) + 8);
        
        $pdf->SetX(10);
        $pdf->Cell(160, $rowHight, '', 'LRTB');
        
        $quantityDelivered = (($quantityDelivered == '0') ? '-  ' : number_format(
          ($quantityDelivered / 100.0), (int)$unit['fraction_digits'], ',', '.'
        ).' '.$unitName);
        $pdf->Cell(30, 8, $quantityDelivered, '', 0, 'R');
        $pdf->SetX(170);
        $pdf->Cell(30, $rowHight, '', 'LRTB');
        $pdf->Ln();
      }
    }
    
    private function prepareVegetableNames(&$data, $unitsById) {
      # append unit name in brackets to the vegetable name
      $vegetableNames = array('-1' => '-');
      foreach ($data['Vegetable'] as $vegId => $veg) {
        $unitName = $unitsById[$veg['unit_id']]['name'];
        $vegName = $veg['name'].' (€/'.$unitName.')';
        $vegetableNames[$vegId] = $vegName;
      }
      $data['vegetableNames'] = $vegetableNames;
    }
    
    // code duplication from OffersHelper
    function formatOfferEndDate($offer) {
    	$endDate = new DateTime($offer['end_date']);
    	return $this->weekdays[$endDate->format('w')].', '.$endDate->format('d.m.Y H:i');
    }
    // code duplication from OffersHelper
    function formatDeliveryDate($offer) {
    	$deliveryDate = new DateTime($offer['delivery_date']);
    	return $this->weekdays[$deliveryDate->format('w')].', '.$deliveryDate->format('d.m.Y');
    }
    
    private function sendMail($userData, $offerData) {
    	$adresses = explode(';', $userData['email']);
    	$senderData = $this->Auth->user();
    	$salutation = $this->Users->getSalutationPhrase($senderData);
    	
    	foreach ($adresses as $adress) {
	    	$email = new CakeEmail();
	    	$email->from(array(MAIL_DEFAULT_FROM => "Ludwigs Garten ({$salutation})"));
	    	$email->to($adress);
	    	$email->subject("[Ludwigs Garten] Wochenangebot {$offerData['name']}");
	    	$email->emailFormat('text');
	    	$email->viewVars(array(
	    		'salutationPhrase' => $this->Users->getSalutationPhrase($userData),
					'endDate' => $this->formatOfferEndDate($offerData),
					'deliveryDate' => $this->formatDeliveryDate($offerData),
	      	'offerData' => $offerData
	    	));
	    	$email->template('customerOfferNotification');
	    	$email->send();
    	}
    }
    
  }

?>
