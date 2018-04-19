<footer class="footer" id="footer">
	<?php if(!is_page_template( 'template-landing.php')) : ?>
	<?php get_template_part( 'templates/social'); ?>
	<div class="footer__container footer__container--grid footer__container--grow footer__container--gray footer__container--shrink-fw">
		<div class="footer__cell footer__cell--first">
			<a href="<?php echo home_url('/'); ?>" class="icon-logo" ui-sref="app.root({lang : '<?php echo ICL_LANGUAGE_CODE; ?>'})"></a>
		</div>
		<div class="footer__cell footer__cell--menus">
			<?php 
			$menus = array(array('footer_products_menu', __('Prodotti', 'iro')), array( 'footer_pages_menu', __('Iro', 'iro') ) );
			foreach($menus as $menu) : 
			if (has_nav_menu($menu[0])) : ?>
			<nav class="footer__nav">
				<h4 class="footer__subtitle"><?php echo $menu[1]; ?></h4>
				<?php bem_menu($menu[0], 'footer-menu'); ?>
			</nav>
			<?php endif; endforeach; ?>
		</div>
		<?php acf_set_language_to_default(); while(have_rows('footer_cols', 'options')) : the_row(); ?>
		<div class="footer__cell">
			<div class="footer__content">
			<h4 class="footer__subtitle"><?php echo (get_sub_field('col_kind') > 1) ? __('In vendita su', 'iro') : __('Pagamento sicuro con', 'iro'); ?></h4>
			<?php while(have_rows('col_item')) : the_row();
					$img = get_sub_field('col_imag'); ?>
			<figure class="footer__figure" style="max-width:<?php echo (($img['width']/2)/16); ?>em">
				<?php if(get_sub_field('col_link')) : ?>
				<a href="<?php the_sub_field('col_link'); ?>" target="_blank" >
				<?php endif;
				?>
				<img class="footer__image" src="<?php echo $img['url']; ?>" />
				<?php if(get_sub_field('col_link')) : ?>
				</a>
				<?php endif; ?>
			</figure>
			<?php endwhile; ?>
		</div>
		</div>
		<?php endwhile; acf_unset_language_to_default(); 
		get_template_part( 'templates/footer', 'rating' ); ?>
	</div>
<?php endif; ?>
	<div class="footer__container footer__container--grow footer__container--grid footer__container--dark footer__container--shrink-fw">
		<div class="footer__cell footer__cell--s4 footer__cell--first">
			<i class="icon-luna"></i><strong><?php _e('Sogni d\'oro', 'iro'); ?></strong>
		</div>
		<div class="footer__cell footer__cell--s4">
            <?php acf_set_language_to_default(); 
            the_field('info', 'options'); 
            acf_unset_language_to_default(); ?>
        </div>
        <div class="footer__cell footer__cell--s4 footer__cell--last">
        	<a href="http://www.bspkn.it" target="_blank" class="icon-credits"></a>
        </div>
	</div>
</footer>
<?php 
	$main_product = get_field('main_product', 'options');
	$materasso = get_posts(
		array(
			'post_type'=>'product',
			'tax_query'=> array(
				array(
					'taxonomy' => 'prodotto_associato',
					'field' => 'term_id',
					'terms' => array($main_product)
				)
			)
		)
	);
	if(!is_product() && !is_page_template( 'template-landing.php')) : 
?>
<span class="add-to-cart__wrapper add-to-cart__wrapper--grow-top add-to-cart__wrapper--fixed" ng-sm="{'triggerHook' : 'onLeave','triggerElement':'body', 'class':'add-to-cart__wrapper--fixed-inview', offset: 160}">
<a class="add-to-cart__button" href="<?php echo get_permalink($materasso[0]->ID); ?>"><?php _e('Acquista IRO', 'iro'); ?></a>
</span>
<?php endif; ?>
<cookie-law-banner accept-button="true" accept-text="<?php _e('Chiudi', 'iro'); ?>" ng-class="{accepted: isClAccepted}" policy-button="true" policy-text="Cookie Policy" message="<?php _e('Il sito di IRO utilizza cookie: proseguendo nella navigazione, acconsenti al loro uso secondo le finalità illustrate dalle nostre policy.', 'iro'); ?>" policy-url="<?php the_field('cookie_policy', 'iro'); ?>"></cookie-law-banner>