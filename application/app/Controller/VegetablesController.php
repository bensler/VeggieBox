<?php

  # /app/controllers/vegetables_controller.php

	class VegetablesController extends AppController {

		var $name = 'Vegetables';

		var $uses = array('Vegetable', 'Unit');
		
    var $helpers = array('Htmlx');
    
    function statistics($id) {
    	$statistics = array();
    	$vegetable = array();
    	$dbData = $this->Vegetable->find('all', array(
    		'conditions' => array('Vegetable.id' => $id),
    		'fields' => array('Vegetable.name', 'Offer.name','OfferLine.price'),
    		'joins' => array(array(
    			'table' => 'offer_lines',
    			'alias' => 'OfferLine',
    			'conditions' => array('Vegetable.id = OfferLine.vegetable_id')
    		), array(
    			'table' => 'offers',
    			'alias' => 'Offer',
    			'conditions' => array('Offer.id = OfferLine.offer_id')
    		)),
     		'order' => array('Offer.end_date DESC')
    	));
    	foreach($dbData as $entry) {
    		$vegetable = $entry['Vegetable']['name'];
    		$statistics[$entry['Offer']['name']] = $entry['OfferLine']['price'];
    	}
    	$this->set('vegetable', $vegetable);
    	$this->set('statistics', $statistics);
    }
    
		function index() {
      $errorsById = $this->saveFormData($this->request->data);
      $vegetables = $this->Vegetable->find('all', array('order' => 'Vegetable.name'));
      array_push($vegetables, array('Vegetable' => array(
        'id' => null,
        'name' => '',
        'unit_id' => 1
      )));
      foreach($vegetables as &$vegetable) {
        $vegetable = &$vegetable['Vegetable'];
        $vegetableId = $vegetable['id'];
        if (array_key_exists($vegetableId, $errorsById)) {
          $vegetable = array_merge($vegetable, $errorsById[$vegetableId]);
        }
      }
      $this->set('vegetables', $vegetables);
      $this->set('units', $this->Unit->find('list', array('order' => 'Unit.name')));
    }
    
    function saveFormData($formData) {
      $errorsById = array();
      if (!empty($formData)) {
        $formData = $formData['Vegetable'];
        foreach($formData as &$vegData) {
          $vegId = $vegData['id'];
          $vegData['unit_id'] = $vegData['Unit'];
          if ($vegId == '') {
            if ($vegData['name'] != null) {
              // create new vegetable
              try {
              	$this->Vegetable->save($vegData);
              } catch(Exception $e) {
                $errorsById[$vegId] = $vegData;
                $errorsById[$vegId]['error'] = 'Der Name muss eindeutig sein!';
              }
            }
          } else {
            if ($vegData['name'] != null) {
              // update existing vegetable
              try {
              	$this->Vegetable->save($vegData);
              } catch(Exception $e) {
             		$errorsById[$vegId] = $vegData;
                $errorsById[$vegId]['error'] = 'Der Name muss eindeutig sein!';
              }
            } else {
              // remove
              try {
              	$this->Vegetable->delete($vegId);
             	} catch(Exception $e) {
             		$errorsById[$vegId] = $vegData;
                $errorsById[$vegId]['error'] = 'Die Gemüsesorte wird verwendet und kann nicht gelöscht werden.';
              }
            }
          }
        }
      }
      return $errorsById;
    }
		
	}

?>
