<!-- File: /app/views/elements/orders/confirmOrdered.ctp -->

<?php   $offer = $orderData['Offer'] ?>

<span style="font-size:120%; font-weight:bold">Die Bestellung f&uuml;r <?php echo $offer['name'] ?> 
 für <?php echo $this->Users->getSalutationPhrase($userData) ?> wurde entgegengenommen.</span>
<p />
Diese Bestellung kann bis <?php echo $this->Offers->formatOfferEndDate($offer) ?> ver&auml;ndert oder storniert werden. 
Die Lieferung erfolgt am <?php echo $this->Offers->formatDeliveryDate($offer) ?>.
<p />
<?php echo $this->element('orders/orderLinesTable') ?>     
<p />
