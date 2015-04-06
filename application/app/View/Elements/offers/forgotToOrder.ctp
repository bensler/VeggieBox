<!-- File: /app/elements/offers/forgotToOrder.ctp -->

<table border="yes">
  <tr>
    <th>Name</th>
    <th>Telefon</th>
    <th />
  </tr>
  <?php
    setlocale(LC_ALL, 'de_DE');
    foreach ($usersWhoForgot as $user):
      $tagParams = array();
      if ($user['id'] == $this->Session->read('Order.lastUser')) {
        $tagParams = array('class' => 'currentEntity');
      }
      echo $this->Html->tag('tr', null, $tagParams);
  ?>      
    <td><?php echo $this->Users->getSalutationPhrase($user).' ('.$user['username'].')' ?></td>
    <td><?php echo $user['telephone']?></td>
    <td><?php 
      if ($offerData['state'] > 100) {
	      echo $this->Html->link('Bestellen', array(
				  'controller' => 'orders',
				  'action' => 'order',
				  '?' => array(
				    'user_id' => $user['id'],
	          'offer_id' => $offerData['id']
	        )
				));
      }
		?></td>
  </tr>
  <?php endforeach; ?>
</table><p />
