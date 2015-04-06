<!-- File: /app/views/orders/confirm.ctp -->

<?php
  $order = $orderData['Order'];
  $orderLines = $orderData['OrderLine'];
  $emptyOrder = (count($orderLines) < 1);
  $offer = $orderData['Offer'];
?>
<h1>Bestätigung</h1>

<?php echo $this->element('orders/'.($emptyOrder ? 'confirmEmptyOrder' : 'confirmOrdered')) ?>

<p />
<?php 
  $params = $this->params['url'];
  if (array_key_exists('navi', $params) && ($params['navi'] == 'offers')) {
    echo $this->Html->link('Zurück zur Bestellung', '/orders/order/'.$order['id']);
    echo '<p />';
    echo $this->Html->link('Bestellübersicht', '/offers/orders/'.$offer['id']);
  } else {
    echo $this->element('orders/confirmNaviCustomer');
  }
?> 
