<?php
namespace Skincare\SiteKit\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use \Elementor\Widget_Base;
use \Elementor\Controls_Manager;
use \Skincare\SiteKit\Admin\Rewards_Master;

/**
 * Rewards Page Sections
 *
 * Container class for all Rewards page specific sections to match reference HTML.
 */

class Sk_Rewards_Hero_Section extends Widget_Base {
	public function get_name() { return 'sk_rewards_hero_section'; }
	public function get_title() { return __( 'Rewards: Hero Section', 'skincare' ); }
	public function get_icon() { return 'eicon-banner'; }
	public function get_categories() { return [ 'skincare-rewards' ]; }

	protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			[ 'label' => __( 'Content', 'skincare' ), ]
		);
		$this->add_control(
			'title',
			[
				'label' => __( 'Title', 'skincare' ),
				'type' => Controls_Manager::TEXT,
				'default' => 'Skin Cupid Rewards',
			]
		);
		$this->end_controls_section();
	}

	protected function render() {
		$user_id = get_current_user_id();
		$points = 0;
		$is_logged_in = is_user_logged_in();

		if ( $is_logged_in && class_exists( '\Skincare\SiteKit\Admin\Rewards_Master' ) ) {
			$points = Rewards_Master::get_user_balance( $user_id );
		}

		?>
		<div class="sk-rewards-hero">
			<div class="sk-rewards-hero__bg">
				<!-- Background image handled via CSS or added here if dynamic -->
			</div>
			<div class="sk-rewards-hero__content">
				<h1 class="sk-rewards-hero__title"><?php echo esc_html( $this->get_settings_for_display( 'title' ) ); ?></h1>
				<p class="sk-rewards-hero__subtitle"><?php esc_html_e( 'Join the club & earn points every time you shop', 'skincare' ); ?></p>

				<?php if ( $is_logged_in ) : ?>
					<div class="sk-rewards-hero__status">
						<span class="sk-rewards-hero__points"><?php echo esc_html( $points ); ?></span>
						<span class="sk-rewards-hero__label"><?php esc_html_e( 'Cupid Points', 'skincare' ); ?></span>
					</div>
				<?php else : ?>
					<div class="sk-rewards-hero__actions">
						<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="sk-btn sk-btn--primary"><?php esc_html_e( 'Join Now', 'skincare' ); ?></a>
						<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="sk-btn sk-btn--outline"><?php esc_html_e( 'Sign In', 'skincare' ); ?></a>
					</div>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}
}

class Sk_Rewards_How_It_Works_Section extends Widget_Base {
	public function get_name() { return 'sk_rewards_how_it_works'; }
	public function get_title() { return __( 'Rewards: How it Works', 'skincare' ); }
	public function get_icon() { return 'eicon-flow'; }
	public function get_categories() { return [ 'skincare-rewards' ]; }

	protected function render() {
		?>
		<div class="sk-rewards-steps">
			<h2 class="sk-section-title"><?php esc_html_e( 'How it works', 'skincare' ); ?></h2>
			<div class="sk-rewards-steps__grid">
				<div class="sk-rewards-step">
					<div class="sk-rewards-step__icon">1</div>
					<h3><?php esc_html_e( 'Join', 'skincare' ); ?></h3>
					<p><?php esc_html_e( 'Create an account to start earning points.', 'skincare' ); ?></p>
				</div>
				<div class="sk-rewards-step">
					<div class="sk-rewards-step__icon">2</div>
					<h3><?php esc_html_e( 'Earn', 'skincare' ); ?></h3>
					<p><?php esc_html_e( 'Earn points every time you shop.', 'skincare' ); ?></p>
				</div>
				<div class="sk-rewards-step">
					<div class="sk-rewards-step__icon">3</div>
					<h3><?php esc_html_e( 'Redeem', 'skincare' ); ?></h3>
					<p><?php esc_html_e( 'Use points for exclusive discounts.', 'skincare' ); ?></p>
				</div>
			</div>
		</div>
		<?php
	}
}

class Sk_Rewards_VIP_Tiers_Section extends Widget_Base {
	public function get_name() { return 'sk_rewards_vip_tiers'; }
	public function get_title() { return __( 'Rewards: VIP Tiers', 'skincare' ); }
	public function get_icon() { return 'eicon-table'; }
	public function get_categories() { return [ 'skincare-rewards' ]; }

	protected function render() {
		?>
		<div class="sk-rewards-tiers">
			<h2 class="sk-section-title"><?php esc_html_e( 'VIP Tiers', 'skincare' ); ?></h2>
			<div class="sk-rewards-tiers__table">
				<!-- Header -->
				<div class="sk-tier-header">
					<div class="sk-tier-col"></div>
					<div class="sk-tier-col">
						<h3><?php esc_html_e( 'Cupids', 'skincare' ); ?></h3>
						<span><?php esc_html_e( '0 - 199 Points', 'skincare' ); ?></span>
					</div>
					<div class="sk-tier-col">
						<h3><?php esc_html_e( 'Cherubs', 'skincare' ); ?></h3>
						<span><?php esc_html_e( '200 - 499 Points', 'skincare' ); ?></span>
					</div>
					<div class="sk-tier-col">
						<h3><?php esc_html_e( 'Angels', 'skincare' ); ?></h3>
						<span><?php esc_html_e( '500+ Points', 'skincare' ); ?></span>
					</div>
				</div>
				<!-- Row 1 -->
				<div class="sk-tier-row">
					<div class="sk-tier-col sk-tier-label"><?php esc_html_e( 'Points per £1', 'skincare' ); ?></div>
					<div class="sk-tier-col">5</div>
					<div class="sk-tier-col">5</div>
					<div class="sk-tier-col">5</div>
				</div>
				<!-- Row 2 -->
				<div class="sk-tier-row">
					<div class="sk-tier-col sk-tier-label"><?php esc_html_e( 'Birthday Bonus', 'skincare' ); ?></div>
					<div class="sk-tier-col">✓</div>
					<div class="sk-tier-col">✓</div>
					<div class="sk-tier-col">✓</div>
				</div>
			</div>
		</div>
		<?php
	}
}

class Sk_Rewards_Ways_To_Earn_Section extends Widget_Base {
	public function get_name() { return 'sk_rewards_ways_to_earn'; }
	public function get_title() { return __( 'Rewards: Ways to Earn', 'skincare' ); }
	public function get_icon() { return 'eicon-star-o'; }
	public function get_categories() { return [ 'skincare-rewards' ]; }

	protected function render() {
		?>
		<div class="sk-rewards-earn">
			<h2 class="sk-section-title"><?php esc_html_e( 'Ways to earn', 'skincare' ); ?></h2>
			<div class="sk-rewards-earn__grid">
				<div class="sk-earn-card">
					<span class="sk-earn-icon">🛍️</span>
					<h4><?php esc_html_e( 'Place an order', 'skincare' ); ?></h4>
					<p><?php esc_html_e( '5 Points for every £1 spent', 'skincare' ); ?></p>
				</div>
				<div class="sk-earn-card">
					<span class="sk-earn-icon">📱</span>
					<h4><?php esc_html_e( 'Follow on Instagram', 'skincare' ); ?></h4>
					<p><?php esc_html_e( '50 Points', 'skincare' ); ?></p>
				</div>
				<div class="sk-earn-card">
					<span class="sk-earn-icon">🎂</span>
					<h4><?php esc_html_e( 'Celebrate a birthday', 'skincare' ); ?></h4>
					<p><?php esc_html_e( '200 Points', 'skincare' ); ?></p>
				</div>
			</div>
		</div>
		<?php
	}
}
