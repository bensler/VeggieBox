<!-- File: /app/views/users/password.ctp -->

<h1>Eigenes Kennwort ändern</h1>
<?php	
  echo $this->Form->create('User', array('action' => 'password'));
?>
<table>
	<?php echo $this->Htmlx->createWarningTrOnError('authError', $errors, 2); ?>
	<tr>
	  <td>Altes Kennwort</td><td>
	    <?php
	      echo $this->Form->input('oldPassword', array(
	        'type' => 'password',
	        'value' => '',
	        'label' => false,
	        'size' => 20
	      ));
	    ?>
	  </td>
  </tr>
	<?php echo $this->Htmlx->createWarningTrOnError('passwordError', $errors, 2); ?>
  <tr>
	  <td>Neues Kennwort</td><td>
	    <?php
	      echo $this->Form->input('newPassword', array(
	        'type' => 'password',
	        'value' => '',
	        'label' => false,
	        'size' => 20
	      ));
	    ?>
	  </td>
  </tr>
	<?php echo $this->Htmlx->createWarningTrOnError('repeatError', $errors, 2); ?>
  <tr>
	  <td>Neues Kennwort (Wiederholung)</td><td>
	    <?php
	      echo $this->Form->input('newPasswordRepeat', array(
	        'type' => 'password',
	        'value' => '',
	        'label' => false,
	        'size' => 20
	      ));
	    ?>
	  </td>
  </tr>
</table>
<?php echo $this->Form->end('Kennwort ändern') ?>
<?php echo $this->Html->link('Abrechen', '/'); ?>
