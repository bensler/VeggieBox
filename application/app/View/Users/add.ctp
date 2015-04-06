<!-- File: /app/views/users/add.ctp -->

<h1>Benutzer anlegen</h1>
<?php echo $this->Form->create('User') ?>
<table>
  <tr><td>Profil</td><td>
	  <?php
		  echo $this->Form->input("profile_id", array(
		    'options' => $profiles,
		    'selected' => '1',
		    'label' => false
		  ));
    ?>
  </td><td>&nbsp;</td></tr>
  <?php echo $this->Htmlx->warningTrOnError(array_key_exists('salutationError', $errors)); ?>
  <td>Anrede</td><td>
    <?php
		  echo $this->Form->input('salutation', array(
				'label' => false,
        'size' => 10, 'maxlength' => 45
			));
    ?>
  </td><td><?php echo $this->Htmlx->get($errors, 'salutationError', '') ?></td>
  </tr><tr><td>(Vorname)</td><td>
    <?php
			echo $this->Form->input('fname', array(
		    'label' => false,
        'size' => 20, 'maxlength' => 45
      ));
    ?>
  </td><td>&nbsp;</td></tr>
  <?php echo $this->Htmlx->warningTrOnError(array_key_exists('lnameError', $errors)); ?>
  <td>Nachname</td><td>
    <?php
      echo $this->Form->input('lname', array(
        'label' => false,
        'size' => 20, 'maxlength' => 90
  	  ));
    ?>
  </td>
  <td><?php echo $this->Htmlx->get($errors, 'lnameError', '') ?></td>
  </tr>
  <tr><td colspan=3>&nbsp;</td></tr>
  <?php echo $this->Htmlx->warningTrOnError(array_key_exists('loginError', $errors)); ?>
  <td>Anmeldename</td><td>
    <?php
      echo $this->Form->input('username', array(
        'label' => false,
        'size' => 10, 'maxlength' => 18
      ));
    ?>
  </td><td><?php echo $this->Htmlx->get($errors, 'loginError', '') ?></td></tr>
  <?php echo $this->Htmlx->warningTrOnError(array_key_exists('passwordError', $errors)); ?>
  <td>Kennwort</td><td>
    <?php
  	  echo $this->Form->input('password', array(
		    'value' => '',
		    'label' => false,
        'size' => 20
		  ));
    ?>
  </td><td><?php echo $this->Htmlx->get($errors, 'passwordError', '') ?></td>
  <?php echo $this->Htmlx->warningTrOnError(array_key_exists('passwordError', $errors)); ?>
  <td>Kennwort (Wiederholung)</td><td>
    <?php
		  echo $this->Form->input('passwordRepeat', array(
		    'type' => 'password',
		    'value' => '',
		    'label' => false,
        'size' => 20
		  ));
    ?>
  </td><td>&nbsp;</td></tr>
  <?php echo $this->Htmlx->warningTrOnError(array_key_exists('emailError', $errors)); ?>
  <td>Email</td><td>
    <?php
		  echo $this->Form->input('email', array(
		    'label' => false,
        'size' => 20, 'maxlength' => 90
			));
    ?>
  </td><td><?php echo $this->Htmlx->get($errors, 'emailError', '') ?></td></tr>
  <tr><td colspan=3>&nbsp;</td></tr>
  <tr><td>(Telefon)</td><td>
    <?php
			echo $this->Form->input('telephone', array(
		    'label' => false,
        'size' => 20, 'maxlength' => 90
			));
    ?>
  </td><td>&nbsp;</td></tr>
  <?php echo $this->Htmlx->warningTrOnError(array_key_exists('addressError', $errors)); ?>
  <td>Anschrift</td><td>
    <?php
			echo $this->Form->input('address', array(
		    'label' => false,
        'size' => 20, 'maxlength' => 90
			));
    ?>
  </td><td><?php echo $this->Htmlx->get($errors, 'addressError', '') ?></td></tr>
  <?php echo $this->Htmlx->warningTrOnError(array_key_exists('zipCityError', $errors)); ?>
  <td>PLZ - Ort</td><td>
    <?php
			echo $this->Form->input('zip', array(
		    'label' => false, 'div' => false,
			  'size' => 5, 'maxlength' => 5
			));
		?> - <?php
			echo $this->Form->input('city', array(
		    'label' => false, 'div' => false,
        'size' => 15, 'maxlength' => 15
			));
    ?>
  </td><td><?php echo $this->Htmlx->get($errors, 'zipCityError', '') ?></td></tr>
</table>
<?php	echo $this->Form->end('Benutzer anlegen') ?>

<?php echo $this->Html->link('Abrechen', '/users/'); ?>

