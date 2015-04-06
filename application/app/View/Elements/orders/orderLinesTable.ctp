<!-- File: /app/views/elements/orders/orderLinesTable.ctp -->

<table>
  <?php 
  $order = $orderData['Order'];
  $orderLines = $orderData['OrderLine'];
  foreach($orderLines as $orderLine):
      $offerLine = $orderLine['OfferLine'];
      $vegetable = $offerLine['Vegetable'];
  ?>
  <tr style="vertical-align:top">
    <td style="text-align:right; padding-bottom:1em; padding-right:2em"><?php
      $unit = $vegetable['Unit']['name'];
      $fractionDigits = $vegetable['Unit']['fraction_digits'];
      $quantityOrdered = $orderLine['quantity_ordered'];
      if (($quantityOrdered != '') && ($quantityOrdered != '0')) {
        $quantityOrdered = number_format(
          $quantityOrdered / 100.0, (int)$fractionDigits, ',', '.'
        );
      }
      $quantityDelivered = $orderLine['quantity_delivered'];
      if (($quantityDelivered != '') && ($quantityDelivered != '0')) {
        $quantityDelivered = number_format(
          $quantityDelivered / 100.0, (int)$fractionDigits, ',', '.'
        );
      }
      $diff = ($isAdmin && ($quantityDelivered != $quantityOrdered));
      if ($diff) {
        echo $quantityDelivered.' '.$unit;
        echo ' (bestellt: '.$quantityOrdered.' '.$unit.')';
      } else {
        echo $quantityOrdered.' '.$unit;
      }
    ?></td>
    <td style="padding-bottom:1em; padding-right:2em">
      <b><?php echo $vegetable['name'] ?></b>
      <?php if ($offerLine['comment'] != '') {
      	echo '<br />';
      	echo $this->Html->tag('small', $offerLine['comment']);
      } ?>
    </td>
    <td><?php echo $offerLine['price'].' €/'.$unit ?></td>
    <td style="text-align:right;"><?php
      $price = number_format($orderLine['price'] / 100, 2, ',', '.'); 
      echo $price.' €';
    ?></td>
  </tr>
<?php     
  endforeach;
?>
  <tr>
    <td colspan="3" style="text-align:right; font-weight:bold">Summe:</td>
    <td style="text-align:right; font-weight:bold"><?php
      $price = number_format($order['price'] / 100, 2, ',', '.'); 
      echo $price.' €'; 
    ?></td>
  </tr>
</table>
<p />
<?php 
  $comment = $order['comment'];
  if ($comment != '') {
    echo 'Kommentar: '.$order['comment'];
  }
?>
