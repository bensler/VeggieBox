<!-- File: /app/views/orders/preview.ctp -->

<?php 
  echo $this->element('orders/orderForm', array('submit' => false));
  if ($navi != '') {
	  echo $this->Html->link('zurück zu Angebot bearbeiten', '/offers/'.$navi);
  }
?><br />
<?php echo $this->Html->link('Angebotsübersicht', '/offers') ?>