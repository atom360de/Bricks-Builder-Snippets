// In the second version of the script, logic has been added that crosses out unavailable variants.

function atom360_de_convert_select_to_swatches() {
    ?>
    <script type="text/javascript">
document.addEventListener('DOMContentLoaded', function() {
    // Parse the product variations JSON from the form attribute
    var form = document.querySelector('.variations_form');
    var productVariations = JSON.parse(form.getAttribute('data-product_variations'));

    // Function to check if a variation is in stock by its value
    function isInStock(value) {
        return productVariations.some(function(variation) {
            return variation.attributes.attribute_pa_rozmiar === value && variation.is_in_stock;
        });
    }

    // Find all variant selectors
    var variantSelects = document.querySelectorAll('select[id^="pa_"]');

    variantSelects.forEach(function(select) {
        var swatchesContainer = document.createElement('div');
        swatchesContainer.className = 'swatches-container';

        Array.from(select.options).forEach(function(option) {
            if (option.value) {
                var swatch = document.createElement('button');
                swatch.className = 'swatch';
                swatch.innerText = option.text;
                swatch.dataset.value = option.value;
                swatch.setAttribute('type', 'button');
                
                // Disable the swatch if the variant is out of stock
                if (!isInStock(option.value)) {
                    swatch.disabled = true;
                    swatch.classList.add('swatch-unavailable');
                }

                swatch.addEventListener('click', function() {
                    // Update the value of the selector
                    select.value = this.dataset.value;
                    var event = new CustomEvent('change', { bubbles: true });
                    select.dispatchEvent(event);

                    // Remove the 'swatch-active' class from all swatches in this container
                    swatchesContainer.querySelectorAll('.swatch').forEach(function(el) {
                        el.classList.remove('swatch-active');
                    });

                    // Add 'swatch-active' class to clicked swatch
                    this.classList.add('swatch-active');
                });
                swatchesContainer.appendChild(swatch);
            }
        });

        // Hide original selector and add swatches
        select.style.display = 'none';
        select.parentNode.insertBefore(swatchesContainer, select);
    });
});
    </script>
    <style>
    /* Styles remain unchanged */
    #swatches-container {
      display: flex;
      flex-wrap: wrap;
    }

    .swatch {
      padding: 10px;
      margin: 5px;
      background-color: #f1f1f1;
      border: 1px solid #ccc;
      cursor: pointer;
    }
    .swatch:hover {
      background-color: #e9e9e9;
    }
    .swatch-active {
      background-color: green !important;
      color:white!important;
    }
    .swatch-unavailable {
      cursor: not-allowed;
      text-decoration: line-through;
      background-color: #e0e0e0;
    }
    </style>
    <?php
}
add_action('wp_footer', 'atom360_de_convert_select_to_swatches');
