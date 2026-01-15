<?php
namespace Skincare\SiteKit\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use \Elementor\Controls_Manager;

class Rewards_Actions extends Shortcode_Renderer {

	public function get_name() {
		return 'sk_rewards_actions';
	}

	public function get_title() {
		return __( 'Acciones de recompensas (ganar)', 'skincare-site-kit' );
	}

	public function get_icon() {
		return 'eicon-check-circle';
	}

	public function get_categories() {
		return [ 'general' ];
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Acciones para ganar', 'skincare-site-kit' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'icon',
			[
				'label' => __( 'Icono', 'skincare-site-kit' ),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-star',
					'library' => 'fa-solid',
				],
			]
		);

		$repeater->add_control(
			'title',
			[
				'label' => __( 'Título', 'skincare-site-kit' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Título de acción', 'skincare-site-kit' ),
			]
		);

		$repeater->add_control(
			'points',
			[
				'label' => __( 'Valor en puntos', 'skincare-site-kit' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( '50 puntos', 'skincare-site-kit' ),
			]
		);

		$repeater->add_control(
			'link',
			[
				'label' => __( 'Enlace de acción', 'skincare-site-kit' ),
				'type' => Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'skincare-site-kit' ),
				'default' => [
					'url' => '#',
				],
			]
		);

		$repeater->add_control(
			'button_text',
			[
				'label' => __( 'Texto del botón', 'skincare-site-kit' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Ir', 'skincare-site-kit' ),
			]
		);

		$this->add_control(
			'actions',
			[
				'label' => __( 'Lista de acciones', 'skincare-site-kit' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'title' => __( 'Seguir en Instagram', 'skincare-site-kit' ),
						'points' => '50 puntos',
						'button_text' => 'Seguir',
					],
					[
						'title' => __( 'Recomienda a un amigo', 'skincare-site-kit' ),
						'points' => '500 puntos',
						'button_text' => 'Referir',
					],
				],
				'title_field' => '{{{ title }}}',
			]
		);

		$this->add_control(
			'columns',
			[
				'label' => __( 'Columnas', 'skincare-site-kit' ),
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
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$columns = $settings['columns'];

		echo '<div class="sk-rewards-actions-grid" style="display: grid; grid-template-columns: repeat(' . esc_attr( $columns ) . ', 1fr); gap: 20px;">';

		foreach ( $settings['actions'] as $item ) {
			?>
			<div class="sk-reward-action-card" style="border: 1px solid #eee; padding: 20px; border-radius: 8px; text-align: center; display: flex; flex-direction: column; align-items: center;">
				<div class="sk-action-icon" style="font-size: 24px; color: #0F3062; margin-bottom: 10px;">
					<?php \Elementor\Icons_Manager::render_icon( $item['icon'], [ 'aria-hidden' => 'true' ] ); ?>
				</div>

				<h4 class="sk-action-title" style="margin: 10px 0; color: #0F3062; font-size: 16px;"><?php echo esc_html( $item['title'] ); ?></h4>

				<div class="sk-action-points" style="font-weight: bold; color: #E5757E; margin-bottom: 15px;">
					<?php echo esc_html( $item['points'] ); ?>
				</div>

				<?php if ( ! empty( $item['link']['url'] ) ) :
					$this->add_link_attributes( 'button-' . $item['_id'], $item['link'] );
					?>
					<a <?php echo $this->get_render_attribute_string( 'button-' . $item['_id'] ); ?> class="sk-action-btn button" style="background-color: transparent; border: 1px solid #0F3062; color: #0F3062; padding: 8px 20px; border-radius: 20px; cursor: pointer; text-decoration: none; display: inline-block; transition: all 0.3s;">
						<?php echo esc_html( $item['button_text'] ); ?>
					</a>
				<?php endif; ?>
			</div>
			<?php
		}

		echo '</div>';
	}
}
