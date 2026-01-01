(function($) {
    'use strict';

    const SkincareCart = {
        init: function() {
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
                            <h3>Your Bag <span class="sk-cart-count">(0)</span></h3>
                            <button class="sk-drawer-close">&times;</button>
                        </div>
                        <div class="sk-drawer-body">
                            <div class="sk-cart-items"></div>
                            <div class="sk-cart-empty" style="display:none;">
                                <p>Your bag is empty</p>
                                <a href="/tienda/" class="btn btn-primary">Start Shopping</a>
                            </div>
                        </div>
                        <div class="sk-drawer-footer">
                            <div class="sk-cart-subtotal">
                                <span>Subtotal:</span>
                                <span class="amount">$0.00</span>
                            </div>
                            <a href="/carrito/" class="btn btn-secondary btn-block">View Cart</a>
                            <a href="/finalizar-compra/" class="btn btn-primary btn-block">Checkout</a>
                        </div>
                    </div>
                </div>
            `);
        },

        bindEvents: function() {
            $(document).on('click', 'a[href="#cart-drawer"]', (e) => {
                e.preventDefault();
                this.openDrawer();
            });

            $(document).on('click', '.sk-drawer-close, .sk-drawer-overlay', () => {
                this.closeDrawer();
            });

            $(document.body).on('added_to_cart', () => {
                this.updateCart();
                this.openDrawer();
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
                        // Update fragments logic would go here perfectly in a full woo environment
                        // For now we simulate structure updates if widget content is passed
                        // Real implementation relies on WooCommerce localized scripts

                        // Example simulation of parsing the mini cart widget HTML if available
                        const $miniCart = $(data.fragments['div.widget_shopping_cart_content']);
                        if ($miniCart.length) {
                            $('.sk-cart-items').html($miniCart.find('.woocommerce-mini-cart').html());
                            $('.sk-cart-subtotal .amount').html($miniCart.find('.total .amount').html());

                            const count = $miniCart.find('.mini_cart_item').length;
                            $('.sk-cart-count').text(`(${count})`);

                            if (count === 0) {
                                $('.sk-cart-empty').show();
                                $('.sk-cart-items, .sk-drawer-footer').hide();
                            } else {
                                $('.sk-cart-empty').hide();
                                $('.sk-cart-items, .sk-drawer-footer').show();
                            }
                        }
                    }
                }
            });
        }
    };

    $(document).ready(function() {
        SkincareCart.init();
    });

})(jQuery);
