var homadWizard = (function($) {
    var currentStep = 1;
    var $form, $steps, $progress;

    function init() {
        $form = $('#homad-quote-form');
        if (!$form.length) return;

        $steps = $form.find('.homad-wizard__step');
        $progress = $('.homad-wizard__progress .step');

        // Bind radio change to show/hide logic immediately (optional)
        $('input[name="quote_type"]').on('change', function() {
            // Logic to pre-filter step 2 could go here
        });
    }

    function showStep(step) {
        $steps.removeClass('active');
        $form.find('[data-step="' + step + '"]').addClass('active');

        // Update progress bar
        $progress.removeClass('active');
        $progress.each(function() {
            if ($(this).data('step') <= step) $(this).addClass('active');
        });

        currentStep = step;

        // If entering step 2, setup conditionals
        if (step === 2) {
            setupConditionals();
        }
    }

    function setupConditionals() {
        var type = $('input[name="quote_type"]:checked').val();
        $('.conditional-group').hide();
        $('.conditional-group[data-for="' + type + '"]').show();

        // Update title
        var titles = {
            'services': 'Custom Service Details',
            'packages': 'Package Selection',
            'b2b': 'Developer / B2B Details'
        };
        $('#step-2-title').text(titles[type] || 'Details');
    }

    function validateStep(step) {
        var valid = true;
        var $current = $form.find('[data-step="' + step + '"]');

        $current.find('input[required], select[required]').each(function() {
            if (!$(this).val()) {
                $(this).addClass('error');
                valid = false;
            } else {
                $(this).removeClass('error');
            }
        });
        return valid;
    }

    function nextStep() {
        if (!validateStep(currentStep)) return;
        showStep(currentStep + 1);
    }

    function prevStep() {
        showStep(currentStep - 1);
    }

    function showWizardNotice(message, type) {
        var $notice = $wizard.find('.homad-wizard__notice');
        if (!$notice.length) {
            return;
        }
        $notice.removeClass('is-error is-success').addClass(type === 'success' ? 'is-success' : 'is-error');
        $notice.text(message || '');
        if (window.homadToast) {
            window.homadToast(message, type === 'success' ? 'success' : 'error');
        }
    }

    function submitForm() {
        if (!validateStep(currentStep)) return;

        // Collect data
        var rawData = $form.serializeArray();
        var dataObj = {};

        $(rawData).each(function(i, field){
            dataObj[field.name] = field.value;
        });

        // Add loading state
        var $btn = $form.find('button').last();
        var origText = $btn.text();
        $btn.text('Sending...').prop('disabled', true);

        fetch(homadWizardVars.rest_url + 'skincare/v1/quote', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-WP-Nonce': homadWizardVars.rest_nonce
            },
            credentials: 'same-origin',
            body: JSON.stringify(dataObj)
        })
            .then(function(response) {
                return response.json().then(function(data) {
                    return { ok: response.ok, data: data };
                });
            })
            .then(function(result) {
                if (result.ok && result.data && result.data.success) {
                    showStep('success');
                    showWizardNotice(result.data.message || 'Solicitud enviada correctamente.', 'success');
                } else {
                    var message = result.data && result.data.message ? result.data.message : 'No se pudo enviar la solicitud.';
                    showWizardNotice('Error: ' + message, 'error');
                    $btn.text(origText).prop('disabled', false);
                }
            })
            .catch(function() {
                showWizardNotice('Server error. Please try again.', 'error');
                $btn.text(origText).prop('disabled', false);
            });
    }

    $(document).ready(init);

    return {
        nextStep: nextStep,
        prevStep: prevStep,
        submitForm: submitForm
    };

})(jQuery);

/**
 * Helper to open quote from other buttons (Services/Packages grid)
 */
function homadOpenQuote(type, prefill) {
    // Scroll to wizard
    var $wizard = $('#homad-quote-wizard');
    if ($wizard.length) {
        $('html, body').animate({
            scrollTop: $wizard.offset().top - 100
        }, 500);

        // Select type
        if(type) {
           // Mapping 'service' -> 'services' (plural) to match radio values
           var radioVal = (type === 'service') ? 'services' : (type === 'package' ? 'packages' : type);
           $('input[name="quote_type"][value="' + radioVal + '"]').prop('checked', true);
        }

        // TODO: Prefill specific dropdowns if needed (requires more complex logic)
    }
}
