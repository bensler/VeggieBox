<?php

class OrderLine extends AppModel {

  var $name = 'OrderLine';

  var $belongsTo = array(
    'Order' => array(
	    'foreignKey' => 'order_id',
	    'className' => 'Order'
	  ),
    'OfferLine' => array(
      'foreignKey' => 'offer_line_id',
      'className' => 'OfferLine'
    ),
  );

}

?>