<?php
/**
 * Email Footer - Custom Homad Style
 *
 * @package Homad_Child
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
$footer_text = get_option('homad_footer_text', 'Thank you for shopping with us.');
?>
            </div>
            <div id="footer">
                <p><?php echo wp_kses_post($footer_text); ?></p>
                <p>&copy; <?php echo date('Y'); ?> <?php echo get_bloginfo( 'name', 'display' ); ?>. All rights reserved.</p>
            </div>
        </div>
    </body>
</html>
