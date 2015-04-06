<!-- File: /app/views/offers/edit.ctp -->
  <?php 
    $offer = $offerData['Offer'];
    $active = $offer['state'] == 200;
  ?>
  <h1>Wochenangebot <?php echo ($active ? '(aktiv!)' : '') ?> <?php echo ($offer['id'] == '') ? 'anlegen' : 'bearbeiten' ?></h1>
  <?php 
    setlocale(LC_ALL, 'de_DE');
    echo $this->Form->create('Offer', array('action' => 'edit'));
    echo $this->Form->hidden('id');
  ?>
  <table><tr><td>
  <table width="100%">
  <?php echo $this->Htmlx->warningTrOnError(array_key_exists('errorName', $errors)) ?>      
	  <td>Name</td><td>
	  <?php
	    echo $this->Form->input('name', array(
	      'type' => 'text',
	      'div' => false,
	      'label' => false
	    ));
	  ?></td><td><?php echo $this->Htmlx->get($errors, 'errorName', '') ?></td>
  </tr><tr><td align="top">Einleitung</td><td>  
	  <?php
	    echo $this->Form->input('introduction', array(
	      'size' => '100%',
	      'label' => false,
        'div' => false
	    ));
    ?>
  </td></tr>
  <tr><td>Bestellschlu&szlig;</td><td>
	  <?php 
	    echo $this->Form->input('end_date', array(
	      'label' => false,
	      'dateFormat' => 'DMY',
	      'timeFormat' => '24',
	      'minYear' => date('Y'),
	      'maxYear' => date('Y') + 1
	    ));
	  ?>
  </td></tr>
  <tr><td>Lieferdatum</td><td>
    <?php 
      echo $this->Form->input('delivery_date', array(
        'label' => false,
        'type' =>'date',
        'dateFormat' => 'DMY',
        'minYear' => date('Y'),
        'maxYear' => date('Y') + 1
      ));
    ?>
  </td></tr>
  </table>
  </td></tr><tr><td>
  <table width="100%">
    <tr>
      <th>Preis</th>
      <th>Gem&uuml;sesorte</th>
      <th>Kommentar</th>
      <th>Sortierung</th>
      <th>ausverkauft</th>
      <th></th>
    </tr><tr>
    <?php
      $offerLines = $offerData['OfferLine'];
      for ($i = 0; $i < 5; $i++) {
        array_push($offerLines, array(
          'vegetable_id' => null,
          'sold_out' => '0'
        ));
      }
      $i = 0; 
      foreach ($offerLines as $offerLine) {
	    echo $this->Htmlx->warningTrOnError(array_key_exists($i, $errors));
    ?>
      <?php echo $this->Form->hidden("OfferLine.$i.id"); ?>
      <td><?php
        echo $this->Form->input("OfferLine.$i.price", array(
          'type' => 'text',
          'size' => 5, 'maxLength' => 15,
          'div' => false,
          'label' => false
        ));
      ?></td>
      <td><?php 
        echo $this->Form->input("OfferLine.$i.vegetable_id", array(
          'options' => $offerData['vegetableNames'],
          'value' => $offerLine['vegetable_id'],
        	'style' => 'width:100%',
          'label' => false
        ));
      ?></td>
      <td align="right"><?php
	      echo $this->Form->input("OfferLine.$i.comment", array(
	        'type' => 'text',
	        'size' => 20, 'maxLength' => 90,
	        'div' => false,
	        'label' => false
	      ));
	    ?></td>
      <td align="right"><?php
        echo $this->Form->input("OfferLine.$i.sort_order", array(
          'type' => 'text',
        	'size' => 4,
          'value' => (10 + ($i * 10)),
          'div' => false,
          'label' => false
        ));
      ?></td><td align="center">
      <?php if ($active) {
        echo $this->Form->input("OfferLine.$i.sold_out", array(
          'type' => 'checkbox',
          'checked' => ($offerLine['sold_out'] == '1'),
          'label' => false,
          'div' => false
        ));
      }?></td>
      <td><?php
        $br = false;
        $messages = $this->Htmlx->get($errors, $i, array());
        foreach ($messages as $msg) {
        	if ($br) {
        		echo $this->Html->tag('br');
        	}
        	echo $msg;
        	$br = true;
        } 
      ?></td>
    </tr>
    <?php
      $i++;
    } 
    echo $this->Htmlx->warningTrOnError(array_key_exists($i, $errors)) ?>      
  </table>
  </td></tr></table>
  <?php 
    echo $this->Form->submit('Angebot speichern', array('name' => 'edit'));
    echo $this->Form->submit('Angebot speichern & Übersicht', array('name' => 'exit'));
    echo $this->Form->submit('Angebot speichern & Vorschau', array('name' => 'preview'));
    echo $this->Form->end();
  ?>

  <?php echo $this->Html->link('Änderungen verwerfen', '/offers/'); ?>
  