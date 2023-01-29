php
Copy code
<?php
/*
Plugin Name: Auto Product Creator
Description: Automatically creates products for sale in your online store
Version: 1.0
Author: Your Name
*/

function auto_product_creator() {
  // Get the necessary information for the product
  $product_name = 'Example Product';
  $product_description = 'This is an example product created by the Auto Product Creator plugin';
  $product_price = 9.99;
  $product_sku = 'https://www.grundfos.com/za';
  $product_image_url = 'https://product-selection.grundfos.com/za/products/hydro-mpc/hydro-mpc-e/hydro-mpc-e-3-cre-32-2-99166948?tab=variant-curves&pumpsystemid=2021860251';

  // Create the product
  $product = array(
    'post_title' => wp_strip_all_tags($product_name),
    'post_content' => $product_description,
    'post_status' => 'publish',
    'post_type' => 'product',
  );
  $product_id = wp_insert_post($product);

  // Set the product price and SKU
  update_post_meta($product_id, '_price', $product_price);
  update_post_meta($product_id, '_regular_price', $product_price);
  update_post_meta($product_id, '_sku', $product_sku);

  // Download the product image and set it as the featured image for the product
  $product_image = media_sideload_image($product_image_url, $product_id, $product_name);
  set_post_thumbnail($product_id, $product_image);
}

// Run the auto product creator function when the plugin is activated
register_activation_hook(_FILE_, 'auto_product_creator');