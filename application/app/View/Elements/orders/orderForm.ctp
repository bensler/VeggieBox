<!-- File: /app/views/elements/orders/orderForm.ctp -->

<?php
  $offer = $offerData['Offer'];
  echo $this->Form->create('Order', array('action' => 'order'));
?>
<h1>Bestellung <?php echo $offer['name'] ?> für <?php echo $this->Users->getSalutationPhrase($userData) ?></h1><p />
Es kann bis <?php echo $this->Offers->formatOfferEndDate($offer) ?> bestellt werden, die Lieferung
erfolgt am  <?php echo $this->Offers->formatDeliveryDate($offer) ?>.
<p />
<?php echo $offer['introduction'] ?><p />
<table cellspacing="0">
  <?php 
    $i = 0;
    foreach($offerData['OfferLine'] as $offerLine) {
      $vegetable = $offerLine['Vegetable'];
      $comment = $offerLine['comment'];
      $comment = (($comment == '') ? '' : ' ('.$comment.')');
      $soldOut = ($offerLine['sold_out'] == '1');
  ?>
  <?php echo $this->Htmlx->rowStyleOnCondition($soldOut, 'sold_out') ?>
    <td style="padding-right:2em"><b><?php
      echo $this->Form->hidden("OrderLine.$i.offer_line_id", array('value' => $offerLine['id']));
      echo $vegetable['name']; ?></b>
      <small><?php echo $comment; ?></small>
    </td>
    <td style="padding-right:2em"><?php 
      if (array_key_exists('id', $offerLine['OrderLine'])) {
        echo $this->Form->hidden("OrderLine.$i.id", array('value' => $offerLine['OrderLine']['id']));
      }
	    $fractionDigits = $vegetable['Unit']['fraction_digits'];
	    echo $this->Form->hidden("OrderLine.$i.fraction_digits", array('value' => $fractionDigits));
	    $quantityOrdered = $offerLine['OrderLine']['quantity_ordered'];
	    $ordered = (($quantityOrdered != '') && ($quantityOrdered != '0'));
	    $qtyFieldAttr = array(
        'type' => 'text',
        'style' => 'text-align: right', 'size' => 5, 'maxLength' => 10,
        'label' => false,
        'div' => false
      );
	    if ($ordered) {
	      $quantityOrdered = number_format(
	        $quantityOrdered / 100.0, (int)$fractionDigits, ',', ''
	      );
	    } else {
	    	if ($soldOut && (!$ordered)) {
          $qtyFieldAttr['readonly'] = 'readonly';
	    	}
	    }
	    $Unit = $vegetable['Unit']['name'];
      $qtyFieldAttr['value'] = $quantityOrdered;
	    echo $this->Form->input("OrderLine.$i.quantity_ordered", $qtyFieldAttr).' '.$Unit;
    ?></td>
    <td><?php echo $offerLine['price'].' €/'.$vegetable['Unit']['name'] ?></td>
    <td>
    <?php
      if ($soldOut) {
        echo $this->Html->tag('b', 'Ausverkauft! ');
        if ($ordered) {
          echo $this->Html->tag('small', 'Sie können maximal '.$quantityOrdered.' '.$Unit.' bestellen.');
        }
      }
    ?>
    </td>
  </tr>
<?php     
    $i++;
  }
?>
<tr><td style="text-align: right; vertical-align: top;" colspan="4">
	Kommentar: 
	<div style="display:inline;">
	<?php
	  if ($submit) {
		  echo $this->Form->hidden("navi", array('value' => $navi));
		  echo $this->Form->hidden("Order.id", array('value' => $orderData['Order']['id']));
		  echo $this->Form->hidden("Order.user_id", array('value' => $userData['id']));
		  echo $this->Form->hidden("Order.offer_id", array('value' => $offer['id']));
	  }
	  echo $this->Form->input('Order.comment', array(
	    'rows' => 2,
	    'value' => ($submit ? $orderData['Order']['comment'] : ''),
	    'label' => false, 'div' => false 
	  )); 
	?>
	</div>
</td></tr>
<tr><td style="text-align: right;" colspan="4">
<?php
	  if ($submit) {
	    echo $this->Form->submit('Bestellung speichern');
	  }
?>
</td></tr>
</table>
<?php echo $this->Form->end(); ?>
	<p />
<?php
  if ($submit && ($navi == 'offers')) {
    echo $this->Html->link('Zurück zur Bestellübersicht', '/offers/orders/'.$offer['id']);
  } 
?>

