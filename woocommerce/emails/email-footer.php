<?php
/**
 * Email Footer
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/email-footer.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates/Emails
 * @version     2.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
															</div>
														</td>
													</tr>
													<?php if(get_field('downloads', 'options')) : ?>
													<tr>
														<td style='color: #797a7c; font-family: "Helvetica Neue", Helvetica, Roboto, Arial, sans-serif; font-size: 14px; line-height: 150%; text-align: center;'><h2 style='color: #123f6d; display: block; font-family: "Helvetica Neue", Helvetica, Roboto, Arial, sans-serif; font-size: 18px; font-weight: bold; line-height: 130%; margin: 0 0 18px; text-align: center;'><?php _e('Download', 'iro'); ?></h2>
														<p><a style='color: #123f6d;font-weight:bold;text-decoration:none;' href="<?php echo get_field('downloads', 'options')['carta_di_identita']; ?>"><?php _e('La carta di identità di IRO', 'iro'); ?></a> | <a style='color: #123f6d;font-weight:bold;text-decoration:none;' href="<?php echo get_field('downloads', 'options')['dichiarazione_di_conformita']; ?>"><?php _e('Dichiarazione di conformità di IRO', 'iro'); ?></a></p>
													</tr>
												<?php endif; ?>
												</table>
												<!-- End Content -->
											</td>
										</tr>
									</table>
									<!-- End Body -->
								</td>
							</tr>
							<tr>
								<td align="center" valign="top">
									<!-- Footer -->
									<table border="0" cellpadding="10" cellspacing="0" width="600" id="template_footer">
										<tr>
											<td valign="top">
												<table border="0" cellpadding="10" cellspacing="0" width="100%">
													<tr>
														<td colspan="2" valign="middle" id="credit">
															<?php echo wpautop( wp_kses_post( wptexturize( apply_filters( 'woocommerce_email_footer_text', get_option( 'woocommerce_email_footer_text' ) ) ) ) ); ?>
														</td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
									<!-- End Footer -->
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</div>
	</body>
</html>
