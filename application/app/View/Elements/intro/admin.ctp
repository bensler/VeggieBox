<!-- File: /app/views/pages/Elements/intro/admin.ctp -->

<h1><?php
  $user = $this->Session->read('Auth.User');
  if ($user['fname'] == '') {
    echo $user['salutation'].' '.$user['lname'];
  } else {
  	echo $user['fname'].' '.$user['lname'];
  }
 	echo (' (Admin)');
?></h1>

<h2><?php echo $this->Html->link('Wochenangebote', '/offers/'); ?></h2>

<h2><?php echo $this->Html->link('Kunden', '/users/'); ?></h2>

<h2><?php echo $this->Html->link('Gemüsesorten', '/vegetables/'); ?></h2>

<h2><?php echo $this->Html->link('Maßeinheiten', '/units/'); ?></h2>

<hr/>

<h3><?php echo $this->Html->link('Abmelden', '/users/logout'); ?></h3>
<h4><?php echo $this->Html->link('eigenes Kennwort ändern', '/users/password'); ?></h4>


