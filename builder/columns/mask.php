<?php 
	$masked_images = get_sub_field('masked_image', false, false);
	$r = get_sub_field('radius');
	include(locate_template('builder/commons/mask.php', false, false));