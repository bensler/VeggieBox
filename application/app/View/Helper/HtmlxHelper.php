<?php

  class HtmlxHelper extends AppHelper {
    
    var $helpers = array('Html');

    function warningTrOnError($condition) {
    	return $this->rowStyleOnCondition($condition, 'warning');
    }

    function rowStyleOnCondition($condition, $style) {
      $tagParams = array();
      if ($condition) {
        $tagParams = array('class' => $style);
      }
      return $this->output($this->Html->tag('tr', null, $tagParams));
    }

    function createWarningTrOnError($key, $errors, $cols) {
      if (array_key_exists($key, $errors)) {
      	return $this->output($this->Html->tag(
      		'tr', 
      		$this->output($this->Html->tag('td', $errors[$key], array('colspan' => $cols))), 
      		array('class' => 'warning'))
      	);
      }
      return '';
    }

    function get($anArray, $key, $defaultValue) {
    	return array_key_exists($key, $anArray) ? $anArray[$key] : $defaultValue;
    }
    
  }
  
?>
