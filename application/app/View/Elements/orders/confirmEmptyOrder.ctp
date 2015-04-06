<!-- File: /app/views/elements/orders/confirmEmptyOrder.ctp -->

<?php   $offer = $orderData['Offer'] ?>

<span style="font-size:120%; font-weight:bold">Es wurde nichts bestellt.</span> 
Keine Lieferung f&uuml;r <?php echo $offer['name'] ?> an <?php echo $this->Users->getSalutationPhrase($userData) ?>.
<p />
Bis <?php echo $this->Offers->formatOfferEndDate($offer) ?> kann bei Bedarf noch bestellt werden 
 (Lieferung am <?php echo $this->Offers->formatDeliveryDate($offer)?>).
<p />
