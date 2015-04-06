<?php

  class UsersHelper extends AppHelper {
    
    function getSalutationPhrase($userData) {
		  if ($userData['fname'] == '') {
		    return $userData['salutation'].' '.$userData['lname'];
		  } else {
		    return $userData['fname'].' '.$userData['lname'];
		  }
    }
    
  }
  
?>
