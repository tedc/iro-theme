<div class="facebook facebook--shrink facebook--grid">
	<div class="facebook__cover" ng-style="{backgroundImage : 'url(<?php acf_set_language_to_default();the_field('facebook_cover', 'option');acf_unset_language_to_default(); ?>)'}"></div>
	<div class="facebook__cell facebook__cell--stream facebook__cell--s7">
		<header class="facebook__header facebook__header">
			<img src="http://graph.facebook.com/<?php echo get_option('cff_page_id'); ?>/picture?type=large" alt="<?php bloginfo('name'); ?>">
			<h2 class="facebook__title facebook__title--small facebook__title--lighter"><?php bloginfo('name'); ?></h2>
		</header><!-- /header -->
		<scrollbar continuous-scrolling="true">
		<?php echo do_shortcode('[custom-facebook-feed]'); ?>
		</scrollbar>
	</div>
	<div class="facebook__cell facebook__cell--grow facebook__cell--shrink facebook__cell--s5 facebook__cell--align-end">
	<p><a href="<?php get_social_link('Facebook'); ?>" target="_blank" class="facebook__send"><?php _e('Seguici su Facebook', 'catellani'); ?></a></p>
	</div>
</div>