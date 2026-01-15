(function($) {
    $(document).ready(function() {
        var formRoot = $('#homad-form-root');
        if (formRoot.length === 0) return;

        // Retrieve config from global variable (we need to localize this)
        var formConfig = window.homadFormConfig || [];
        var googleMapsKey = window.homadGoogleMapsKey || '';

        // Render Form
        function renderForm() {
            var html = '<form id="homad-dynamic-form" class="homad-form">';
            // Nonce
            html += '<input type="hidden" name="homad_form_nonce" value="' + window.homadFormNonce + '">';
            html += '<input type="hidden" name="action" value="homad_submit_lead">';

            formConfig.forEach(function(field, index) {
                var fieldId = field.name || 'field_' + index;
                var displayStyle = ''; // Logic handled after render

                html += '<div class="homad-form-group homad-field-row" data-condition="' + (field.condition_service || '') + '" data-name="' + fieldId + '">';

                html += '<label for="' + fieldId + '" class="homad-form-label">' + field.label + (field.required ? ' <span class="required">*</span>' : '') + '</label>';

                if (field.type === 'select') {
                    html += '<select name="' + fieldId + '" id="' + fieldId + '" class="homad-form-input" ' + (field.required ? 'required' : '') + '>';
                    html += '<option value="">Select...</option>';
                    if (field.options) {
                        var opts = field.options.split(',');
                        opts.forEach(function(opt) {
                            var val = opt.trim();
                            html += '<option value="' + val + '">' + val + '</option>';
                        });
                    }
                    html += '</select>';
                } else if (field.type === 'textarea') {
                    html += '<textarea name="' + fieldId + '" id="' + fieldId + '" class="homad-form-input" rows="4" ' + (field.required ? 'required' : '') + '></textarea>';
                } else if (field.type === 'address') {
                    html += '<input type="text" name="' + fieldId + '" id="' + fieldId + '" class="homad-form-input homad-address-input" ' + (field.required ? 'required' : '') + ' placeholder="Start typing address...">';
                } else {
                    // text, number, date
                    html += '<input type="' + field.type + '" name="' + fieldId + '" id="' + fieldId + '" class="homad-form-input" ' + (field.required ? 'required' : '') + '>';
                }

                if (field.validation_rule === 'dni') {
                     // Add simple client-side hint
                     html += '<small>Numbers only.</small>';
                }

                html += '</div>';
            });

            html += '<button type="submit" class="homad-submit-btn">Send Request</button>';
            html += '<div class="homad-form-message"></div>';
            html += '</form>';

            formRoot.html(html);

            // Post-Render: Init Logic
            initConditionalLogic();
            initGoogleMaps();
            initValidation();
        }

        function initConditionalLogic() {
            // Find the "service_type" field (or similar)
            // We assume one field acts as the trigger.
            // In a robust system, we'd map "Trigger Field -> Target Field".
            // Here we hardcode looking for a field named 'service_type' or 'service'.

            var triggerField = $('#homad-dynamic-form').find('[name="service_type"], [name="service"]');

            if (triggerField.length > 0) {
                triggerField.on('change', function() {
                    var val = $(this).val().toLowerCase();

                    $('.homad-field-row').each(function() {
                        var condition = $(this).data('condition');
                        if (!condition) {
                            $(this).show(); // Always show if no condition
                            return;
                        }

                        // Simple substring match for "Architecture" vs "Interior"
                        if (val.indexOf(condition) !== -1 || condition.indexOf(val) !== -1) {
                            $(this).show();
                        } else {
                            $(this).hide();
                        }
                    });
                });

                // Trigger initial state
                triggerField.trigger('change');
            }
        }

        function initGoogleMaps() {
            if (!googleMapsKey || typeof google.maps === 'undefined') return;

            $('.homad-address-input').each(function() {
                new google.maps.places.Autocomplete(this);
            });
        }

        function initValidation() {
            $('#homad-dynamic-form').on('submit', function(e) {
                e.preventDefault();
                var form = $(this);
                var valid = true;
                var msgDiv = form.find('.homad-form-message');
                msgDiv.text('').css('color', '');

                // Custom Validation (DNI)
                formConfig.forEach(function(field, index) {
                    if (field.validation_rule === 'dni') {
                        var fieldId = field.name || 'field_' + index;
                        var input = form.find('[name="' + fieldId + '"]');
                        if (input.is(':visible') && input.val() && !/^\d+$/.test(input.val())) {
                            msgDiv.text(field.label + ' must be numbers only.').css('color', 'red');
                            valid = false;
                        }
                    }
                });

                if (!valid) return;

                // AJAX Submit
                var formData = form.serialize();
                var msgDiv = form.find('.homad-form-message');
                msgDiv.text('Sending...').css('color', 'black');

                $.post(window.homadAjaxUrl, formData, function(response) {
                    if (response.success) {
                        msgDiv.text('Thank you! We will contact you shortly.').css('color', 'green');
                        form[0].reset();
                    } else {
                        msgDiv.text('Error: ' + (response.data || 'Unknown error')).css('color', 'red');
                    }
                });
            });
        }

        renderForm();
    });
})(jQuery);
