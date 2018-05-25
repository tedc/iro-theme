<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
	<tr class="fee">
		<th><?php echo esc_html( $fee->name ); ?></th>
		<td><?php wc_cart_totals_fee_html( $fee ); ?></td>
	</tr>
<?php endforeach; ?>

<?php if ( wc_tax_enabled() && 'excl' === WC()->cart->tax_display_cart ) : ?>
	<?php if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) : ?>
		<?php foreach ( WC()->cart->get_tax_totals() as $code => $tax ) : ?>
			<tr class="tax-rate tax-rate-<?php echo sanitize_title( $code ); ?>">
				<th><?php echo esc_html( $tax->label ); ?></th>
				<td><?php echo wp_kses_post( $tax->formatted_amount ); ?></td>
			</tr>
		<?php endforeach; ?>
	<?php else : ?>
		<tr class="tax-total">
			<th><?php echo esc_html( WC()->countries->tax_or_vat() ); ?></th>
			<td><?php wc_cart_totals_taxes_total_html(); ?></td>
		</tr>
	<?php endif; ?>
<?php endif; ?>