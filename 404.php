<?php get_template_part('templates/page', 'header'); ?>

<div class="alert alert--grow-lg-bottom alert--shrink alert--mw-large alert--aligncenter">
  <p><?php _e('Spiacenti, la pagina che cerchi non esiste.', 'iro'); ?></p>
  <a href="<?php echo home_url('/'); ?>" ui-sref="app.root()" class="alert__button" style="margin-top:<?php echo (40/16); ?>em"><?php _e('Vai alla home', 'iro'); ?></a>
</div>

