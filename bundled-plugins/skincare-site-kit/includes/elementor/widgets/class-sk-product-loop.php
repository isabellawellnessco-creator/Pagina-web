<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Skincare_Product_Loop_Widget extends \Elementor\Widget_Base {

	public function get_name() { return 'sk_product_loop'; }
	public function get_title() { return __( 'SK Product Loop', 'skincare' ); }
	public function get_icon() { return 'eicon-products'; }
	public function get_categories() { return [ 'general' ]; }

	protected function register_controls() {
        $this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Query', 'skincare' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

        $this->add_control(
			'posts_per_page',
			[
				'label' => __( 'Count', 'skincare' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'default' => 4,
			]
		);

		$this->end_controls_section();
    }

	protected function render() {
		$settings = $this->get_settings_for_display();

        $args = [
            'post_type' => 'product',
            'posts_per_page' => $settings['posts_per_page'],
        ];

        $query = new WP_Query($args);

        if($query->have_posts()) : ?>
            <div class="sk-product-grid">
                <?php while($query->have_posts()) : $query->the_post();
                    global $product;
                ?>
                    <div class="sk-product-card sk-card">
                        <a href="<?php the_permalink(); ?>" class="sk-card-img">
                            <?php echo woocommerce_get_product_thumbnail(); ?>
                        </a>
                        <div class="sk-card-body">
                            <h3 class="sk-card-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h3>
                            <div class="sk-card-price"><?php echo $product->get_price_html(); ?></div>
                            <?php woocommerce_template_loop_add_to_cart(); ?>
                        </div>
                    </div>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>
        <?php else: ?>
            <p><?php _e('No products found.', 'skincare'); ?></p>
        <?php endif;
    }
}
