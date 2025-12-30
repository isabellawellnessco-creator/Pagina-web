(function($) {
    $(document).ready(function() {
        var totalEl = $('#homad-config-total');

        $('.homad-config-option input[type="radio"]').on('change', function() {
            var group = $(this).attr('name').replace('homad_group_', '');
            var targetId = $(this).val();
            var price = parseFloat($(this).data('price')) || 0;

            // 1. Handle Visuals
            // Hide all images for this group
            $('.homad-layer-img.group-' + group).hide();
            // Show selected
            $('#' + targetId).show();

            // 2. Recalculate Total Price
            var totalExtra = 0;
            $('.homad-config-option input[type="radio"]:checked').each(function() {
                totalExtra += parseFloat($(this).data('price')) || 0;
            });

            // Format Price (Simple version, ideally use WC Currency settings)
            totalEl.text('$' + totalExtra.toFixed(2));

            // 3. Update Hidden Inputs for Cart
            $('#homad_config_total_input').val(totalExtra);

            var selectedNames = [];
            $('.homad-config-option input[type="radio"]:checked').each(function() {
                 var label = $(this).closest('label').text().trim(); // Gets "Red (+$10)"
                 selectedNames.push(label);
            });
            $('#homad_selected_layers').val(selectedNames.join(', '));
        });

        // Move inputs to the main Add To Cart form on page load
        var cartForm = $('form.cart');
        if (cartForm.length > 0) {
            $('#homad-cart-inputs').children().appendTo(cartForm);
        }
    });
})(jQuery);
