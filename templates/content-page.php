<?php if(!is_checkout() && !is_account_page()) : ?>
<div class="page__content page__content--grow-md-bottom page__content--shrink page__content--mw-large">
	<?php the_content(); ?>
</div>
<?php else : the_content(); endif; ?>