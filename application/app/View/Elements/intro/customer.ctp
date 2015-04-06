<!-- File: /app/views/Elements/intro/customer.ctp -->

<h1>Kunde <?php
  $user = $this->Session->read('Auth.User');
	if ($user['fname'] == '') {
    echo $user['salutation'].' '.$user['lname'];
  } else {
  	echo $user['fname'].' '.$user['lname'];
  }
?></h1>

<h3><?php echo $this->Html->link('Bestellen', '/orders/order'); ?></h3>

<h4><?php echo $this->Html->link('Diesmal nicht bestellen', '/orders/cancel') ?></h4>

<h3><?php echo $this->Html->link('Abmelden', '/users/logout'); ?></h3>

<h4><?php echo $this->Html->link('eigenes Kennwort ändern', '/users/password'); ?></h4>
