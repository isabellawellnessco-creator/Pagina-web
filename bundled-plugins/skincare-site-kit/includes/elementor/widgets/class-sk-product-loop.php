<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Skincare_Product_Loop_Widget extends \Elementor\Widget_Base {

	public function get_name() { return 'sk_product_loop'; }
	public function get_title() { return __( 'Loop de Productos', 'skincare-site-kit' ); }
	public function get_icon() { return 'eicon-products'; }
	public function get_categories() { return [ 'skincare-widgets' ]; }

	protected function register_controls() {
        $this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Consulta', 'skincare-site-kit' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

        $this->add_control(
			'posts_per_page',
			[
				'label' => __( 'Cantidad', 'skincare-site-kit' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'default' => 4,
			]
		);

        $this->add_control(
			'columns',
			[
				'label' => __( 'Columnas (Desktop)', 'skincare-site-kit' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'default' => 4,
                'min' => 1, 'max' => 6
			]
		);

		$this->end_controls_section();
    }

	protected function render() {
		$settings = $this->get_settings_for_display();

        $args = [
            'post_type' => 'product',
            'posts_per_page' => $settings['posts_per_page'],
            'post_status' => 'publish'
        ];

        $query = new WP_Query($args);

        // Grid CSS dynamic
        $cols = $settings['columns'];
        $style = "grid-template-columns: repeat({$cols}, 1fr);";

        if($query->have_posts()) : ?>
            <div class="sk-product-grid" style="<?php echo esc_attr($style); ?>">
                <?php while($query->have_posts()) : $query->the_post();
                    global $product;
                ?>
                    <div class="sk-product-card sk-card">
                        <div class="sk-card-badges">
                            <?php if ( $product->is_on_sale() ) : ?>
                                <span class="badge sale"><?php _e('Oferta', 'skincare-site-kit'); ?></span>
                            <?php endif; ?>
                            <?php if ( !$product->is_in_stock() ) : ?>
                                <span class="badge out"><?php _e('Agotado', 'skincare-site-kit'); ?></span>
                            <?php endif; ?>
                        </div>

                        <a href="<?php the_permalink(); ?>" class="sk-card-img">
                            <?php echo $product->get_image( 'woocommerce_thumbnail' ); ?>
                        </a>

                        <div class="sk-card-body">
                            <div class="sk-card-cat">
                                <?php echo wc_get_product_category_list($product->get_id(), ', '); ?>
                            </div>
                            <h3 class="sk-card-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h3>
                            <div class="sk-card-price"><?php echo $product->get_price_html(); ?></div>

                            <div class="sk-card-actions">
                                <?php if($product->is_in_stock()): ?>
                                    <a href="?add-to-cart=<?php echo $product->get_id(); ?>" data-product_id="<?php echo $product->get_id(); ?>" class="btn btn-sm btn-primary add_to_cart_button ajax_add_to_cart">
                                        <?php _e('Añadir', 'skincare-site-kit'); ?>
                                    </a>
                                <?php else: ?>
                                    <a href="<?php the_permalink(); ?>" class="btn btn-sm btn-secondary">
                                        <?php _e('Ver', 'skincare-site-kit'); ?>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>

            <style>
                .sk-product-grid { display: grid; gap: 20px; }
                .sk-product-card { position: relative; border: 1px solid transparent; transition: var(--transition-base); }
                .sk-product-card:hover { border-color: var(--c-border); box-shadow: var(--shadow-md); border-radius: 8px; }

                .sk-card-img img { width: 100%; height: auto; border-radius: 8px 8px 0 0; }
                .sk-card-body { padding: 15px; text-align: center; }

                .sk-card-cat { font-size: 0.75rem; color: var(--c-text-light); margin-bottom: 5px; text-transform: uppercase; }
                .sk-card-title { font-size: 1rem; margin-bottom: 5px; font-weight: 500; min-height: 48px; }
                .sk-card-price { color: var(--c-accent); font-weight: 600; margin-bottom: 10px; }

                .sk-card-badges { position: absolute; top: 10px; left: 10px; z-index: 2; display: flex; flex-direction: column; gap: 5px; }
                .badge { padding: 3px 8px; border-radius: 4px; font-size: 0.7rem; font-weight: 600; color: #fff; text-transform: uppercase; }
                .badge.sale { background: var(--c-accent); }
                .badge.out { background: var(--c-text-light); }

                @media(max-width: 768px) {
                    .sk-product-grid { grid-template-columns: repeat(2, 1fr) !important; gap: 10px; }
                    .sk-card-body { padding: 10px; }
                    .sk-card-title { font-size: 0.9rem; min-height: auto; }
                }
            </style>
        <?php else: ?>
            <p><?php _e('No se encontraron productos.', 'skincare-site-kit'); ?></p>
        <?php endif;
    }
}
