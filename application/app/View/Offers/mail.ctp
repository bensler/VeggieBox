<!-- File: /app/views/offers/activate.ctp -->
<h1>Benachrichtigungs-Emails f&uuml;r Wochenangebot <?php echo $offerData['Offer']['name']; ?></h1>

Empf&auml;nger: <p />
<?php 
  echo $this->Form->create('Offer', array('action' => 'mail'));
  echo $this->Form->input('Offer.id', array(
    'type' => 'hidden',
    'value' => $offerData['Offer']['id']
  ));
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
      $admin = ($userData['profile_id'] == 2);
  ?>
  <tr>
    <td><?php 
	    echo $this->Form->input("User.{$userData['id']}.send", array(
        'type' => 'checkbox',
	      'checked' => $userData['active'],
	      'label' => false,
	      'div' => false
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
<?php echo $this->Form->end('Emails versenden'); ?><p />

<?php echo $this->Html->link('Übersicht', '/offers/'); ?>

