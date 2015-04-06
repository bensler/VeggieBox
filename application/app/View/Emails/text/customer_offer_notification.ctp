Liebe(r) <?php echo $salutationPhrase; ?>,
<?php
  $intro = $offerData['introduction'];
  echo (($intro != '') ? "\n" : '');
  echo $intro;
  echo (($intro != '') ? "\n" : '');
?> 
Bestellungen sind möglich bis zum <?php echo $endDate ?>, die Lieferung erfolgt
am <?php echo $deliveryDate ?>.

Zum Bestellen bitte hier klicken:
http://www.ludwigs-garten.de/orders/order
(die Bestellung kann mit diesem Link bis zum Bestellschluß jederzeit eingesehen und verändert werden)
 
Diesmal nichts bestellen: 
http://www.ludwigs-garten.de/orders/cancel

Es grüßt Euch Ludwig.
