<!-- File: /app/views/pages/intro.ctp -->

<?php
  $user = $this->Session->read('Auth.User');
  $admin = ($user['profile_id'] == 2);
  if ($admin) {
  	echo $this->element('intro/admin');
  } else {
  	echo $this->element('intro/customer');
  }
?>
