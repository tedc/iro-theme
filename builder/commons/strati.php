<figure class="<?php echo $strati; ?>__render">
	<?php 
		for($i= 0; $i<=7; $i++) {
			$data_layer = ($i != 5 && $i != 7) ? $i : 5;
			echo '<img src="'.get_stylesheet_directory_uri() .'/assets/images/strati/'.$folder_base.$i.'.png" class="'.$strati.'__layer" data-layer="'.$data_layer.'" />';
		}
	?>
</figure>