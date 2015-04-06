<?php

class Vegetable extends AppModel {

  var $name = 'Vegetable';

	var $belongsTo = array(
		'Unit' => array(
			'foreignKey' => 'unit_id',
			'className' => 'Unit'
		),
	);

}

?>