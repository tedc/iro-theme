<?php 
	$user = wp_get_current_user();
	$product_id = get_query_var( 'productId');
	if(!empty($product_id)) {
		$current_review = get_posts(
			array(
				'post_type' => 'recensioni',
				'tax_query' => array(
					array(
						'taxonomy' => 'prodotto_associato',
						'field' => 'term_id',
						'terms' => $product_id
					)
				),
				'author' => $user->ID
			)
		);
	}
?>
<div class="review review--grow-lg review--shrink review--mw-large" review>
	<form class="review__form" name="reviewForm" ng-submit="sendReview(reviewForm.$valid, '<?php echo wp_create_nonce('wp_rest'); ?>', <?php echo $user->ID; ?>)" ng-if="!isReview" ng-class="{'review__form--loading' : isPosting}">
			<?php 
			get_template_part( 'templates/page', 'title'); 
			$name = (get_field('review_name', 'user_'.$user->ID)) ? get_field('review_name', 'user_'.$user->ID) : $user->user_firstname.' '.$user->user_lastname;
			$email = (get_field('review_email', 'user_'.$user->ID)) ? get_field('review_email', 'user_'.$user->ID) : $user->user_email;
			$city = (get_field('review_city', 'user_'.$user->ID)) ? get_field('review_city', 'user_'.$user->ID) : $user->billing_city;
			$age = get_field('review_age', 'user_'.$user->ID);
		?>
		<span class="review__required"><?php _e('Tutti i campi sono obbligatori', 'iro'); ?></span>
		<div class="review__row review__row--grow review__row--grid">
			<div class="review__cell review__cell--grow review__cell--s6 review__cell--shrink-right-only">
				<label class="review__label"><?php _e('Nome', 'iro'); ?></label>
				<input name="name" ng-attr-placeholder="{{(reviewForm.name.$error.required && reviewForm.name.$touched) ? '<?php _e('Campo obbligatorio', 'iro'); ?>' : ''}}" class="review__input" ng-model="userFields.fields.review_name" type="text" required ng-init="userFields.fields.review_name='<?php echo $name; ?>'" />
			</div>
			<div class="review__cell review__cell--grow review__cell--s6 review__cell--shrink-left-only">
				<label class="review__label"><?php _e('Età', 'iro'); ?></label>
				<input name="age" ng-attr-placeholder="{{(reviewForm.age.$error.required && reviewForm.age.$touched) ? '<?php _e('Campo obbligatorio', 'iro'); ?>' : ''}}" class="review__input" ng-model="userFields.fields.review_age" type="text" ng-init="userFields.fields.review_age='<?php echo $age; ?>'" required />
			</div>
			<div class="review__cell review__cell--grow review__cell--s6 review__cell--shrink-right-only">
				<label class="review__label"><?php _e('Email', 'iro'); ?></label>
				<input name="email" ng-attr-placeholder="{{(reviewForm.email.$error.required && reviewForm.email.$touched) ? '<?php _e('Campo obbligatorio', 'iro'); ?>' : ''}}" class="review__input" ng-model="userFields.fields.review_email" type="email" required  ng-init="userFields.fields.review_email='<?php echo $email; ?>'" />
			</div>
			<div class="review__cell review__cell--grow review__cell--s6 review__cell--shrink-left-only">
				<label class="review__label"><?php _e('Città', 'iro'); ?></label>
				<input name="city" ng-attr-placeholder="{{(reviewForm.city.$error.required && reviewForm.city.$touched) ? '<?php _e('Campo obbligatorio', 'iro'); ?>' : ''}}" class="review__input" ng-model="userFields.fields.review_city" ng-init="userFields.fields.review_city='<?php echo $city; ?>'" type="text" required/>
			</div>
		</div>
		<div class="review__row review__row--grow review__row--grid">
			<div class="review__item review__item--grow"><?php _e('Recensione per', 'iro'); ?></div>
			<?php 
				$cats = get_terms(array('taxonomy'=>'prodotto_associato', 'hide_empty'=>0, 'orderby' => 'term_order'));
				foreach ($cats as $cat) { 
					$init = (!empty($product_id) && $product_id == $cat->term_id) ? ' ng-checked="true" ng-init="reviewFields.prodotto_associato='.$cat->term_id.'"' : '';
				?>
				<div class="review__item review__item--grow">
					<input id="cat_<?php echo $cat->term_id; ?>" type="radio" class="review__choice" ng-model="reviewFields.prodotto_associato" ng-value="'<?php echo $cat->term_id; ?>'" required<?php echo $init; ?> name="prodotto_associato" />
					<label for="cat_<?php echo $cat->term_id; ?>">
						<?php 
							$icon_kind = get_field('icon_kind', 'product_plus_'.$cat->term_id);
							$icon = get_field('icon_'.$icon_kind, 'product_plus_'.$cat->term_id);
						 	if($icon_kind == 'svg') :
						 		echo print_svg($icon); 
						 	else : 
						 ?>
						<i class="icon-<?php echo $icon; ?>"></i>
						<?php endif; ?>
						<span class="review__name review__name--grow-top"><?php echo $cat->name; ?>	
					</label>
				</div>
			<?php }
			?>
		</div>

		<div class="review__row review__row--grow review__row--grid">
			<div class="review__cell review__cell--grow review__cell--s12"><?php _e('Voto', 'iro'); ?></div>
			<?php 
				$values = get_terms(
					array(
						'taxonomy'=>'rating',
						'hide_empty'=>0,
						'orderby' => 'meta_value_num',
						'order' => 'DESC',
						'meta_query' => [[
							'key' => 'rating',
							'type' => 'numeric'
						]]
					));
				foreach ($values as $value) { 
					$init_rating = '';
					if($current_review) {
						$rating = wp_get_post_terms($current_review->ID, 'rating')[0];
						$init_rating = ($rating && $value->term_id == $rating->term_id) ? ' ng-checked="true" ng-init="reviewFields.rating='.$value->term_id.'"' : '';
					}
				?>
				<div class="review__item">
					<input id="rate_<?php echo $value->term_id; ?>" type="radio" class="review__choice" ng-model="reviewFields.rating" ng-value="'<?php echo $value->term_id; ?>'" required name="rating"<?php echo $init_rating; ?> />
					<label for="rate_<?php echo $value->term_id ?>">
						<?php 
							for($i= 0; $i < intval(get_field('rating', 'rating_'.$value->term_id)); $i++) {
						 ?>
						<i class="icon-stella"></i>
						<?php } ?>
						<span class="review__name review__name--grow-top"><?php echo $value->name; ?></span>
					</label>
				</div>
			<?php }
			?>
		</div>
		<div class="review__row review__row--grow">
			<?php if(isset($current_review) && $current_review) : ?><input type="hidden" ng-model="reviewFields.id" ng-init="reviewFields.id=<?php echo $current_review[0]->ID; ?>" /><?php endif; ?>
			<div class="review__cell review__cell--grow">
				<label class="review__label"><?php _e('Giudizio', 'iro'); ?></label>
				<input name="title" ng-attr-placeholder="{{(reviewForm.title.$error.required && reviewForm.title.$touched) ? '<?php _e('Campo obbligatorio', 'iro'); ?>' : '<?php _e('Impressioni generali sul prodotto', 'iro'); ?>'}}" class="review__input" ng-model="reviewFields.title" type="text" required<?php if(isset($current_review) && $current_review) : ?> ng-init="reviewFields.title='<?php echo get_the_title($current_review[0]->ID); ?>'"<?php endif; ?> />
			</div>
			<div class="review__cell review__cell--grow">
				<label class="review__label"><?php _e('Recensione', 'iro'); ?></label>
				<textarea name="content" ng-attr-placeholder="{{(reviewForm.content.$error.required && reviewForm.content.$touched) ? '<?php _e('Campo obbligatorio', 'iro'); ?>' : '<?php _e('Raccontaci come dormi su Iro', 'iro'); ?>'}}" class="review__textarea" ng-model="reviewFields.content" type="text" required<?php if(isset($current_review) && $current_review) : ?> ng-init="reviewFields.content='<?php echo get_the_title($current_review[0]->ID); ?>'"<?php endif; ?>></textarea>
			</div>
		</div>
		<footer class="review__footer review__footer--grid-nowrap">
			<span class="review__privacy"><?php _e('Inviando il modulo acconsento al trattamento dei dati personali
ai sensi dell’articolo 123/45', 'iro'); ?></span>
			<button type="submit" class="review__button review__button--dark" ng-disabled="reviewForm.$invalid" ng-class="{'review__button--loading' : isPosting}">
				<?php _e('Invia', 'iro'); ?>
			</button>
		</footer>
	</form>
	<div class="review__message review__message--aligncenter" ng-if="isReview && !isError">
		<h2 class="review__title review__title--big">
			<?php the_field('success_title'); ?>
		</h2>
		<div class="review__content review__content--grow-md">
			<?php the_field('success_message'); ?>
		</div>
		<a href="<?php echo home_url('/'); ?>" class="review__button review__button--dark">
			<?php _e('Vai alla home', 'iro'); ?>
		</a>
	</div>
	<div class="review__message review__message--aligncenter" ng-if="isReview && isError">
		<h2 class="reivew__title review__title--big-error">
			<?php the_field('error_title'); ?>
		</h2>
		<div class="review__content review__content--grow-md" ng-if="isError">
			<?php the_field('error_message'); ?>
		</div>
		<span ng-click="isReview=false;isError=false" class="review__button review__button--dark">
			<?php _e('Riprova', 'iro'); ?>
		</span>
	</div>
</div>