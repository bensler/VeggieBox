<!-- File: /app/views/units/index.ctp -->

<h1>Ma&szlig;einheiten / Gebinde</h1>
  <?php echo $this->Form->create('Units', array('action' => 'index')); ?>
  <table>
    <tr>
      <th>Name</th>
      <th>Nachkommastellen</th>
      <th><!-- error --></th>
    </tr>
  
    <?php
      $i = 0; 
      foreach ($units as &$unit):
        $unit = $unit['Unit'];
	      echo $this->Htmlx->warningTrOnError(array_key_exists('error', $unit));      
    ?>
      <td><?php
        echo $this->Form->hidden("Unit.$i.id", array(
          'value' => $unit['id']
        ));
        echo $this->Form->input("Unit.$i.name", array(
          'type' => 'text',
          'value' => $unit['name'],
          'div' => false,
          'label' => false
        ));
      ?></td>
      <td><?php
        echo $this->Form->input("Unit.$i.fraction_digits", array(
          'type' => 'text',
          'value' => $unit['fraction_digits'],
          'div' => false,
          'label' => false
        ));
      ?></td>
      <td><?php echo $this->Htmlx->get($unit, 'error', '') ?></td>
    </tr>
    <?php
        $i++;
      endforeach;
    ?>
  </table>
<?php echo $this->Form->end('Speichern'); ?>

<br />
<?php echo $this->Html->link('Änderungen verwerfen', '/units/'); ?>

<?php echo $this->Html->link('Übersicht', '/')?>
