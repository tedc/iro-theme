<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Class WESO_Checkbox_Field.
 *
 * Text field class.
 *
 * @class		WESO_Checkbox_Field
 * @version		1.0.0
 * @author		Jeroen Sormani
 */
class WESO_Iro_Checkbox_Field extends WESO_Abstract_Field {

	use WESO_Field_Has_Choices;

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 *
	 * @param WESO_Shipping_Option $shipping_option
	 * @param array $shipping_option_args List of arguments of the shipping option as saved in the meta.
	 */
	public function __construct( $shipping_option, $shipping_option_args ) {

		parent::__construct( $shipping_option );

		$this->shipping_option_args = $shipping_option_args;
		$this->type                 = 'checkbox';

	}


	/**
	 * HTML field output.
	 *
	 * The field output on the front end.
	 *
	 * @param  $package_index
	 */
	public function output( $package_index ) {
		$choices   = $this->get_choices();
		$option_id = $this->shipping_option_args['option_id'];
		$values    = $this->shipping_option->get_value();
		// Set default value
		if ( is_null( $values ) ) :
			$values = array_flip( preg_split( '/\s*\n\s*/', $this->shipping_option_args['extra_options']['default_value'] ) );
		endif;

		?><div class='extra-shipping-option'>

			<span class='weso-shipping-option-text-label'><?php
				echo wp_kses_post( $this->shipping_option_args['option_name'] );
			?></span>
			<input type="hidden" name='extra_shipping_option[<?php echo sanitize_key( $package_index ); ?>][<?php echo absint( $this->shipping_option->post_id ); ?>][<?php echo esc_attr( $option_id ); ?>][]'>

			<ul><?php
				$count = 0;
				foreach ( $choices as $k => $choice ) :

					?><li>
						<input
								type='checkbox'
								class='shipping__checkbox'
								<?php checked( array_key_exists( $choice['key'], $values ) || array_key_exists( $choice['name'], $values ) ); ?>
								name='extra_shipping_option[<?php echo sanitize_key( $package_index ); ?>][<?php echo absint( $this->shipping_option->post_id ); ?>][<?php echo esc_attr( $option_id ); ?>][<?php echo $choice['key']; ?>]'
								ng-model='checkoutFields.post_data.extra_shipping_option[<?php echo sanitize_key( $package_index ); ?>][<?php echo absint( $this->shipping_option->post_id ); ?>]["<?php echo esc_attr( $option_id ); ?>"]["<?php echo $choice['key']; ?>"]'
								value='yes'
								id='extra_shipping_<?php echo $count; ?>'
							>
							<label for="extra_shipping_<?php echo $count; ?>"><?php
							echo wp_kses_post( $choice['name'] ).'</label>';
							$choice_cost = $this->get_choice_total_cost( $choice );
							if ( $choice_cost > 0 ) :

								$cost = wc_price( $choice_cost );

								if ( $this->shipping_option->taxable() ) {
									$eso_tax = $this->get_choice_tax( $choice );
									if ( WC()->cart->tax_display_cart == 'excl' ) {
										if ( $eso_tax > 0 && WC()->cart->prices_include_tax ) {
											$cost .= ' <small class="tax_label">' . WC()->countries->ex_tax_or_vat() . '</small>';
										}
									} else {
										$cost = wc_price( $choice_cost + $eso_tax );
										if ( $eso_tax > 0 && ! WC()->cart->prices_include_tax ) {
											$cost .= ' <small class="tax_label">' . WC()->countries->inc_tax_or_vat() . '</small>';
										}
									}
								}

								?>
								
								<span class='shipping__price'><?php echo $cost; ?></span><?php
							endif;
						?>
					</li><?php
					$count++;
				endforeach;
			?></ul>

		</div><?php

	}
}
