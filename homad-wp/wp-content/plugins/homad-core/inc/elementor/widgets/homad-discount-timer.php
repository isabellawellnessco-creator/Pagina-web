<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Homad_Discount_Timer_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'homad_discount_timer';
	}

	public function get_title() {
		return esc_html__( 'Homad Discount Timer', 'homad-core' );
	}

	public function get_icon() {
		return 'eicon-countdown';
	}

	public function get_categories() {
		return [ 'homad-core' ];
	}

	protected function register_controls() {
		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'Settings', 'homad-core' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

        $this->add_control(
			'expired_message',
			[
				'label' => esc_html__( 'Expired Message', 'homad-core' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Offer Expired', 'homad-core' ),
			]
		);

		$this->end_controls_section();

        // Style
        $this->start_controls_section(
			'style_section',
			[
				'label' => esc_html__( 'Typography', 'homad-core' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

        $this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'timer_typography',
				'selector' => '{{WRAPPER}} .homad-timer-digits',
			]
		);

        $this->add_control(
			'timer_color',
			[
				'label' => esc_html__( 'Color', 'homad-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .homad-timer-digits' => 'color: {{VALUE}}',
				],
			]
		);

        $this->end_controls_section();
	}

	protected function render() {
        global $product;
        if ( ! $product ) return;

        $deadline = get_post_meta($product->get_id(), '_homad_discount_deadline', true);
        if ( ! $deadline ) return;

        // Convert to JS timestamp
        $end_time = strtotime($deadline) * 1000;
        $now = time() * 1000;

        if ($now > $end_time) {
            $settings = $this->get_settings_for_display();
            echo '<div class="homad-timer-expired">' . esc_html($settings['expired_message']) . '</div>';
            return;
        }

        $uniqid = 'timer_' . uniqid();

        echo '<div class="homad-discount-timer" id="' . $uniqid . '">';
        echo '<span class="homad-timer-label">Offer ends in: </span>';
        echo '<span class="homad-timer-digits">Loading...</span>';
        echo '</div>';

        // Inline JS for self-contained widget
        ?>
        <script>
        (function($){
            var countDownDate = <?php echo $end_time; ?>;
            var x = setInterval(function() {
                var now = new Date().getTime();
                var distance = countDownDate - now;

                if (distance < 0) {
                    clearInterval(x);
                    $('#<?php echo $uniqid; ?>').html('<?php echo esc_js($this->get_settings('expired_message')); ?>');
                    return;
                }

                var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                $('#<?php echo $uniqid; ?> .homad-timer-digits').text(days + "d " + hours + "h " + minutes + "m " + seconds + "s ");
            }, 1000);
        })(jQuery);
        </script>
        <?php
	}
}
