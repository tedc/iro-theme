<?php 
    acf_set_language_to_default();
    $phone = get_field('phone', 'options');
    $phone_unformatted = preg_replace('/[^0-9,.]/','',str_replace('+', '00', $phone)); 
    acf_unset_language_to_default();
?>
<header class="banner banner--grid banner--shrink-fw" ng-class="{'banner--active-menu':isMenu}">
    <nav class="banner__nav banner__nav--grid">
      <a class="icon-logo" href="<?= esc_url(home_url('/')); ?>" ui-sref="app.root({lang : '<?php echo ICL_LANGUAGE_CODE; ?>'})"></a>
      <div class="banner__menu">
      <?php
        if (has_nav_menu('primary_navigation')) :
          $append_sting = (is_user_logged_in()) ? __('Profilo', 'iro') : __('Login', 'iro');
          $append_url = (is_user_logged_in()) ? wc_get_page_permalink('myaccount') : '/tab/login/';
          $append = '<li class="menu__item menu__item--account" ui-sref-active="menu__item--active"><a href="'.$append_url.'" ng-click="account($event)" class="menu__link" ng-class="{\'menu__link--active\':menuItem ==\''.basename(wc_get_page_permalink('myaccount')).'\'}" ng-bind-html="(isUserLoggedIn) ? \''.__('Profilo', 'iro').'\' : \''.__('Login', 'iro').'\'">'.$append_sting.'</a></li>';
         bem_menu('primary_navigation', 'menu', 'menu--shrink', null, $append);
          
        endif;
        ?>
        <a href="tel:<?php echo $phone_unformatted; ?>" class="banner__btn banner__btn--phone"><i class="icon-phone"></i><span><?php _e('Assistenza', 'iro'); ?> <?php echo $phone; ?></span></a>
      </div>
    </nav>
    <span class="banner__toggle" ng-click="isMenu=!isMenu">
        <span class="banner__line banner__line--top"></span>
        <span class="banner__line banner__line--center"></span>
        <span class="banner__line banner__line--bottom"></span>
    </span>
    <nav class="banner__tools">
      <a href="tel:<?php echo $phone_unformatted; ?>" class="banner__btn banner__btn--phone"><i class="icon-phone"></i><span><?php _e('Assistenza', 'iro'); ?><br><?php echo $phone; ?></span></a>
      <a class="banner__btn banner__btn--account" href="<?php echo (is_user_logged_in()) ? wc_get_page_permalink('myaccount') : '/tab/login/'; ?>" ng-click="account($event)" ng-bind-html="(isUserLoggedIn) ? '<?php _e('Profilo', 'iro'); ?>' : '<?php _e('Login', 'iro'); ?>'"><?php echo (is_user_logged_in()) ? __('Profilo', 'iro') : __('Login', 'iro'); ?></a>
      <ngcart-summary template-url="cart-summary.html"></ngcart-summary>
    </nav>
</header>