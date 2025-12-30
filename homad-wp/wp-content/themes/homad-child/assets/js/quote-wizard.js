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

        $.ajax({
            url: homadWizardVars.ajaxurl,
            type: 'POST',
            data: {
                action: 'homad_submit_quote_wizard',
                nonce: homadWizardVars.nonce,
                fields: JSON.stringify(dataObj)
            },
            success: function(response) {
                if (response.success) {
                    showStep('success');
                } else {
                    alert('Error: ' + response.data.message);
                    $btn.text(origText).prop('disabled', false);
                }
            },
            error: function() {
                alert('Server error. Please try again.');
                $btn.text(origText).prop('disabled', false);
            }
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
