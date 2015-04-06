<!-- File: /app/views/elements/orders/confirmNaviCustomer.ctp -->

<?php 
  $order = $orderData['Order'];
  $orderLines = $orderData['OrderLine'];
  $emptyOrder = (count($orderLines) < 1);
?>
<?php echo $this->Html->link('Bestellung ändern', '/orders/order'); ?>&nbsp;
<?php
  if (!$emptyOrder) {
    echo $this->Html->link(
      'Bestellung stornieren', '/orders/cancel/'.$order['id'], null, 
      'Soll die Bestellung wirklich storniert werden?'
    );
    echo '&nbsp;';
  }
?>
<?php
  echo $this->Html->link('Startseite', '/');
?>
