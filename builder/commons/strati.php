<?php $mw = ($strati=='section') ? ' '.$strati.'__render--mw-large' : ''; ?>
<figure class="<?php echo $strati; ?>__render<?php $mw; ?>">
	<?php 
		$max = ($strati == 'layers') ? 5 : 7;
		for($i= 0; $i<=$max; $i++) {
			$data_layer = ($i != 5 && $i != 7) ? $i : 5;
			echo '<img src="'.get_stylesheet_directory_uri() .'/assets/images/strati/'.$folder_base.$i.'.png" class="'.$strati.'__layer" data-layer="'.$data_layer.'" />';
		}
	?>
</figure>