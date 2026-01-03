jQuery(function ($) {
  $('.sk-color-field').wpColorPicker();

  function openMediaFrame($field) {
    const frame = wp.media({
      title: 'Seleccionar imagen',
      button: { text: 'Usar imagen' },
      multiple: false,
    });

    frame.on('select', function () {
      const attachment = frame.state().get('selection').first().toJSON();
      $field.find('input[type="hidden"]').val(attachment.id);
      const preview = $field.find('.sk-media-preview');
      preview.html('<img src="' + attachment.sizes.thumbnail.url + '" alt="" style="max-width: 120px; height: auto;">');
    });

    frame.open();
  }

  $(document).on('click', '.sk-media-upload', function (event) {
    event.preventDefault();
    const $field = $(this).closest('.sk-media-field');
    openMediaFrame($field);
  });

  $(document).on('click', '.sk-media-remove', function (event) {
    event.preventDefault();
    const $field = $(this).closest('.sk-media-field');
    $field.find('input[type="hidden"]').val('');
    $field.find('.sk-media-preview').empty();
  });
});
