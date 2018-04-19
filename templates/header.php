<?php 
    do_action( 'iro_header' );
    acf_set_language_to_default();
    $phone = get_field('phone', 'options');
    $phone_unformatted = preg_replace('/[^0-9,.]/','',str_replace('+', '00', $phone)); 
    acf_unset_language_to_default();

    get_template_part('templates/promo'); ?>  
<header class="banner banner--shrink-fw" ng-class="{'banner--active-menu':isMenu}">
    <nav class="banner__nav">
      <!-- <a class="icon-logo" href="<?= esc_url(home_url('/')); ?>" ui-sref="app.root({lang : '<?php echo ICL_LANGUAGE_CODE; ?>'})"></a> -->
      <a class="icon-logo" href="<?= esc_url(home_url('/')); ?>"></a>
      <?php if(!is_page_template( 'template-landing.php')) : ?>
      <div class="banner__menu" scroller options="{freeMode: true, slidesPerView: 'auto', breakpoints:{1024:{mousewheel: true, direction:'vertical', 'scrollbar':{'el':'.swiper-scrollbar', 'draggable':true} } }  }">
      <?php
        if (has_nav_menu('primary_navigation')) :
          $append_sting = (is_user_logged_in()) ? __('Profilo', 'iro') : __('Login', 'iro');
          $append_url = (is_user_logged_in()) ? wc_get_page_permalink('myaccount') : '/tab/login/';
          //$append = '<li class="menu__item swiper-slide menu__item--account" ui-sref-active="menu__item--active"><a href="#register"class="menu__link" ng-class="{\'menu__link--active\':menuItem ==\''.basename(wc_get_page_permalink('myaccount')).'\'}" ng-bind-html="(isUserLoggedIn) ? \''.__('Profilo', 'iro').'\' : \''.__('Registrati', 'iro').'\'">'.$append_sting.'</a></li>';
          $append = '<li class="menu__item swiper-slide menu__item--account"><a href="#register" class="menu__link" ng-bind-html="(isUserLoggedIn) ? \''.__('Profilo', 'iro').'\' : \''.__('Registrati', 'iro').'\'">'.$append_sting.'</a></li>';
         bem_menu('primary_navigation', 'menu', 'menu--shrink swiper-wrapper', null, $append);
          
        endif;
        if($phone) :
        ?>
        <a href="tel:<?php echo $phone_unformatted; ?>" class="banner__btn banner__btn--phone"><i class="icon-phone"></i><span><?php _e('Assistenza', 'iro'); ?> <?php echo $phone; ?></span></a><?php endif; ?>

        <span class="banner__swiper swiper-scrollbar"></span>
      </div>
    </nav>
    <span class="banner__toggle" ng-click="isMenu=!isMenu">
        <span class="banner__line banner__line--top"></span>
        <span class="banner__line banner__line--center"></span>
        <span class="banner__line banner__line--bottom"></span>
    </span>
    <nav class="banner__tools">
      <?php 
        if($phone) : ?>

      <a href="tel:<?php echo $phone_unformatted; ?>" class="banner__btn banner__btn--phone"><i class="icon-phone"></i><span><?php _e('Assistenza', 'iro'); ?><br><?php echo $phone; ?></span></a><?php endif; ?>
     <!--  <a class="banner__btn banner__btn--account" href="<?php echo (is_user_logged_in()) ? wc_get_page_permalink('myaccount') : '#register'; ?>" ng-click="account($event)" ng-bind-html="(isUserLoggedIn) ? '<?php _e('Profilo', 'iro'); ?>' : '<?php _e('Registrati', 'iro'); ?>'"><?php echo (is_user_logged_in()) ? __('Profilo', 'iro') : __('Registrati', 'iro'); ?></a> -->
     <a class="banner__btn banner__btn--account" href="<?php echo (is_user_logged_in()) ? wc_get_page_permalink('myaccount') : '#register'; ?>"><?php echo (is_user_logged_in()) ? __('Profilo', 'iro') : __('Registrati', 'iro'); ?></a>
      <ngcart-summary template-url="cart-summary.html"></ngcart-summary>
    <?php endif; ?>
    </nav>
</header>