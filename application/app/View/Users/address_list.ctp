<!-- File: /app/views/users/address_list.ctp -->
<h1>Email-Adressliste anzeigen</h1>

<?php 
  echo $this->Form->create('AddressList');
  echo $this->Form->input('list', array(
  	'type' => 'textarea',
  	'value' => $addresses,
	  'label' => false, 'div' => false
  ));
  echo $this->Html->link('Übersicht', '/users/');
  echo $this->Form->submit('Adressliste anzeigen');
  $lastRequest = (array_key_exists('User', $lastRequest) ? $lastRequest['User'] : array());
?>
<table>
  <tr>
    <th></th>
    <th>Name</th>
    <th>Email</th>
    <th>Telefon</th>
  </tr>

  <?php 
    foreach ($userData as $user) {
      $userData = $user['User'];
      $userId = $userData['id'];
      $admin = ($userData['profile_id'] == 2);
      $checkedLast = (
      	array_key_exists($userId, $lastRequest)
      	?	$lastRequest[$userId]['send']	: $userData['active']
      );
  ?>
  <tr>
    <td><?php 
	    echo $this->Form->input("User.{$userData['id']}.send", array(
        'type' => 'checkbox',
	      'checked' => $checkedLast,
	      'label' => false, 'div' => false
	    ))
    ?></td>
    <td><?php 
      if ($admin) {echo $this->Html->tag('b');}
      echo $this->Users->getSalutationPhrase($userData);
    ?></td>
    <td><?php echo $userData['email']; ?></td>
    <td><?php echo $userData['telephone']; ?></td>
  </tr>
  <?php
    }
  ?>

</table>
<?php echo $this->Form->end('Adressliste anzeigen'); ?><p />

<?php echo $this->Html->link('Übersicht', '/users/'); ?>

