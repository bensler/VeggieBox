<?php

  class UsersComponent extends Component {
  	
    function getSalutationPhrase($userData) {
      if ($userData['fname'] == '') {
        return $userData['salutation'].' '.$userData['lname'];
      } else {
        return $userData['fname'].' '.$userData['lname'];
      }
    }

    function isAdmin($auth) {
      $user = $auth->user();
      return ($user['profile_id'] == '2');
    }
  	
  }
?>
