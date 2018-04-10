<?php acf_set_language_to_default();
			$main_product = get_field('main_product', 'options');
			acf_unset_language_to_default();
			$materasso = get_posts(array('post_type' => 'product', 'tax_query'=> array(array('taxonomy'=> 'prodotto_associato', 'field'=> 'term_id', 'terms' => array($main_product)))));
			$url = get_permalink($materasso[0]->ID); ?>
<div class="header header--page header--shrink-fw">
	<div class="header__container header__container--grow-md">
			<img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/superhero-404.jpg" class="header__image header__image--error" />
 			<h1 class="header__title header__title--medium header__title--aligncenter"><?php _e('Il tuo carrello è vuoto', 'iro'); ?></h1>
	</div>
</div>
<div class="alert alert--grow-lg-bottom alert--shrink alert--mw-large alert--aligncenter">
  <p><?php _e('Spiacenti, la tua sessione di acquisto sembra essere scaduta.', 'iro'); ?></p>
  <a href="<?php echo $url; ?>" class="alert__button" style="margin-top:<?php echo (40/16); ?>em"><?php _e('Acquista IRO', 'iro'); ?></a>
</div>