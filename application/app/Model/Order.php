<?php

class Order extends AppModel {

  var $name = 'Order';

  var $belongsTo = array(
    'User' => array(
      'foreignKey' => 'user_id',
      'className' => 'User'
    ),
    'Offer' => array(
      'foreignKey' => 'offer_id',
      'className' => 'Offer'
    )
  );

  var $hasMany = array(
    'OrderLine' => array(
      'className' => 'OrderLine',
      'foreignKey' => 'order_id',
      'dependent' => true
    )
  );
  
}

?>