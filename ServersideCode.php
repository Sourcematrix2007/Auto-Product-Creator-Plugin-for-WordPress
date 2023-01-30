/* Here's an example of server-side code in PHP to fetch the product information from an external API and create the products based on the SKUs and source URL:
*/


<?php
/*
Plugin Name: Auto Product Creator
Description: Automatically creates products for sale in your online store
Version: 1.0
Author: Your Source Matrix
*/

function auto_product_creator() {
  // Define the API endpoint URL
  $api_url = 'https://api.example.com/products';

  // Fetch the product information from the API
  $response = wp_remote_get($api_url);
  $product_data = json_decode(wp_remote_retrieve_body($response), true);

  // Loop through the product data and create each product
  foreach ($product_data as $product) {
    $product_name = $product['name'];
    $product_description = $product['description'];
    $product_price = $product['price'];
    $product_image_url = $product['image_url'];
    $product_sku = $product['sku'];

    // Create the product
    $new_product = array(
      'post_title' => wp_strip_all_tags($product_name),
      'post_content' => $product_description,
      'post_status' => 'publish',
      'post_type' => 'product',
    );
    $product_id = wp_insert_post($new_product);

    // Set the product price
    update_post_meta($product_id, '_price', $product_price);
    update_post_meta($product_id, '_regular_price', $product_price);

    // Set the product SKU
    update_post_meta($product_id, '_sku', $product_sku);

    // Download the product image and set it as the featured image for the product
    $product_image = media_sideload_image($product_image_url, $product_id, $product_name);
    set_post_thumbnail($product_id, $product_image);
  }
}

// Run the auto product creator function when the plugin is activated
register_activation_hook(__FILE__, 'auto_product_creator');
