<?php 
$section__content = 'section__content';
if(!$mw && !$centered) {
	$section__content .= ($col%2==0) ? ' section__content--shrink-fw-left' : ' section__content--shrink-fw-right';
}
include(locate_template( 'builder/commons/review.php', false, true ));
?>
