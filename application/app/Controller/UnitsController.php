<?php

  # /app/controllers/units_controller.php

  class UnitsController extends AppController {

    var $name = 'Units';

    var $helpers = array('Htmlx');
    
    function index() {
      $errorsById = $this->saveFormData($this->request->data);
    	$units = $this->Unit->find('all', array('order' =>  'Unit.name'));
      array_push($units, array('Unit' => array(
        'id' => '',
        'name' => '',
        'fraction_digits' => ''
      )));
      foreach($units as &$unit) {
      	$unit = &$unit['Unit'];
      	$unitId = $unit['id'];
      	if (array_key_exists($unitId, $errorsById)) {
	      	$unit = array_merge($unit, $errorsById[$unitId]);
      	}
      }
      $this->set('units', $units);
    }

    function saveFormData($formData) {
      $errorsById = array();
      if (!empty($formData)) {
        $formData = $formData['Unit'];
        foreach($formData as &$unitData) {
          $unitId = $unitData['id'];
          $frac = (array_key_exists('fraction_digits', $unitData) && ($unitData['fraction_digits'] != '')) ? $unitData['fraction_digits'] : '0';
          $unitData['fraction_digits'] = $frac;
          if (array_search($frac, array('0', '1', '2')) === FALSE) {
            $errorsById[$unitId] = $unitData;
            $errorsById[$unitId]['error'] = 'Nachkommastellen muss 0, 1 oder 2 sein!';
          } else {
            if ($unitId == '') {
              if ($unitData['name'] != null) {
                // create new unit
                try {
	                $this->Unit->save($unitData);
                } catch(Exception $e) {
	                $errorsById[$unitId] = $unitData;
	                $errorsById[$unitId]['error'] = 'Der Name muss eindeutig sein!';
                }
              }
            } else {
              if ($unitData['name'] != null) {
                // update existing unit
                try {
                	$this->Unit->save($unitData);
                }catch(Exception $e) {
	                $errorsById[$unitId] = $unitData;
	                $errorsById[$unitId]['error'] = 'Der Name muss eindeutig sein!';
                }
              } else {
                // remove
                try {
                	$this->Unit->delete($unitId);
                } catch(Exception $e) {
                  $errorsById[$unitId]['error'] = 'Die Einheit wird verwendet und kann nicht gelöscht werden.';
                }
              }
            }
          }
        }
      }
    	return $errorsById;
    }
    
  }

?>
