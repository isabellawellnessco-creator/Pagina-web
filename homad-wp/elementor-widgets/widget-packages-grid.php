<?php
if ( ! defined( 'ABSPATH' ) ) {
    return; // Using return instead of exit
}

/**
 * Elementor Packages Grid Widget.
 *
 * @since 1.0.0
 */
class Homad_Packages_Grid_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'homad_packages_grid';
    }

    public function get_title() {
        return __( 'Homad Packages Grid', 'homad' );
    }

    public function get_icon() {
        return 'eicon-posts-grid';
    }

    public function get_categories() {
        return [ 'general' ];
    }

    protected function _register_controls() {
        $this->start_controls_section(
            'content_section',
            [
                'label' => __( 'Content', 'homad' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'posts_per_page',
            [
                'label' => __( 'Number of Packages', 'homad' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 10,
                'step' => 1,
                'default' => 3,
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        // Render the widget output on the frontend
        $settings = $this->get_settings_for_display();
        $args = [
            'post_type' => 'package',
            'posts_per_page' => $settings['posts_per_page'],
        ];
        $packages_query = new \WP_Query( $args );

        if ( $packages_query->have_posts() ) {
            echo '<div class="packages-grid">';
            while ( $packages_query->have_posts() ) {
                $packages_query->the_post();
                get_template_part( 'template-parts/package-card' );
            }
            echo '</div>';
            wp_reset_postdata();
        } else {
            echo '<p>' . __( 'No packages found.', 'homad' ) . '</p>';
        }
    }
}
