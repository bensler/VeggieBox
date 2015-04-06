<!-- File: /app/views/offers/index.ctp -->

<h1>Wochenangebote</h1>

<p align="right"><?php echo $this->Html->link('Neues Wochenangebot anlegen','/offers/edit') ?></p>

<p align="right"><?php echo $this->Html->link('Übersicht', '/') ?></p>

<table border="yes">
	<tr>
		<th>Name</th>
    <th>Bestellschlu&szlig;/Lieferdatum</th>
    <th>Status</th>
	</tr>
	
	<?php
    setlocale(LC_ALL, 'de_DE');
    foreach ($offersData as $anOffer):
      $anOffer = $anOffer['Offer'];
      $state = $anOffer['state'];
      $offerId = $anOffer['id'];
  ?>
  <?php 
    $tagParams = array();
    if ($offerId == $this->Session->read('Offer.last')) {
      $tagParams = array('class' => 'currentEntity');
    }
    echo $this->Html->tag('tr', null, $tagParams);
  ?>      
		<td><?php echo $anOffer['name'] ?></td>
		<td><?php echo $this->Offers->formatOfferDates($anOffer) ?></td>
    <td><?php echo $this->Offers->formatOfferState($anOffer) ?></td>
  <tr>
    <td colspan="4" align="right" valign="middle"><?php
      if ($state == 100) {
        echo $this->Html->link('Bearbeiten', "/offers/edit/".$offerId);
    ?>&nbsp;<?php
        echo $this->Html->link('Vorschau', "/orders/preview/".$offerId.'?navi=index');
    ?>&nbsp;<?php
        echo $this->Html->link('Löschen', "/offers/delete/".$offerId);
    ?>&nbsp;<?php
        echo $this->Html->link('Aktivieren', "/offers/activate/".$offerId);
      }
      if ($state == 200) {
        echo $this->Html->link('Bearbeiten', "/offers/edit/".$offerId, null, 'Wollen Sie dieses aktive (!) Angebot wirklich bearbeiten?');
      ?>&nbsp;<?php
        echo $this->Html->link('Benachrichtigungs-Email', "/offers/mail/".$offerId);
      ?>&nbsp;<?php
        echo $this->Html->link('Bestellungen', "/offers/orders/".$offerId);
      ?>&nbsp;<?php
        echo $this->Html->image('page_white_stack.png', array(
          'alt' => 'PDF',
          'url' => array('controller' => 'offers', 'action' => 'pdf', $offerId)
        ));
      }
      if ($state == 300) {
        echo $this->Html->link('Löschen', array('action' => 'delete', 'id' => $offerId), null, 'Wollen Sie dieses Angebot wirklich löschen?');
      ?>&nbsp;<?php
        echo $this->Html->link('Bestellungen', "/offers/orders/".$offerId);
      ?>&nbsp;<?php
        echo $this->Html->image('page_white_stack.png', array(
          'alt' => 'PDF',
          'url' => array('controller' => 'offers', 'action' => 'pdf', $offerId)
        ));
      }
    ?></td>
	</tr>
	<?php endforeach; ?>

</table>
