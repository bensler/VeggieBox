<!-- File: /app/elements/offers/didNotOrder.ctp -->

<table border="yes">
  <tr>
    <th>Name</th>
    <th>Telefon</th>
    <th>Kommentar</th>
    <th />
  </tr>
  <?php
    setlocale(LC_ALL, 'de_DE');
    foreach ($usersWithEmptyOrder as $user):
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
    <td><?php echo $user['comment']?></td>
    <td><?php
      if ($offerData['state'] > 100) {
        echo $this->Html->link('Bestellen', '/orders/order/'.$orderId);
      }
    ?></td>
  </tr>
  <?php endforeach; ?>
</table><p />
