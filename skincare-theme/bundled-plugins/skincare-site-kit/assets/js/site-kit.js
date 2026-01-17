jQuery(document).ready(function($) {

    function showToast(message, type) {
        var $container = $('.sk-toast-container');
        if (!$container.length) {
            $container = $('<div class="sk-toast-container" aria-live="polite" aria-atomic="true"></div>');
            $('body').append($container);
        }
        var $toast = $('<div class="sk-toast"></div>');
        if (type) {
            $toast.addClass('sk-toast--' + type);
        }
        $toast.text(message);
        $container.append($toast);
        setTimeout(function() {
            $toast.addClass('is-visible');
        }, 10);
        setTimeout(function() {
            $toast.removeClass('is-visible');
            setTimeout(function() {
                $toast.remove();
                if (!$container.children().length) {
                    $container.remove();
                }
            }, 300);
        }, 3200);
    }

    window.skShowToast = showToast;

    function showConfirmModal(options) {
        var settings = $.extend({
            title: 'Confirmar acción',
            message: '',
            confirmText: 'Confirmar',
            cancelText: 'Cancelar',
            onConfirm: function() {},
            onCancel: function() {}
        }, options || {});

        var $modal = $(`
            <div class="sk-modal" role="dialog" aria-modal="true">
                <div class="sk-modal__overlay"></div>
                <div class="sk-modal__content">
                    <h3>${settings.title}</h3>
                    <p>${settings.message}</p>
                    <div class="sk-modal__actions">
                        <button type="button" class="sk-modal__btn sk-modal__btn--cancel">${settings.cancelText}</button>
                        <button type="button" class="sk-modal__btn sk-modal__btn--confirm">${settings.confirmText}</button>
                    </div>
                </div>
            </div>
        `);

        $('body').append($modal);

        $modal.find('.sk-modal__btn--cancel, .sk-modal__overlay').on('click', function() {
            settings.onCancel();
            $modal.remove();
        });

        $modal.find('.sk-modal__btn--confirm').on('click', function() {
            settings.onConfirm();
            $modal.remove();
        });
    }


    function skRestFetch(endpoint, options) {
        var restUrl = sk_vars && sk_vars.rest_url ? sk_vars.rest_url : '';
        var restNonce = sk_vars && sk_vars.rest_nonce ? sk_vars.rest_nonce : '';
        var url = restUrl ? restUrl + endpoint : '';
        return fetch(url, $.extend(true, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-WP-Nonce': restNonce
            },
            credentials: 'same-origin'
        }, options || {}));
    }

    function skAjaxRequest(action, data, options) {
        var settings = $.extend({
            method: 'POST',
            retries: 0,
            loadingTarget: null
        }, options || {});

        if (!sk_vars || !sk_vars.ajax_url) {
            return Promise.reject(new Error('AJAX URL missing'));
        }

        var payload = $.extend({
            action: action,
            nonce: sk_vars.nonce || ''
        }, data || {});

        if (settings.loadingTarget) {
            $(settings.loadingTarget).addClass('is-loading').attr('aria-busy', 'true');
        }

        var body = new URLSearchParams();
        Object.keys(payload).forEach(function(key) {
            if (typeof payload[key] !== 'undefined' && payload[key] !== null) {
                body.append(key, payload[key]);
            }
        });

        var request = function() {
            return fetch(sk_vars.ajax_url, {
                method: settings.method,
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
                },
                credentials: 'same-origin',
                body: body.toString()
            }).then(function(response) {
                return response.json();
            });
        };

        return request().catch(function(error) {
            if (settings.retries > 0) {
                settings.retries -= 1;
                return request();
            }
            throw error;
        }).finally(function() {
            if (settings.loadingTarget) {
                $(settings.loadingTarget).removeClass('is-loading').removeAttr('aria-busy');
            }
        });
    }

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
    $(document).on('click', '.sk-wishlist-toggle, .sk-add-to-wishlist', function(e) {
        e.preventDefault();
        var $btn = $(this);
        var pid = $btn.data('product-id');
        var isToggle = $btn.hasClass('sk-wishlist-toggle');
        var inWishlist = isToggle ? !!$btn.data('in-wishlist') : false;
        var action = inWishlist ? 'sk_remove_from_wishlist' : 'sk_add_to_wishlist';

        if (!pid) {
            showToast('Producto inválido.', 'error');
            return;
        }

        skAjaxRequest(action, { product_id: pid }, { loadingTarget: $btn })
            .then(function(result) {
                if (result && result.success) {
                    if (isToggle) {
                        var newState = !inWishlist;
                        $btn.data('in-wishlist', newState);
                        $btn.toggleClass('is-active', newState);
                        $btn.attr('aria-pressed', newState ? 'true' : 'false');
                        $btn.text(newState ? 'Quitar de favoritos' : 'Añadir a favoritos');
                    } else {
                        $btn.addClass('added');
                    }

                    if (inWishlist && isToggle) {
                        var $productCard = $btn.closest('li.product');
                        if ($productCard.length) {
                            $productCard.remove();
                        }
                        if ($('.sk-wishlist-grid li.product').length === 0) {
                            $('.sk-wishlist-grid').replaceWith('<p class="sk-wishlist-empty">Tu lista de deseos está vacía.</p>');
                        }
                    }

                    showToast(result.data && result.data.message ? result.data.message : 'Favoritos actualizados.', 'success');
                } else {
                    showToast(result && result.data && result.data.message ? result.data.message : 'No se pudo actualizar favoritos.', 'error');
                }
            })
            .catch(function() {
                showToast('No se pudo conectar. Intenta de nuevo.', 'error');
            });
    });

    // Stock Notifier AJAX
    $('#sk-stock-notifier-form').on('submit', function(e) {
        e.preventDefault();
        var $form = $(this);
        var $msg = $form.find('.message');

        skAjaxRequest('sk_stock_notify', {
            email: $form.find('input[name="email"]').val(),
            product_id: $form.find('input[name="product_id"]').val()
        }, { loadingTarget: $form.find('button[type=\"submit\"]') })
            .then(function(result) {
                if (result && result.success) {
                    $msg.html(result.data && result.data.message ? result.data.message : '¡Te avisaremos pronto!').css('color', 'green');
                } else {
                    $msg.html(result && result.data && result.data.message ? result.data.message : 'Error').css('color', 'red');
                }
            })
            .catch(function() {
                $msg.html('No se pudo conectar. Intenta de nuevo.').css('color', 'red');
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
            var actionUrl = sk_vars && sk_vars.ajax_url ? sk_vars.ajax_url : '';
            var nonce = sk_vars && sk_vars.nonce ? sk_vars.nonce : '';

            if(!actionUrl) return;

            // Updated to use admin-ajax.php with nonce as per hardening requirements
            // Using jQuery.ajax to support legacy admin-ajax endpoint as defined in class-ajax-search.php
            $.ajax({
                url: actionUrl,
                type: 'GET',
                data: {
                    action: 'sk_ajax_search',
                    term: term,
                    nonce: nonce
                },
                success: function(response) {
                    if (response.success && response.data && response.data.length > 0) {
                        var html = '<ul>';
                        $.each(response.data, function(i, item) {
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

    // Account Tabs
    $(document).on('click', '[data-sk-tabs] .sk-tab-btn', function() {
        var $btn = $(this);
        var $wrapper = $btn.closest('[data-sk-tabs]');
        $wrapper.find('.sk-tab-btn').removeClass('active').attr('aria-selected', 'false');
        $wrapper.find('.sk-tab-pane').removeClass('active');
        $btn.addClass('active').attr('aria-selected', 'true');
        var $target = $($btn.data('target'));
        $target.addClass('active');
        if ($target.is('#tab-rewards')) {
            animatePoints($target.find('.sk-points-total'));
        }
    });

    function animatePoints($el) {
        if (!$el.length || $el.data('animated')) {
            return;
        }
        var target = parseInt($el.data('target'), 10) || 0;
        var current = 0;
        var step = Math.max(1, Math.floor(target / 30));
        var interval = setInterval(function() {
            current += step;
            if (current >= target) {
                current = target;
                clearInterval(interval);
            }
            $el.text(current);
        }, 30);
        $el.data('animated', true).addClass('is-animated');
    }

    animatePoints($('.sk-points-total'));

    // Rewards Redeem
    $(document).on('click', '#sk-redeem-btn', function(e) {
        e.preventDefault();
        var $btn = $(this);
        var $container = $btn.closest('.sk-rewards-actions');
        var $message = $container.find('.sk-inline-message');

        if ($btn.hasClass('is-loading')) {
            return;
        }

        var redeemPoints = sk_vars && sk_vars.redeem_points ? sk_vars.redeem_points : 500;
        showConfirmModal({
            title: 'Canje de puntos',
            message: '¿Deseas canjear ' + redeemPoints + ' puntos por un cupón?',
            confirmText: 'Canjear',
            cancelText: 'Cancelar',
            onConfirm: function() {
                var originalText = $btn.text();
                var loadingText = $btn.data('loading-text') || 'Canjeando...';

                $btn.addClass('is-loading').prop('disabled', true).text(loadingText);
                $message.removeClass('is-success is-error').text('Procesando tu canje...');

                skRestFetch('rewards/redeem')
                    .then(function(response) {
                        return response.json().then(function(data) {
                            return { ok: response.ok, data: data };
                        });
                    })
                    .then(function(result) {
                        if (result.ok) {
                            $message.addClass('is-success').text('¡Listo! Tu cupón es ' + result.data.code + '.');
                            $('.points-value, .sk-points-total').text(result.data.new_balance);
                            showToast('Canje exitoso. Cupón generado.', 'success');
                            $btn.remove();
                        } else {
                            var errorMessage = result.data && result.data.message ? result.data.message : 'No se pudo canjear en este momento.';
                            $message.addClass('is-error').text(errorMessage);
                            showToast(errorMessage, 'error');
                            $btn.removeClass('is-loading').prop('disabled', false).text(originalText);
                        }
                    })
                    .catch(function() {
                        $message.addClass('is-error').text('No se pudo conectar. Intenta de nuevo.');
                        showToast('No se pudo conectar. Intenta de nuevo.', 'error');
                        $btn.removeClass('is-loading').prop('disabled', false).text(originalText);
                    });
            }
        });
    });

    // Ajax Filter (Static Demo Logic)
    $('.sk-filter-group input').on('change', function() {
        var activeBrands = [];
        $('.sk-filter-group input[name="brand"]:checked').each(function() {
            activeBrands.push($(this).val());
        });

        var maxPrice = $('input[name="max_price"]').val();

        var actionUrl = sk_vars && sk_vars.ajax_url ? sk_vars.ajax_url : '';
        var nonce = sk_vars && sk_vars.nonce ? sk_vars.nonce : '';

        if (!actionUrl) return;

        // AJAX Request for Filtering
        $.ajax({
            url: actionUrl,
            type: 'POST',
            data: {
                action: 'sk_filter_products',
                nonce: nonce,
                brand: activeBrands,
                max_price: maxPrice
            },
            success: function(response) {
                if (response.success && response.data.html) {
                    $('.sk-product-grid').replaceWith(response.data.html);
                } else {
                    // Fallback or empty state
                }
            }
        });
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
            var placeholderImage = sk_vars && sk_vars.placeholder_image ? sk_vars.placeholder_image : '';
            var imageMarkup = placeholderImage ? '<img src="' + placeholderImage + '" alt="Producto">' : '';
            $('.sk-qv-content').html('<div class="qv-inner">' + imageMarkup + '<div><h3>Título del producto</h3><span class="price">£25.00</span><button class="btn">Agregar al carrito</button></div><button class="close">&times;</button></div>');
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

        skRestFetch('forms/contact', {
            body: JSON.stringify({
                name: $form.find('input[type="text"]').val(),
                email: $form.find('input[type="email"]').val(),
                message: $form.find('textarea').val()
            })
        })
            .then(function(response) {
                return response.json().then(function(data) {
                    return { ok: response.ok, data: data };
                });
            })
            .then(function(result) {
                $btn.prop('disabled', false).text('Enviar Mensaje');
                if (result.ok && result.data && result.data.success) {
                    showToast(result.data.message, 'success');
                    $form[0].reset();
                } else {
                    var message = result.data && result.data.message ? result.data.message : 'No se pudo enviar el mensaje.';
                    showToast(message, 'error');
                }
            })
            .catch(function() {
                $btn.prop('disabled', false).text('Enviar Mensaje');
                showToast('No se pudo conectar. Intenta de nuevo.', 'error');
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
