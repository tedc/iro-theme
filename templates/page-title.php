<?php
	use Roots\Sage\Titles; 
	$title = (get_field('header_alt_title')) ? get_field('header_alt_title') : Titles\title();
	var_dump(is_order_received_page());
?>
	<h1 class="header__title header__title--big"><?= $title; ?></h1>

	<?php while(have_rows('header_button')) : the_row();
		$button_class = 'header__button';
		$button_class .= get_sub_field('header_button_color') ? ' header__button--'.get_sub_field('header_button_color') : '';
		if(get_sub_field('header_button_link')):
		$button_link = get_sub_field('header_button_link')['url'];
		$button_text = get_sub_field('header_button_link')['title'];
		$ga_click = '';
		foreach (get_posts(array('post_type'=>'product', 'posts_per_page'=>-1)) as $p) {
			if(get_permalink($p->ID) == $button_link) {
				$ga_click = ' ga-click';
				break;
			}
		}
	?>
	<nav class="header__btns header__btns--grow-top">
		
		<a href="<?php echo $button_link; ?>" class="<?php echo $button_class; ?>" ui-sref="app.page({slug : '<?php echo basename($button_link); ?>'})"<?php echo $ga_click; ?>>
			<?php echo $button_text; ?>
		</a>
	</nav>
	<?php endif; endwhile; ?>
