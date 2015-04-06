<!-- File: /app/views/offers/close.ctp -->
<h1>Bestellungen für Wochenangebot <?php echo $offerData['name'] ?> (<?php echo $this->Offers->formatOfferState($offerData)?>)</h1>

<h2>Vergesser <?php echo '('.count($usersWhoForgot).')' ?></h2>
<?php echo empty($usersWhoForgot) ? 'keine Vergesser' : $this->element('offers/forgotToOrder'); ?>
<p />

<h2>Nichtbesteller <?php echo '('.count($usersWithEmptyOrder).')' ?></h2>
<?php echo empty($usersWithEmptyOrder) ? 'keine Nichtbesteller' : $this->element('offers/didNotOrder'); ?>
<p />

<h2>Besteller <?php echo '('.count($usersWithOrder).')' ?></h2>
<?php echo empty($usersWithOrder) ? 'keine Besteller' : $this->element('offers/ordered'); ?>
<p />

<?php   
  $offerId = $offerData['id'];
  echo $this->Html->link('Aktualisieren', '/offers/orders/'.$offerId)
?>
<p />
<?php echo $this->Html->link('Angebot beenden', array('action' => 'close', $offerId), null, 'Wollen Sie dieses Angebot wirklich beenden?');?>
<p />
<?php echo $this->Html->link('PDF erzeugen', '/offers/pdf/'.$offerId)?>
<p />
<?php echo $this->Html->link('Angebotsübersicht', '/offers/')?>

