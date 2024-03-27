add_filter( 'woocommerce_order_button_text', 'atom360de_change_checkout_button_text' );
function atom360de_change_checkout_button_text( $button_text ) {
   return 'Subscribe now'; // Replace this text in quotes with your respective custom button text
}
