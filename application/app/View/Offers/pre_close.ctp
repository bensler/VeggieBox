<!-- File: /app/views/offers/close.ctp -->
<h1>Wochenangebot beenden</h1>

<h2>Vergesser</h2>
<?php
  if (empty($usersWhoForgot)) {
  	echo 'keine Vergesser';
  } else {
    echo $this->element('offers/forgotToOrder');
  } 
?>
<p />
<h2>Nichtbesteller</h2>
<?php
  if (empty($usersWithEmptyOrder)) {
    echo 'keine Nichtbesteller';
  } else {
    echo $this->element('offers/didNotOrder');
  } 
?>
<p />
<?php echo $this->Html->link('Aktualisieren', '/offers/preClose/'.$offerId)?>

<?php echo $this->Html->link('Abschließen', '/offers/close/'.$offerId)?>

<?php echo $this->Html->link('PDF', '/offers/pdf/'.$offerId)?>
  

