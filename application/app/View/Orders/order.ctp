<!-- File: /app/views/orders/order.ctp -->

<?php
  if (empty($offerData)) {
    echo $this->element('orders/noActiveOffer');
  } else {
    if ($isAdmin) {
        echo $this->element('orders/orderFormAdmin', array('submit' => true));
    } else {
        echo $this->element('orders/orderForm', array('submit' => true));
    }
  } 
?>
