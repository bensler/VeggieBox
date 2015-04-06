<!-- File: /app/views/offers/view.ctp -->
  <?php  
    setlocale(LC_ALL, 'de_DE');
    
    $offerLines = $offerData['OfferLine'];
    $units = $offerData['Unit'];
    $vegetables = $offerData['Vegetable'];
    $offerData = $offerData['Offer'];
  ?>
  <h1>Wochenangebot <?php echo $offerData['name']; ?></h1><p />
  <?php echo $offerData['introduction']; ?><p />
  <strong>Einsendeschlu&szlig;</strong>: <?php echo $this->Offers->formatOfferEndDate($offerData);?><p />
  <strong>Status</strong>: <?php echo $this->Offers->formatOfferState($offerData);?><p />
  <table border="yes">
    <tr>
      <th align="left">Preis</th>
      <th align="left">Gem&uuml;sesorte</th>
    </tr>
    <tr>
      <th colspan=2 align="right">Kommentar</th>
    </tr>
  
    <?php foreach ($offerLines as $offerLine): ?>
    <tr>
      <td><?php
        echo $offerLine['price'].' '.$this->Offers->getUnitString($offerLine, $vegetables, $units) 
      ?></td>
      <td><?php echo $vegetables[$offerLine['vegetable_id']]['name']; ?></td>
      <?php 
        $comment = $offerLine['comment'];
        if ($comment != null) {
          echo $this->Html->tableCells(array(
            array(array($comment, array('colspan' => 2, 'align' => 'right')))
          ));
        }
      ?>
    </tr>
    <?php endforeach; ?>
  </table>
  