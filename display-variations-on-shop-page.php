function custom_variation_swatches_on_shop_pages() {
  // Remove the WooCommerce action that adds a 'Select Options' button for variable products.
  remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);

  // Add a custom action that calls the custom swatches rendering function.
  add_action('woocommerce_after_shop_loop_item', 'render_custom_variation_swatches', 10);

  function render_custom_variation_swatches() {
      global $product;

      // Make sure the product is variable.
      if ($product->is_type('variable')) {
          // Download available product variations.
          $variations = $product->get_available_variations();
          echo '<div class="custom-swatches-container" style="margin-top: 10px;">';

          foreach ($variations as $variation) {
              // Generate a swatch button for each variation.
              echo '<button class="custom-swatch" data-variation-id="' . esc_attr($variation['variation_id']) . '" data-product-id="' . esc_attr($product->get_id()) . '">' . esc_html(implode(" / ", $variation['attributes'])) . '</button>';
          }

          echo '</div>';
      }
  }

  // Add scripts that support redirection to the product with the selected variation.
  add_action('wp_footer', 'custom_swatches_script');
  function custom_swatches_script() {
      ?>
      <script>
      document.addEventListener('DOMContentLoaded', function() {
          var swatches = document.querySelectorAll('.custom-swatch');

          swatches.forEach(function(swatch) {
              swatch.addEventListener('click', function() {
                  var variationId = this.dataset.variationId;
                  var productId = this.dataset.productId;
                  // Redirect to the product page with the selected variation.
                  window.location.href = '/produkt/?p=' + productId + '&variation_id=' + variationId;
                  // Make sure the URL format is compatible with your store's configuration.
              });
          });
      });
      </script>
      <style>
      .custom-swatches-container {
          display: flex;
          flex-wrap: wrap;
      }

      .custom-swatch {
          padding: 10px;
          margin: 5px;
          background-color: #fff;
          border: 1px solid #ddd;
          cursor: pointer;
          font-size: 14px;
      }

      .custom-swatch:hover {
          border-color: #333;
      }
      </style>
      <?php
  }
}
add_action('init', 'custom_variation_swatches_on_shop_pages');
