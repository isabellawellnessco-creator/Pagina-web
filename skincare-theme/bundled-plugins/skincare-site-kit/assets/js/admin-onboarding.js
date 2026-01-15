jQuery(document).ready(function($) {
    const $startBtn = $('#sk-start-import');
    const $intro = $('#sk-wizard-intro');
    const $progress = $('#sk-wizard-progress');
    const $success = $('#sk-wizard-success');
    const $progressBar = $('.sk-progress-fill');
    const $statusText = $('.sk-progress-status');
    const $logList = $('.sk-log-list');

    // Steps defined in PHP
    const steps = ['pages', 'categories', 'products', 'theme_parts', 'menus', 'finalize'];
    let currentStepIndex = 0;

    // Auto-start if mode is 'repair'
    if (sk_onboarding.mode === 'repair') {
         $intro.hide();
         $progress.show();
         runStep();
    }

    $startBtn.on('click', function() {
        $intro.hide();
        $progress.show();
        runStep();
    });

    function runStep() {
        if (currentStepIndex >= steps.length) {
            completeWizard();
            return;
        }

        const stepKey = steps[currentStepIndex];
        const stepLabel = sk_onboarding.steps[stepKey] || stepKey;

        // Update UI
        $statusText.text(stepLabel);
        const progressPct = ((currentStepIndex) / steps.length) * 100;
        $progressBar.css('width', progressPct + '%');

        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'sk_onboarding_run_step',
                nonce: sk_onboarding.nonce,
                step: stepKey,
                mode: sk_onboarding.mode || 'install' // Send mode context
            },
            success: function(response) {
                if (response.success) {
                    addLog(stepLabel + ' - OK');
                } else {
                    addLog(stepLabel + ' - ' + (response.data.message || 'Error'), true);
                }
                // Always continue to next step to ensure partial fixes
                currentStepIndex++;
                runStep();
            },
            error: function() {
                addLog(stepLabel + ' - Server/Network Error', true);
                 currentStepIndex++;
                 runStep();
            }
        });
    }

    function addLog(message, isError = false) {
        const $li = $('<li>').text(message);
        if (isError) $li.addClass('sk-error');
        $logList.append($li);
        $logList.scrollTop($logList[0].scrollHeight);
    }

    function completeWizard() {
        $progressBar.css('width', '100%');
        setTimeout(function() {
            $progress.hide();
            $success.show();
        }, 500);
    }
});
