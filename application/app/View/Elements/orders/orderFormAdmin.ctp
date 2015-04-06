<!-- File: /app/views/elements/orders/orderFormAdmin.ctp -->

<?php
  $offer = $offerData['Offer'];
  echo $this->Form->create('Order', array('action' => 'order'));
?>
<h1>Nachbearbeitung</h1>
<p />
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
      echo $this->Form->hidden("OrderLine.$i.offer_line_id", array('value' => $offerLine['id']));
  ?>
  <?php echo $this->Htmlx->rowStyleOnCondition($soldOut, 'sold_out') ?>
    <td style="padding-right:2em"><b>
      <?php echo $vegetable['name'] ?>
      </b><small>
      <?php echo $comment; ?>
      </small>
    </td>
    <td style="padding-right:2em"><?php 
      if (array_key_exists('id', $offerLine['OrderLine'])) {
	      echo $this->Form->hidden("OrderLine.$i.id", array('value' => $offerLine['OrderLine']['id']));
      }
	    $fractionDigits = $vegetable['Unit']['fraction_digits'];
	    echo $this->Form->hidden("OrderLine.$i.fraction_digits", array('value' => $fractionDigits));
	    $orderLine = $offerLine['OrderLine'];
	    $quantityOrdered = (isset($orderLine['quantity_delivered']) ? $orderLine['quantity_delivered'] : '');
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
      } 
    ?>
    </td>
  </tr>
<?php     
    $i++;
  }
?>
</table>
Kommentar 
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
  if ($submit) {
    echo $this->Form->end('Bestellung speichern');
  } else {
    echo $this->Form->end();
  }
?><p />
<?php
  if ($submit && ($navi == 'offers')) {
    echo $this->Html->link('Zurück zur Bestellübersicht', '/offers/orders/'.$offer['id']);
  } 
?>

