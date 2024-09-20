<?php
/**
 * Plugin Name: Angry Creative Product Block
 * Description: A Gutenberg block to display WooCommerce products with featured image, title and price.
 * Version: 1.0.0
 * Author: Mark Bozó
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

function ac_product_block_register_block() {
  wp_register_script(
      'ac-product-block-editor-script',
      plugins_url( 'build/index.js', __FILE__ ),
      array( 'wp-blocks', 'wp-element', 'wp-editor', 'wp-components' )
  );

  wp_register_style(
      'ac-product-block-editor-style',
      plugins_url( 'build/index.css', __FILE__ ),
      array( 'wp-edit-blocks' )
  );

  wp_register_style(
      'ac-product-block-style',
      plugins_url( 'build/style-index.css', __FILE__ ),
      array()
  );

  register_block_type( 'ac-plugin/ac-product-block', array(
      'editor_script' => 'ac-product-block-editor-script',
      'editor_style'  => 'ac-product-block-editor-style',
      'style'         => 'ac-product-block-style',
      'render_callback' => 'ac_product_block_render_callback',
      'attributes'      => array(
          'heading' => array(
              'type' => 'string',
              'default' => 'Featured Products',
          ),
          'productIds' => array(
              'type' => 'array',
              'default' => array(),
          ),
      ),
  ) );
}
add_action( 'init', 'ac_product_block_register_block' );

function ac_product_block_render_callback( $attributes ) {
  $heading = esc_html( $attributes['heading'] );
  $product_ids = $attributes['productIds'];

  ob_start();
  ?>
  <div class="product-block">
    <h2 class="product-block__title"><?php echo $heading; ?></h2>
    <div class="product-block__grid">
      <?php foreach ( $product_ids as $product_id ) : ?>
        <?php
        $product = wc_get_product( $product_id );
        if ( ! $product ) {
          continue;
        }
        ?>
        <div class="product-block__item">
          <img class="product-block__item-image" src="<?php echo esc_url( wp_get_attachment_url( $product->get_image_id() ) ); ?>" alt="<?php echo esc_attr( $product->get_name() ); ?>">
          <h3 class="product-block__item-name"><?php echo esc_html( $product->get_name() ); ?></h3>
          <p class="product-block__item-price"><?php echo wp_kses_post( $product->get_price_html() ); ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
  <?php
  return ob_get_clean();
}
