<?php


  class UsersController extends AppController {

    var $name = 'Users';
    
    var $components = array('Session');
    
    var $uses = array('User', 'Profile');
    
    var $helpers = array('Users', 'Session', 'Htmlx');
    
    var $customerActions = array('password');
    
    public function beforeFilter() {
    	$this->Auth->allow('login', 'logout');
    }
     
    public function logout() {
    	$this->Auth->logout();
    	$this->redirect('/');
    }
    
    public function login() {
    	if ($this->request->is('post')) {
    		if ($this->Auth->login()) {
    			$this->redirect($this->Auth->redirect());
    		} else {
    			$this->Session->setFlash(__('Benutzername oder Kennwort ist falsch.'));
    		}
    	}
    }
    
    function addressList() {
    	$addresses = "";
    	$lastRequest = array();
    	$dbUsers = $this->User->find('all', array('order' => 'User.lname'));
    	if (!empty($this->request->data)) {
    		$lastRequest = $this->request->data;
    		$request = $lastRequest['User'];
    		$delimiter = '';
    		foreach ($dbUsers as $userData) {
    			$userData = $userData['User'];
    			$userId = $userData['id'];
    			if ($request[$userId]['send']) {
    				foreach (explode(';', $userData['email']) as $email) {
    				  $addresses = $addresses.$delimiter.$userData['fname'].' '.$userData['lname'];
    				  $addresses = $addresses.' <'.$email.'>';
    				  $delimiter = "\n";
    				}
    			}
    		}
    	}
   		$this->set('lastRequest', $lastRequest);
   		$this->set('addresses', $addresses);
   		$this->set('userData', $dbUsers);
    }
    
    function view($id) {
	    $this->User->id = $id;
      $this->Session->write('User.last', $id);
	    $this->set('user', $this->User->read());
    }

    function index() {
	    $this->set('authUser', $this->Auth->user());
	    $this->set('usersData', $this->User->find('all', array('order' => 'User.lname')));
    }

    function add() {
    	$errors = array();
	    if (!empty($this->request->data)) {
	    	$errors = $this->validateAdd($this->request->data);
	    	if (empty($errors)) {
	        if ($this->User->save($this->request->data)) {
	        	$this->Session->write('User.last', $this->User->id);
		        $this->flash('Der Benutzer wurde angelegt.', '/users');
	        } else {
            $this->flash('Es ist ein Datenbank-Fehler aufgetreten.', '/');
	        }
	    	}
      }
      $this->set('errors', $errors);
      $this->set('profiles', $this->Profile->find('list', array(
        'order' => 'Profile.id'
      )));
    }

    function validateAdd($data) {
    	$errors = array();
    	$user = $data['User'];
      if ($user['salutation'] == '') {
        $errors['salutationError'] = 'Es muss eine Anrede eingegeben werden.';
      }
      if ($user['lname'] == '') {
        $errors['lnameError'] = 'Es muss ein Nachname eingegeben werden.';
      }
      $this->checkPassword($user, $errors, false);
      if ($user['email'] == '') {
        $errors['emailError'] = 'Es muss eine Email-Adresse eingegeben werden.';
      }
      $username = $user['username'];
      if ($username == '') {
        $errors['usernameError'] = 'Es muss eine Anmeldename eingegeben werden.';
      } else {
      	$sameUsernameUser = $this->User->find('first', array('conditions' => array('User.username' => $username)));
	      if (!empty($sameUsernameUser)) {
      	  $sameUsernameUser = $this->getSalutationPhrase($sameUsernameUser['User']);
          $errors['usernameError'] = "Der Anmeldename ist nicht eindeutig (verwendet von {$sameUsernameUser}).";
	      }
      }
      if (($user['zip'] == '') || ($user['city'] == '')) {
        $errors['zipCityError'] = 'Es müssen PLZ und Ort eingegeben werden.';
      }
      if ($user['address'] == '') {
        $errors['addressError'] = 'Es muss eine Anschrift eingegeben werden.';
      }
      return $errors;
    }
    
    function delete($id) {
      $authUser = $this->Auth->user();
      if ($id != $authUser['id']) {
        if ($this->User->delete($id, false)) {
          $this->Session->write('User.last', $id);
        	$this->redirect($this->referer('/users', true));
        } else {
        	$this->flash(
        	  'Der Benutzer konnte nicht gelöscht werden. Wahrscheinlich existieren Bestellungen dieses Benutzers.',
        	  '/users', 0
        	);
        }
      } else {
        $this->flash(
          'Sie können nicht den Benutzer löschen als der Sie angemeldet sind.', '/users', 0
        );
      }
    }
        
    function edit($id) {
      $errors = array();
    	$this->Session->write('User.last', $id);
    	if (empty($this->request->data)) {
      	$this->request->data = $this->User->read(null, $id);
      } else {
      	$errors = $this->validateEdit($this->request->data);
        if (empty($errors)) {
          if ($this->User->save($this->request->data)) {
            $this->flash('Die Benutzerdaten wurden gespeichert.', '/users');
          } else {
            $this->flash('Es ist ein Datenbank-Fehler aufgetreten.', '/');
          }
        }
      }
      $this->set('errors', $errors);
    }
    
    function validateEdit(&$data) {
      $errors = array();
      $user = &$data['User'];
      if ($user['salutation'] == '') {
        $errors['salutationError'] = 'Es muss eine Anrede eingegeben werden.';
      }
      if ($user['lname'] == '') {
        $errors['lnameError'] = 'Es muss ein Nachname eingegeben werden.';
      }
      $this->checkPassword($user, $errors, true);
      if ($user['email'] == '') {
        $errors['emailError'] = 'Es muss eine Email-Adresse eingegeben werden.';
      }
      if (($user['zip'] == '') || ($user['city'] == '')) {
        $errors['zipCityError'] = 'Es müssen PLZ und Ort eingegeben werden.';
      }
      if ($user['address'] == '') {
        $errors['addressError'] = 'Es muss eine Anschrift eingegeben werden.';
      }
      return $errors;
    }

    function checkPassword(&$user, &$errors, $allowEmpty) {
      $pwHash = $this->Auth->password($user['password']);
      $user['password'] = $pwHash;
      $repeat = $user['passwordRepeat'];
      $repeatHash = $this->Auth->password($repeat);
      $error = '';
      if ($pwHash != $repeatHash) {
        $error = 'Das Kennwort wurde nicht korrekt wiederholt.';
      } else {
	      if (($pwHash == $this->Auth->password('')) && (!$allowEmpty)) {
	        $error = 'Es muss ein Kennwort eingegeben werden.';
	      }
      }
      if ($error != '') {
        $errors['passwordError'] = $error;
      } else {
      	if ($allowEmpty && ($repeat == '')) {
      		unset($user['password']);
      	}
      }
    }
    
    /** Changes the password of the user currently logged in. */
    function password() {
    	$errors = array();
      if (!empty($this->request->data)) {
      	$user = $this->request->data['User'];
      	if ($this->reauthCurrentUser($user, $errors)) {
	      	if ($this->checkNewPassword($user, $errors)) {
		        if ($this->User->save(array('User' => $user))) {
		          $this->flash('Das Kennwort wurde geändert.', '/');
		        } else {
	            $this->flash('Es ist ein Datenbank-Fehler aufgetreten.', '/');
	          }
	          return;
	      	}
	      }
      }
      unset($this->request->data);
      $this->set('errors', $errors);
    }
    
    private function reauthCurrentUser(&$user, &$errors) {
    	$password = $user['oldPassword'];
    	unset($user['oldPassword']);
    	$currentUser = $this->Auth->user();
    	$userName = $currentUser['username'];
    	$result = $this->User->find('first', array(
    		'conditions' => array(
    			'User.username' => $userName,
    			'User.password' => $this->Auth->password($password)
    		), false
    	));
    	if (empty($result)) {
        $errors['authError'] = 'Das alte Kennwort ist falsch';
    		return false;
    	}
    	$user['username'] = $userName;
    	$user['id'] = $currentUser['id'];
    	return true;
    }
    
    private function checkNewPassword(&$user, &$errors) {
    	$success = true;
    	$newPw = $user['newPassword'];
    	unset($user['newPassword']);
    	$pwRepeat = $user['newPasswordRepeat'];
    	unset($user['newPasswordRepeat']);
    	if ($newPw == '') {
    		$errors['passwordError'] = 'Es muss ein neues Kennwort eingegeben werden.';
	    	$success = false;
    	}
    	if ($newPw != $pwRepeat) {
    		$errors['repeatError'] = 'Das neue Kennwort wurde nicht korrekt wiederholt.';
	    	$success = false;
    	}
    	if ($success) {
	    	$user['password'] = $this->Auth->password($newPw);
    	}
    	return $success;
    }
    
    /** Duplicated code from UsersHelper */
    function getSalutationPhrase($userData) {
      if ($userData['fname'] == '') {
        return $userData['salutation'].' '.$userData['lname'];
      } else {
        return $userData['fname'].' '.$userData['lname'];
      }
    }
    
  }

?>
