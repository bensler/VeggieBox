<!-- File: /app/views/orders/view.ctp -->

<?php
  $orderLines = $orderData['OrderLine'];
  $emptyOrder = (count($orderLines) < 1);
  $offer = $orderData['Offer'];
?>
<h1>Bestellung <?php echo $offer['name'] ?> für <?php echo $this->Users->getSalutationPhrase($userData) ?></h1>
<p />
<?php
   if ($emptyOrder) {
   	 echo 'Es wurde nichts bestellt.';
   } else {
   	 echo $this->element('orders/orderLinesTable');
   }
?>
<p />
<?php echo $this->Html->link('Zurück', $this->request->referer())?>