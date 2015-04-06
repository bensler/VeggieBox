<!-- File: /app/views/vegetables/statistics.ctp -->

<h1>Preisstatistik</h1>
<h3><?php echo empty($vegetable) ? 'keine Daten' : $vegetable; ?></h3> 
<table>
<?php foreach ($statistics as $name => $price) { ?>
	<tr>
	  <td><?php echo $name; ?></td>
	  <td><?php echo $price; ?></td>
	</tr>	
<?php } ?>
</table>
