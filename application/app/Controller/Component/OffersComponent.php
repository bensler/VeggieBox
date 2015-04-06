<?php

  class OffersComponent extends Component {
    
    function findCurrentActiveOffer($offerModel, $recursive) {
      $offerModel->recursive = $recursive;
      return $offerModel->find('first', array(
        'order' => 'Offer.end_date DESC',
        'conditions' => array('Offer.state' => '200')
      ));
    }
    
  }
?>
