<?php

  class OrdersComponent extends Component {
    
    function findCurrentActiveOffer($offerModel, $recursive) {
      $offerModel->recursive = $recursive;
      return $offerModel->find('first', array(
        'order' => 'Offer.end_date DESC',
        'conditions' => array('Offer.state' => '200')
      ));
    }
    
    function calcOrderPrice(&$order) {
    	$totalPrice = 0;
    	foreach($order['OrderLine'] as &$orderLine) {
    		$offerLine = $orderLine['OfferLine'];
    		$totalPrice += $this->calcOrderlinePrice($orderLine, $offerLine);
    	}
    	$order['Order']['price'] = $totalPrice;
    }
    
    private function calcOrderlinePrice(&$orderLine, $offerLine) {
    	$unitPrice = str_replace(',', '.', $offerLine['price']);
    	$price = $orderLine['quantity_delivered'] * $unitPrice;
    	$orderLine['price'] = $price;
    	return $price;
    }
    
    
  }
?>
