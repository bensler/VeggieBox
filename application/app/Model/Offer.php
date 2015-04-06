<?php

class Offer extends AppModel {

  var $name = 'Offer';

  var $hasMany = array(
    'OfferLine' => array(
			'className' => 'OfferLine',
			'foreignKey' => 'offer_id',
			'dependent' => true,
      'order' => 'OfferLine.sort_order ASC'
		)
	);

}

?>