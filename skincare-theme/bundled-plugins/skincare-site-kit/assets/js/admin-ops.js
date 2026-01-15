jQuery(document).ready(function($) {
    // === Conditional Logic ===
    function updateVisibility() {
        var zone = $('#sk_zone').val();
        var payment = $('#sk_payment_type_ops').val();
        var agency = $('#sk_agency').val();

        // Zone Logic
        if (zone === 'provincia') {
            $('.sk-group-provincia').slideDown();
            $('.sk-group-lima').slideUp();
            // Auto-select Shalom if not set
            if (!agency) $('#sk_agency').val('shalom').trigger('change');
        } else if (zone === 'lima') {
            $('.sk-group-provincia').slideUp();
            $('.sk-group-lima').slideDown();
             // Auto-select Urpi if not set
             if (!agency) $('#sk_agency').val('urpi').trigger('change');
        } else {
            $('.sk-group-provincia').slideUp();
            $('.sk-group-lima').slideUp();
        }

        // Agency Logic (Sub-check)
        // If user manually changes agency, we might want to show/hide specific fields
        // For now, Shalom fields are tied to 'Provincia' group and Urpi to 'Lima' group.

        // Payment Logic
        if (payment === 'contraentrega') {
            $('.sk-group-contraentrega').slideDown();
        } else {
            $('.sk-group-contraentrega').slideUp();
        }
    }

    // Bind change events
    $('#sk_zone, #sk_payment_type_ops, #sk_agency').on('change', updateVisibility);

    // Init on load
    updateVisibility();

    // === AJAX Status Update ===
    $('.sk-ops-action-btn').on('click', function(e) {
        e.preventDefault();
        var btn = $(this);
        var orderId = btn.data('order-id');
        var status = btn.data('status');
        var originalText = btn.text();

        if (btn.hasClass('disabled')) return;

        btn.text('...').addClass('disabled');

        $.post(ajaxurl, {
            action: 'sk_update_op_status',
            order_id: orderId,
            status: status,
            nonce: sk_ops_vars.nonce
        }, function(response) {
            if (response.success) {
                // Update badge
                $('#sk-ops-current-status').text(response.data.new_label)
                    .removeClass()
                    .addClass('sk-ops-badge status-' + response.data.status_slug);

                // Show success toast
                alert(response.data.message); // Replace with nice toast later if possible
                location.reload(); // Reload to refresh actions logic
            } else {
                alert('Error: ' + response.data);
            }
            btn.text(originalText).removeClass('disabled');
        });
    });

    // === WhatsApp Contextual ===
    // This handles the click to log it, then opens the link
    $('.sk-wa-action-btn').on('click', function(e) {
        // The href is already set by PHP. We just want to log it.
        var btn = $(this);
        var orderId = btn.data('order-id');
        var template = btn.data('template');

        $.post(ajaxurl, {
            action: 'sk_log_whatsapp_click',
            order_id: orderId,
            template: template,
            nonce: sk_ops_vars.nonce
        });
        // Allow default behavior (opening link)
    });
});
