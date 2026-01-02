<?php
namespace Skincare\SiteKit\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use \Elementor\Widget_Base;
use \Elementor\Controls_Manager;
use \Elementor\Group_Control_Image_Size;
use \Elementor\Utils;

class Rewards_Catalog extends Widget_Base {

	public function get_name() {
		return 'sk_rewards_catalog';
	}

	public function get_title() {
		return __( 'SK Rewards Catalog', 'skincare-site-kit' );
	}

	public function get_icon() {
		return 'eicon-products';
	}

	public function get_categories() {
		return [ 'general' ];
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Rewards Items', 'skincare-site-kit' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'image',
			[
				'label' => __( 'Image', 'skincare-site-kit' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);

		$repeater->add_control(
			'title',
			[
				'label' => __( 'Title', 'skincare-site-kit' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Reward Item', 'skincare-site-kit' ),
			]
		);

		$repeater->add_control(
			'points_cost',
			[
				'label' => __( 'Points Cost', 'skincare-site-kit' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 500,
			]
		);

		$repeater->add_control(
			'button_text',
			[
				'label' => __( 'Button Text', 'skincare-site-kit' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Redeem', 'skincare-site-kit' ),
			]
		);

		$this->add_control(
			'rewards',
			[
				'label' => __( 'Rewards List', 'skincare-site-kit' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'title' => __( '£5 Off Coupon', 'skincare-site-kit' ),
						'points_cost' => 500,
					],
					[
						'title' => __( '£10 Off Coupon', 'skincare-site-kit' ),
						'points_cost' => 1000,
					],
				],
				'title_field' => '{{{ title }}}',
			]
		);

		$this->add_control(
			'columns',
			[
				'label' => __( 'Columns', 'skincare-site-kit' ),
				'type' => Controls_Manager::SELECT,
				'default' => '3',
				'options' => [
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
				],
			]
		);

		$this->end_controls_section();

		// Style Tab
		$this->start_controls_section(
			'style_section',
			[
				'label' => __( 'Style', 'skincare-site-kit' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => __( 'Title Color', 'skincare-site-kit' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .sk-reward-title' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'points_color',
			[
				'label' => __( 'Points Color', 'skincare-site-kit' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .sk-reward-points' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$columns = $settings['columns'];

		echo '<div class="sk-rewards-catalog-grid" style="display: grid; grid-template-columns: repeat(' . esc_attr( $columns ) . ', 1fr); gap: 20px;">';

		foreach ( $settings['rewards'] as $item ) {
			$image_url = $item['image']['url'];
			?>
			<div class="sk-reward-item" style="border: 1px solid #eee; padding: 15px; border-radius: 8px; text-align: center;">
				<?php if ( $image_url ) : ?>
					<div class="sk-reward-image" style="margin-bottom: 10px;">
						<img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $item['title'] ); ?>" style="max-width: 100%; height: auto; border-radius: 4px;">
					</div>
				<?php endif; ?>

				<h4 class="sk-reward-title" style="margin: 10px 0; color: #0F3062;"><?php echo esc_html( $item['title'] ); ?></h4>

				<div class="sk-reward-points" style="font-weight: bold; color: #E5757E; margin-bottom: 15px;">
					<?php echo esc_html( $item['points_cost'] ); ?> <?php _e( 'Points', 'skincare-site-kit' ); ?>
				</div>

				<button class="sk-reward-redeem-btn button" style="background-color: #0F3062; color: #fff; border: none; padding: 8px 20px; border-radius: 20px; cursor: pointer;">
					<?php echo esc_html( $item['button_text'] ); ?>
				</button>
			</div>
			<?php
		}

		echo '</div>';
	}
}
