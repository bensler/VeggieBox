<!-- File: /app/views/users/login.ctp -->

<h1>Anmelden</h1>
<?php	echo $this->Form->create('User', array('action' => 'login')) ?>
		<?php
			echo $this->Form->input('username', array(
				'type' => 'text',
				'size' => '15',
				'label' => 'Benutzername:'
			));
		?>
		<?php
			echo $this->Form->input('password', array(
				'type' => 'password',
				'size' => '15',
				'label' => 'Kennwort:'
			));
		?>
<?php echo $this->Form->end('Anmelden') ?>

<script type="application/javascript"><!--
  function setfocus(){
    document.getElementById('UserUsername').focus(); 
  }
  setfocus();
--></script>

