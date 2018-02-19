<?php while (have_posts()) : the_post(); ?>
<article <?php post_class('post--shrink-fw'); ?>>
	<?php the_post_thumbnail('full', array('class' => 'post__thumbnail')); ?>
	<div class="post__content post__content--grow-lg post__content--mw-large">
		<div class="post__meta post__meta--grid">
			<?php get_template_part('templates/entry', 'meta'); ?>
		</div>
		<h1 class="post__title post__title--big"><?php the_title(); ?></h1>
		<?php 
		$szPostContent = $post->post_content;
// Define the pattern to search
		$szSearchPattern = '/(<img.*?src\s*=.*?>)/';
		// Run preg_match_all to grab all the images and save the results in $aPics
		// This time we replace/remove the images from the content
		$szDescription = preg_replace( $szSearchPattern, '</div><figure class="post__image">$1</figure><div class="post__content post__content--grow-lg post__content--mw-large">' , $szPostContent);
		// Apply filters for correct content display
		$szDescription = apply_filters('the_content', $szDescription);
		// Echo the Content
		echo $szDescription; 
		?>
	</div>
</article>
<?php endwhile; 
	get_template_part( 'templates/post', 'related' );
?>
