<!-- File: /app/elements/offers/ordered.ctp -->

<table border="yes">
  <tr>
    <th>Name</th>
    <th>Telefon</th>
    <th />
  </tr>
  <?php
    setlocale(LC_ALL, 'de_DE');
    foreach ($usersWithOrder as $user):
      $orderId = $user['order_id'];
      $user = $user['User'];
      $tagParams = array();
      if ($user['id'] == $this->Session->read('Order.lastUser')) {
        $tagParams = array('class' => 'currentEntity');
      }
      echo $this->Html->tag('tr', null, $tagParams);
  ?>
    <td><?php echo $this->Users->getSalutationPhrase($user).' ('.$user['username'].')' ?></td>
    <td><?php echo $user['telephone']?></td>
    <td><?php 
      echo $this->Html->link('Ansehen', '/orders/view/'.$orderId);
      if ($offerData['state'] > 100) {
	      echo '&nbsp';
        echo $this->Html->link('Bestellen', '/orders/order/'.$orderId);
        echo '&nbsp';
        echo $this->Html->link('Stornieren', '/orders/cancel/'.$orderId, null, 'Wollen Sie diese Bestellung wirklich stornieren?');
      }
    ?></td>
  </tr>
  <?php endforeach; ?>
</table><p />
