<?php
/**
 * Plugin Name: Dokan Product Validation
 * Description: Product Validation for add product cover image, price and description.
 * Plugin URI: https://wordpress.org/plugins/dokan-product-validation
 * Author: Riyadh Ahmed
 * Author URI: http://sajuahmed.epizy.com/
 * Version: 1.1
 * License: GPL2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * @package Riyadh_Ahmed
 */

defined( 'ABSPATH' ) || exit;
/**
 * Validation for add product cover image, price and description
 *
 * @param array $errors
 * @return array $errors 
 */
function dokan_can_add_product_validation_customized( $errors ) {

  if (! isset( $_POST['dokan_add_new_product_nonce'] ) && ! isset( $_POST['dokan_edit_product_nonce'] )  ) {
    return $errors;
}

if ( isset( $_POST['dokan_edit_product_nonce'] ) && ! wp_verify_nonce( sanitize_key( $_POST['dokan_edit_product_nonce'] ),
 'dokan_edit_product' ) ) {
  return $errors;
}

if ( isset( $_POST['dokan_add_new_product_nonce'] ) && ! wp_verify_nonce( sanitize_key( $_POST['dokan_add_new_product_nonce'] ),
 'dokan_add_new_product' ) ) {
  return $errors;
}
  // nonce check here
  $_regular_price = wc_format_decimal( wp_unslash( $_POST['_regular_price'] ), false );
  $post_excerpt   = wp_kses_post( wp_unslash( $_POST['post_excerpt'] ) );
  $featured_image = isset( $_POST['feat_image_id'] ) ? absint( wp_unslash( $_POST['feat_image_id'] ) ) : 0;

  if ( empty( $featured_image ) ) {
      $errors[] = __( 'Please upload at least one product image.', 'dokan-lite' );
  }
  if ( empty( $post_excerpt ) ) {
    $errors[] = __( 'Please add a short description.', 'dokan-lite' );
}
if ( empty( $_regular_price ) ) {
  $errors[] = __( 'Please insert the product price.', 'dokan-lite' );
}

  return $errors;
}

add_filter( 'dokan_can_add_product', 'dokan_can_add_product_validation_customized', 35, 1 );
add_filter( 'dokan_can_edit_product', 'dokan_can_add_product_validation_customized', 35, 1 );

function dokan_new_product_popup_validation_customized( $errors, $data ) {

  if ( isset( $data['feat_image_id'] ) && ! $data['feat_image_id'] ) {
    return new WP_Error( 'no-image', __( 'Please upload at least one product image.', 'dokan-lite' ) );
  }
  if ( isset( $data['_regular_price'] ) && ! $data['_regular_price'] ) {
    return new WP_Error( 'no-price', __( 'Please insert the product price.', 'dokan-lite' ) );
  }
  if ( isset( $data['post_excerpt'] ) && ! $data['post_excerpt'] ) {
    return new WP_Error( 'no-desc', __( 'Please add a short description.', 'dokan-lite' ) );
  }
}

add_filter( 'dokan_new_product_popup_args', 'dokan_new_product_popup_validation_customized', 35, 2 );