jQuery(document).ready(function($) {

    // Slider Logic (Simple Fade)
    $('.sk-hero-slider').each(function() {
        var $slider = $(this);
        var $slides = $slider.find('.sk-slide');
        var count = $slides.length;
        var current = 0;

        if (count > 1) {
            setInterval(function() {
                $slides.eq(current).fadeOut(800);
                current = (current + 1) % count;
                $slides.eq(current).fadeIn(800);
            }, 5000);
        }
    });

    // Wishlist AJAX
    $(document).on('click', '.sk-add-to-wishlist', function(e) {
        e.preventDefault();
        var $btn = $(this);
        var pid = $btn.data('product-id');

        $.post(sk_vars.ajax_url, {
            action: 'sk_add_to_wishlist',
            nonce: sk_vars.nonce,
            product_id: pid
        }, function(res) {
            if (res.success) {
                $btn.addClass('added');
                alert('Añadido a favoritos');
            }
        });
    });

    // Stock Notifier AJAX
    $('#sk-stock-notifier-form').on('submit', function(e) {
        e.preventDefault();
        var $form = $(this);
        var $msg = $form.find('.message');

        $.post(sk_vars.ajax_url, {
            action: 'sk_stock_notify',
            nonce: sk_vars.nonce,
            email: $form.find('input[name="email"]').val(),
            product_id: $form.find('input[name="product_id"]').val()
        }, function(res) {
            if (res.success) {
                $msg.html(res.data.message).css('color', 'green');
            } else {
                $msg.html(res.data.message).css('color', 'red');
            }
        });
    });

    // AJAX Search
    var searchTimeout;
    $('.sk-search-input').on('keyup', function() {
        var term = $(this).val();
        var $results = $(this).closest('.sk-ajax-search-wrapper').find('.sk-search-results');

        clearTimeout(searchTimeout);

        if (term.length < 3) {
            $results.hide().html('');
            return;
        }

        searchTimeout = setTimeout(function() {
            $.get(sk_vars.ajax_url, {
                action: 'sk_ajax_search',
                term: term
            }, function(res) {
                if (res.success && res.data.length > 0) {
                    var html = '<ul>';
                    $.each(res.data, function(i, item) {
                        html += '<li>';
                        html += '<a href="' + item.url + '">';
                        if(item.image) html += '<img src="' + item.image + '">';
                        html += '<div class="info"><span>' + item.title + '</span><span class="price">' + item.price + '</span></div>';
                        html += '</a>';
                        html += '</li>';
                    });
                    html += '</ul>';
                    $results.html(html).show();
                } else {
                    $results.html('<p>No se encontraron resultados.</p>').show();
                }
            });
        }, 500);
    });

    // Close search results on click outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.sk-ajax-search-wrapper').length) {
            $('.sk-search-results').hide();
        }
    });

    // Product Tabs
    $('.sk-tab-toggle').on('click', function() {
        $(this).toggleClass('active');
        $(this).next('.sk-tab-content').slideToggle();
        $(this).find('i').toggleClass('eicon-plus eicon-minus');
    });

    // Ajax Filter (Static Demo Logic)
    $('.sk-filter-group input').on('change', function() {
        var activeBrands = [];
        $('.sk-filter-group input[name="brand"]:checked').each(function() {
            activeBrands.push($(this).val());
        });
        console.log('Filtering by:', activeBrands);
    });

    // Quick View (Static Demo)
    $(document).on('click', '.sk-quick-view-btn', function(e) {
        e.preventDefault();
        var pid = $(this).data('product_id');

        // Open Modal (Pseudo-code)
        var modalHtml = '<div class="sk-qv-modal"><div class="sk-qv-content">Loading...<button class="close">x</button></div></div>';
        $('body').append(modalHtml);

        // Simulate AJAX fetch
        setTimeout(function() {
            $('.sk-qv-content').html('<div class="qv-inner"><img src="https://via.placeholder.com/300"><div><h3>Product Title</h3><span class="price">£25.00</span><button class="btn">Add to Cart</button></div><button class="close">&times;</button></div>');
        }, 500);
    });

    $(document).on('click', '.sk-qv-modal .close, .sk-qv-modal', function(e) {
        if (e.target !== this) return;
        $('.sk-qv-modal').remove();
    });

    // Contact Form AJAX
    $('.sk-contact-form').on('submit', function(e) {
        e.preventDefault();
        var $form = $(this);
        var $btn = $form.find('button');

        $btn.prop('disabled', true).text('Enviando...');

        $.post(sk_vars.ajax_url, {
            action: 'sk_contact_submit',
            nonce: sk_vars.nonce,
            name: $form.find('input[type="text"]').val(),
            email: $form.find('input[type="email"]').val(),
            message: $form.find('textarea').val()
        }, function(res) {
            $btn.prop('disabled', false).text('Enviar Mensaje');
            if (res.success) {
                alert(res.data.message);
                $form[0].reset();
            } else {
                alert(res.data.message);
            }
        });
    });

    // Mobile Menu Toggle (Basic)
    $(document).on('click', '.sk-mobile-menu-trigger', function(e) {
        e.preventDefault();
        $('.sk-mobile-menu-drawer').toggleClass('open');
    });

    $(document).on('click', function(e) {
        if (!$(e.target).closest('.sk-mobile-menu-drawer, .sk-mobile-menu-trigger').length) {
            $('.sk-mobile-menu-drawer').removeClass('open');
        }
    });

    // Sticky ATC Logic
    $(window).scroll(function() {
        if ($(window).scrollTop() > 500) {
            $('.sk-sticky-atc').addClass('visible');
        } else {
            $('.sk-sticky-atc').removeClass('visible');
        }
    });

    // Swatches Logic
    $('.sk-swatch-label input').on('change', function() {
        var val = $(this).val();
        var attribute = $(this).closest('.sk-swatches').data('attribute');

        // Find standard woo select
        var $select = $('select[name="' + attribute + '"]');
        if ($select.length) {
            $select.val(val).trigger('change');
        }
    });

});
