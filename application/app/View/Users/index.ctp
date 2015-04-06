<!-- File: /app/views/users/index.ctp -->

<h1>Benutzer</h1>
<p />
<?php echo $this->Html->link('Benutzer anlegen','/users/add')?>&nbsp;
<?php echo $this->Html->link('Mailadressen','/users/addressList')?>
<br /> 
<?php echo $this->Html->link('Übersicht', '/')?>
<p />

<table>
	<tr>
		<th>Anmeldename</th>
		<th>Name</th>
		<th>Email</th>
		<th>Telefon</th>
		<th colspan='2'>Addresse</th>
		<th colspan='2'>Aktionen</th>
	</tr>

	<?php 
	  foreach ($usersData as $user) {
      $userData = $user['User'];
      $userId = $userData['id'];
      $admin = ($userData['profile_id'] == 2);
	 ?>
  <?php 
    $tagParams = array();
    if ($userId == $this->Session->read('User.last')) {
      $tagParams = array('class' => 'currentEntity');
    }
    echo $this->Html->tag('tr', null, $tagParams);
  ?>      
		<td><?php 
		  if ($admin) {echo $this->Html->tag('b');}
      echo $userData['username']; 
    ?></td>
		<td><?php 
      if ($admin) {echo $this->Html->tag('b');}
		  echo $this->Users->getSalutationPhrase($userData);
	  ?></td>
		<td><?php 
		  echo ($userData['active'] ? '' : '(').$userData['email'].($userData['active'] ? '' : ')');
		?></td>
		<td><?php echo $userData['telephone']; ?></td>
    <td><?php echo $userData['address']; ?></td>
    <td><?php echo $userData['zip'].' '.$userData['city'] ?></td>
    <td><?php
       echo $this->Html->link('Bearbeiten', "/users/edit/".$userId);
    ?>&nbsp;<?php
        if ($userId != $authUser['id']) {
        	echo $this->Html->link('Löschen', array('action' => 'delete', $userId), null, 'Wollen Sie diesen Benutzer wirklich löschen?');
        }
    ?></td>
  </tr>
	<?php } ?>

</table>

<?php echo $this->Html->link('Benutzer anlegen','/users/add')?>&nbsp;
<?php echo $this->Html->link('Mailadressen','/users/addressList')?>
<p />
<?php echo $this->Html->link('Übersicht', '/')?>
