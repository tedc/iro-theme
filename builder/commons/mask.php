<?php 
	$base = $masked_images[0]; 
	$mask = wp_get_attachment_image_src( $masked_images[1], 'full' );
	$w = intval($mask[1]);
	$h = intval($mask[2]);
	$id = generateRandomString(rand(10, 20));
	$r = (($w > $h) ? $h + 40 : $w - 2) / 2;
?>
<figure class="mask" ng-mask ng-mousemove="moveMask($event, '#mask_svg_<?php echo $id; ?>')" ng-mouseleave="moveMask($event, '#mask_svg_<?php echo $id; ?>', true)">
	<?php echo wp_get_attachment_image( $base, 'full', false, array('class' => 'mask__image') ); ?>
	<svg class="mask__svg" viewBox="0 0 <?php echo $w; ?> <?php echo $h; ?>" id="mask_svg_<?php echo $id; ?>">
		<defs>
			<clippath id="mask_<?php echo $id; ?>" mask="clip_<?php echo $id; ?>">
				<circle r="<?php echo $r + 50; ?>" cy="<?php echo $h/2; ?>" cx="<?php echo $w/2; ?>" class="mask__circle" id="circle_<?php echo $id; ?>" />
			</clippath>
		</defs>
		<g clip-path="url(#mask_<?php echo $id; ?>)">
			<image xlink:href="" x="0" y="0" width="<?php echo $w; ?>" height="<?php echo $h; ?>" ng-attr-xlink:href="<?php echo $mask[0]; ?>" mask="url(#clip_<?php echo $id; ?>)" />
		</g>
		<circle r="<?php echo $r + 50; ?>" cy="<?php echo $h/2; ?>" cx="<?php echo $w/2; ?>" class="mask__circle mask__circle--visible" fill="url(#gradient_<?php echo $id; ?>)" id="circle_<?php echo $id; ?>" />
	</svg>
</figure>