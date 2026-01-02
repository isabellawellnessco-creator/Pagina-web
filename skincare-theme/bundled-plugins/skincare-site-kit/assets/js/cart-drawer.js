(function($) {
    'use strict';

    const SkincareCart = {
        init: function() {
            if(typeof sk_cart_vars === 'undefined') return;

            this.threshold = parseInt(sk_cart_vars.free_shipping_threshold);
            this.createDrawer();
            this.bindEvents();
        },

        createDrawer: function() {
            if ($('#sk-cart-drawer').length) return;

            // Structure: Left (Cart) + Right (Upsell Sidebar if needed, but standard is usually bottom or integrated)
            // For "Skin Cupid" style, it's often a single wide drawer or split.
            // Let's implement a standard right slide-over with Upsell at bottom or top.

            $('body').append(`
                <div id="sk-cart-drawer" class="sk-drawer">
                    <div class="sk-drawer-overlay"></div>
                    <div class="sk-drawer-content">

                        <div class="sk-drawer-header">
                            <h3>Tu Bolsa <span class="sk-cart-count">(0)</span></h3>
                            <button class="sk-drawer-close">&times;</button>
                        </div>

                        <div class="sk-shipping-progress-bar">
                             <p class="sk-shipping-msg">¡Estás a <span class="amount-needed">£${this.threshold}</span> de <strong>Envío Gratis</strong>!</p>
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
                                    ${sk_cart_vars.upsell_product_html}
                                </div>
                            </div>
                        </div>

                        <div class="sk-drawer-footer">
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

                            <div class="sk-cart-subtotal">
                                <span>Subtotal:</span>
                                <span class="amount">$0.00</span>
                            </div>

                            <a href="/finalizar-compra/" class="btn sk-btn-checkout btn-block">Pagar Ahora</a>
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

            $(document.body).on('added_to_cart removed_from_cart updated_cart_totals', () => {
                this.updateCart();
                this.openDrawer();
            });

            // Coupon
            $(document).on('click', '#sk-apply-coupon', function(e) {
                e.preventDefault();
                var code = $('#sk-drawer-coupon').val();
                $.post(sk_vars.ajax_url, {
                    action: 'sk_apply_coupon',
                    coupon_code: code
                }, function(res) {
                    alert(res.data.message);
                    $(document.body).trigger('wc_update_cart');
                });
            });

            // Initial Load
            this.updateCart();
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
            $.ajax({
                url: wc_cart_fragments_params.wc_ajax_url.toString().replace('%%endpoint%%', 'get_refreshed_fragments'),
                type: 'POST',
                success: (data) => {
                    if (data && data.fragments) {
                        const $miniCart = $(data.fragments['div.widget_shopping_cart_content']);
                        if ($miniCart.length) {
                            $('.sk-cart-items').html($miniCart.find('.woocommerce-mini-cart').html());
                            $('.sk-cart-subtotal .amount').html($miniCart.find('.total .amount').html());

                            const count = $miniCart.find('.mini_cart_item').length;
                            $('.sk-cart-count').text(`(${count})`);

                            // Progress Bar Logic
                            // Parse subtotal amount (remove currency symbols)
                            let subtotalHtml = $miniCart.find('.total .amount').text();
                            let subtotal = parseFloat(subtotalHtml.replace(/[^0-9.]/g, ''));

                            this.updateProgressBar(subtotal);

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
                $('.sk-shipping-msg').html(`¡Estás a <span class="amount-needed">£${needed.toFixed(2)}</span> de <strong>Envío Gratis</strong>!`);
            }
        }
    };

    $(document).ready(function() {
        SkincareCart.init();
    });

})(jQuery);
