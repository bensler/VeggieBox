<!-- File: /app/views/vegetables/index.ctp -->

<h1>Gem&uuml;sesorten</h1>
  <?php echo $this->Form->create('Vegetables', array('action' => 'index')); ?>
  <table>
    <tr>
      <th>Name</th>
      <th>Abrechnungs-/Bestelleinheit</th>
      <th><!-- Preishistorie --></th>
      <th><!-- error --></th>
    </tr>
  
    <?php
      $i = 0; 
      foreach ($vegetables as $vegetable):
      	$vegetable = $vegetable['Vegetable'];
        echo $this->Htmlx->warningTrOnError(array_key_exists('error', $vegetable));      
    ?>
      <td><?php
        echo $this->Form->hidden("Vegetable.$i.id", array(
          'value' => $vegetable['id'],
        ));
        echo $this->Form->input("Vegetable.$i.name", array(
          'type' => 'text',
          'value' => $vegetable['name'],
          'div' => false,
          'label' => false
        ));
      ?></td>
      <td><?php 
        echo ' €/'; 
			  echo $this->Form->input("Vegetable.$i.Unit", array(
			    'options' => $units,
          'selected' => $vegetable['unit_id'],
			    'div' => false,
			    'label' => false
			  ));
      ?></td>
      <td><?php echo $this->Html->link('Historie', array(
      	'controller' => 'vegetables', 
      	'action' => 'statistics', 
      	$vegetable['id']
      ), array('target' => '_statistics')) ?></td>
      <td><?php echo $this->Htmlx->get($vegetable, 'error', '') ?></td>
    </tr>
    <?php
        $i++;
      endforeach;
    ?>
  </table>
<?php echo $this->Form->end('Speichern'); ?>

<?php echo $this->Html->link('Änderungen verwerfen', '/vegetables/'); ?>
<br />
<?php echo $this->Html->link('Übersicht', '/')?>
