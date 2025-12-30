(function($) {
    // Admin Repeater Logic for Form Builder
    $(document).ready(function() {
        var container = $('#homad-form-fields-container');

        // Add Field
        $('#homad-add-field').on('click', function(e) {
            e.preventDefault();
            var index = container.find('.homad-field-row').length;
            var template = $('#homad-field-template').html().replace(/{{index}}/g, index);
            container.append(template);
        });

        // Remove Field
        container.on('click', '.homad-remove-field', function(e) {
            e.preventDefault();
            $(this).closest('.homad-field-row').remove();
        });

        // Toggle Sortable (Optional)
        if ($.fn.sortable) {
            container.sortable({
                handle: '.homad-field-handle'
            });
        }
    });
})(jQuery);
