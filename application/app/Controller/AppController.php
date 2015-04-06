<?php

  # /app/controllers/app_controller.php

  class AppController extends Controller {

    var $name = 'App';
    
    var $components = array('Auth' => array(
    	'authorize' => 'Controller'
    ));
    
    /** array of names of actions being available for users being customers (not admins) */
    var $customerActions = array();
    
    function isAuthorized() {
    	$params = $this->request->params;
      $ctrl = strtolower($params['controller']);
      $action = strtolower($params['action']);

      return (
				($ctrl == 'pages')
      	|| in_array($action, $this->customerActions)
        || ($this->Auth->user('profile_id') == 2)
      );
    }
    
    protected function getEntitiesById($model, $conditions = array(), $order = '') {
      $modelName = $model->name;
      $entities = array();
      $findConditions = array('conditions' => $conditions);
      if ($order != '') {
        $findConditions['order'] = $modelName.'.'.$order;
      }
      foreach ($model->find('all', $findConditions) as $entity) {
        $newEntity = $entity[$modelName];
        foreach ($entity as $key => &$subValues) {
          if ($key != $modelName) {
            $newEntity[$key] = $subValues; 
          }
        }
        $entities[$newEntity['id']] = $newEntity;
      }
      return $entities;
    }
    
  }

?>