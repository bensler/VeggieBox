<?php

  class OffersHelper extends AppHelper {
  	
  	var $weekdays = array('So', 'Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa');
  	
    function formatOfferDates($offer) {
      $endDate = new DateTime($offer['end_date']);
      $deliveryDate = new DateTime($offer['delivery_date']);
      $formattedDate = $this->weekdays[$endDate->format('w')].', '.$endDate->format('d.m. H:i');
      $formattedDate .= ' / '.$this->weekdays[$deliveryDate->format('w')].', '.$deliveryDate->format('d.m.');
      return $this->output($formattedDate);
    }
  	
    function formatOfferEndDate($offer) {
      $endDate = new DateTime($offer['end_date']);
      $formattedDate = $this->weekdays[$endDate->format('w')].', '.$endDate->format('d.m.Y H:i');
      return $this->output($formattedDate);
    }
    
    function formatDeliveryDate($offer) {
      $deliveryDate = new DateTime($offer['delivery_date']);
      $formattedDate = $this->weekdays[$deliveryDate->format('w')].', '.$deliveryDate->format('d.m.Y');
      return $this->output($formattedDate);
    }
    
    function formatOfferState($offer) {
      $state = $offer['state'];
      if ($state == 100) {
        return $this->output('in Vorbereitung');
      }
      if ($state == 200) {
        return $this->output('aktiv');
      }
      if ($state == 300) {
        return $this->output('beendet');
      }
      return $this->output('');
    }

    function getUnitString($offerLine, $vegetables, $unitsById) {
      $vegetable = $vegetables[$offerLine['vegetable_id']];
      $result = '€/';
      $result .= $unitsById[$vegetable['unit_id']]['name'];
      return $this->output($result);
    }
    
  }

?>
