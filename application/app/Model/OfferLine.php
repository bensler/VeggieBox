<?php

class OfferLine extends AppModel {

  var $name = 'OfferLine';

  var $belongsTo = array(
    'Offer' => array(
      'foreignKey' => 'offer_id',
      'className' => 'Offer'
    ),
  	'Vegetable' => array(
      'foreignKey' => 'vegetable_id',
      'className' => 'Vegetable'
    ),
  );

}

?>