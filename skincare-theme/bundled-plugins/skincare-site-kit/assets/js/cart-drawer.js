(function($) {
    'use strict';

    const SkincareCart = {
        init: function() {
            if(typeof sk_cart_vars === 'undefined') return;

            this.threshold = parseFloat(sk_cart_vars.free_shipping_threshold);
            this.currencySymbol = sk_cart_vars.currency_symbol || '$';
            this.currencyCode = sk_cart_vars.currency_code || 'USD';
            this.createDrawer();
            this.bindEvents();
        },

        createDrawer: function() {
            if ($('#sk-cart-drawer').length) return;

            $('body').append(`
                <div id="sk-cart-drawer" class="sk-drawer">
                    <div class="sk-drawer-overlay"></div>
                    <div class="sk-drawer-content">

                        <div class="sk-drawer-header">
                            <h3>Tu Bolsa <span class="sk-cart-count">(0)</span></h3>
                            <button class="sk-drawer-close">&times;</button>
                        </div>

                        <div class="sk-shipping-progress-bar">
                             <p class="sk-shipping-msg">¡Estás a <span class="amount-needed">${this.formatMoney(this.threshold)}</span> de <strong>Envío Gratis</strong>!</p>
                             <div class="progress-track"><div class="progress-fill" style="width:0%"></div></div>
                        </div>

                        <div class="sk-drawer-main-scroll">
                            <div class="sk-drawer-body">
                                <div class="sk-cart-items"></div>
                                <div class="sk-cart-empty" style="display:none;">
                                    <p>Tu bolsa está vacía.</p>
                                    <a href="/tienda/" class="btn sk-btn">Ir a la tienda</a>
                                </div>
                            </div>

                            <!-- Upsell Section -->
                            <div class="sk-drawer-upsell">
                                <h4>Te podría gustar</h4>
                                <div class="sk-upsell-container">
                                    ${sk_cart_vars.recommendations_html || ''}
                                </div>
                            </div>
                        </div>

                        <div class="sk-drawer-footer">
                            <div class="sk-drawer-feedback" role="status" aria-live="polite"></div>

                            <!-- Extras -->
                            <div class="sk-cart-extras">
                                <details>
                                    <summary>Añadir cupón</summary>
                                    <div class="coupon-form">
                                        <input type="text" id="sk-drawer-coupon" placeholder="Código">
                                        <button id="sk-apply-coupon">Aplicar</button>
                                    </div>
                                </details>
                                <details>
                                    <summary>Nota del pedido</summary>
                                    <textarea placeholder="Instrucciones especiales..."></textarea>
                                </details>
                            </div>

                             <div class="sk-policy-check">
                                <label>
                                    <input type="checkbox" id="sk-accept-policy">
                                    Acepto las <a href="${sk_cart_vars.policy_url}" target="_blank">políticas de compra y devoluciones</a>.
                                </label>
                            </div>

                            <div class="sk-cart-subtotal">
                                <span>Subtotal:</span>
                                <span class="amount">${this.formatMoney(0)}</span>
                            </div>

                            <a href="${sk_cart_vars.checkout_url}" class="btn sk-btn-checkout btn-block disabled" id="sk-checkout-btn">Finalizar Compra</a>
                        </div>
                    </div>
                </div>
            `);
        },

        bindEvents: function() {
            $(document).on('click', 'a[href="#cart-drawer"], .sk-cart-trigger', (e) => {
                e.preventDefault();
                this.openDrawer();
            });

            $(document).on('click', '.sk-drawer-close, .sk-drawer-overlay', () => {
                this.closeDrawer();
            });

            // WC Events
            $(document.body).on('added_to_cart removed_from_cart updated_cart_totals', () => {
                this.updateCart();
                this.openDrawer();
            });

            // Qty Buttons (+/-)
            $(document).on('click', '.sk-qty-btn', function(e) {
                e.preventDefault();
                let $btn = $(this);
                let $input = $btn.siblings('.sk-qty-input');
                let val = parseInt($input.val()) || 0;
                let step = parseInt($input.attr('step')) || 1;
                let max = parseInt($input.attr('max'));

                if ($btn.hasClass('plus')) {
                    if (!max || val < max) {
                        $input.val(val + step).trigger('change');
                    }
                } else {
                    if (val > 1) {
                         $input.val(val - step).trigger('change');
                    }
                }
            });

            // Debounce for input change to avoid massive AJAX spam
            var debounceTimer;
            $(document).on('change', '.sk-qty-input', function() {
                 let key = $(this).data('key');
                 let qty = $(this).val();
                 clearTimeout(debounceTimer);
                 debounceTimer = setTimeout(() => {
                      SkincareCart.updateItemQty(key, qty);
                 }, 500);
            });

            // Coupon
            $(document).on('click', '#sk-apply-coupon', function(e) {
                e.preventDefault();
                SkincareCart.applyCoupon($('#sk-drawer-coupon').val());
            });

            // Policy Check
            $(document).on('change', '#sk-accept-policy', function() {
                var checked = $(this).is(':checked');

                // AJAX Sync to Session
                $.post(sk_vars.ajax_url, {
                    action: 'sk_set_policy_status',
                    status: checked,
                    nonce: sk_cart_vars.nonce
                });

                if(checked) {
                    $('#sk-checkout-btn').removeClass('disabled');
                } else {
                    $('#sk-checkout-btn').addClass('disabled');
                }
            });

            $(document).on('click', '#sk-checkout-btn', function(e) {
                if($(this).hasClass('disabled')) {
                    e.preventDefault();
                    SkincareCart.showDrawerMessage('Debes aceptar las políticas para continuar.', 'error');
                }
            });
        },

        applyCoupon: function(code) {
             $.ajax({
                 url: sk_vars.ajax_url,
                 type: 'POST',
                 data: {
                     action: 'sk_apply_coupon',
                     nonce: sk_cart_vars.nonce,
                     coupon_code: code
                 },
                 success: (res) => {
                     if(res.success) {
                         this.showDrawerMessage(res.data.message, 'success');
                         $(document.body).trigger('wc_update_cart');
                     } else {
                         this.showDrawerMessage(res.data ? res.data.message : 'Error', 'error');
                     }
                 }
             });
        },

        updateItemQty: function(key, qty) {
             $('.sk-drawer-content').addClass('loading');

             $.post(sk_vars.ajax_url, {
                 action: 'sk_update_cart_item',
                 cart_item_key: key,
                 qty: qty,
                 nonce: sk_cart_vars.nonce
             }, (res) => {
                 $('.sk-drawer-content').removeClass('loading');
                 if(res.success) {
                      $(document.body).trigger('wc_update_cart');
                 }
             });
        },

        showDrawerMessage: function(message, type) {
            var $feedback = $('.sk-drawer-feedback');
            $feedback.removeClass('is-success is-error').addClass('is-' + type).text(message).show();
            setTimeout(() => { $feedback.fadeOut(); }, 4000);
        },

        openDrawer: function() {
            $('#sk-cart-drawer').addClass('open');
            $('body').addClass('sk-drawer-open');
        },

        closeDrawer: function() {
            $('#sk-cart-drawer').removeClass('open');
            $('body').removeClass('sk-drawer-open');
        },

        updateCart: function() {
            // Re-fetch global vars just in case localized script updated (rare, but good practice if page reloaded)
            if(typeof sk_cart_vars !== 'undefined') {
                 this.threshold = parseFloat(sk_cart_vars.free_shipping_threshold);
            }

            $.ajax({
                url: wc_cart_fragments_params.wc_ajax_url.toString().replace('%%endpoint%%', 'get_refreshed_fragments'),
                type: 'POST',
                success: (data) => {
                    if (data && data.fragments) {
                        const $miniCart = $(data.fragments['div.widget_shopping_cart_content']);
                        if ($miniCart.length) {
                            $('.sk-cart-items').html($miniCart.find('.woocommerce-mini-cart').html());

                            let subHtml = $miniCart.find('.total .amount').html();
                            // Fallback if subtotal is missing (e.g. empty cart)
                            $('.sk-cart-subtotal .amount').html(subHtml || this.formatMoney(0));

                            const count = $miniCart.find('.mini_cart_item').length;
                            $('.sk-cart-count').text(`(${count})`);

                            // Progress Bar Logic
                            let subtotalText = $miniCart.find('.total .amount').text();

                            // Advanced Parsing for Currency
                            // We need to be careful about currencies that use comma as decimal separator
                            // Ideally, we trust the clean float parse logic, or better, we ask server for raw subtotal
                            // But keeping JS parsing for now to match requirement "Don't reinvent everything"

                            let raw = subtotalText;
                            // Remove symbol if possible
                            if(this.currencySymbol) {
                                raw = raw.replace(this.currencySymbol, '');
                            }
                            raw = raw.trim();

                            // Heuristic: if last punctuation is comma, it's likely decimal in EUR/Spanish
                            // but this is fragile.
                            // Robust approach: remove all non-digits except last punctuation
                            // 1,234.56 -> 1234.56
                            // 1.234,56 -> 1234.56

                            // Strategy: Identify separator
                            // Regex match last [.,]
                            let match = raw.match(/[.,](?=[0-9]+$)/);
                            let val = 0;

                            if (match) {
                                let sep = match[0];
                                let clean = raw.replace(new RegExp('[^0-9' + (sep === '.' ? '\\.' : sep) + ']', 'g'), '');
                                if (sep === ',') clean = clean.replace(',', '.');
                                val = parseFloat(clean);
                            } else {
                                val = parseFloat(raw.replace(/[^0-9]/g, ''));
                            }

                            if (isNaN(val)) val = 0;

                            this.updateProgressBar(val);

                            if (count === 0) {
                                $('.sk-cart-empty').show();
                                $('.sk-cart-items, .sk-drawer-footer, .sk-shipping-progress-bar').hide();
                            } else {
                                $('.sk-cart-empty').hide();
                                $('.sk-cart-items, .sk-drawer-footer, .sk-shipping-progress-bar').show();
                            }
                        }
                    }
                }
            });
        },

        updateProgressBar: function(subtotal) {
            let percentage = (subtotal / this.threshold) * 100;
            if (percentage > 100) percentage = 100;

            $('.progress-fill').css('width', percentage + '%');

            let needed = this.threshold - subtotal;
            if (needed <= 0) {
                $('.sk-shipping-msg').html('¡Felicidades! Tienes <strong>Envío Gratis</strong>.');
            } else {
                $('.sk-shipping-msg').html(`¡Estás a <span class="amount-needed">${this.formatMoney(needed)}</span> de <strong>Envío Gratis</strong>!`);
            }
        },

        formatMoney: function(amount) {
            try {
                // Use Intl if supported
                return new Intl.NumberFormat(this.currencyCode === 'PEN' ? 'es-PE' : 'en-US', {
                    style: 'currency',
                    currency: this.currencyCode
                }).format(amount);
            } catch(e) {
                return this.currencySymbol + amount.toFixed(2);
            }
        }
    };

    $(document).ready(function() {
        SkincareCart.init();
    });

})(jQuery);
