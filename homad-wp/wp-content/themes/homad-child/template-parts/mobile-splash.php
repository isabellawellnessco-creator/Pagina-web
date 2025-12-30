<?php
/**
 * Mobile Splash Screen Template Part.
 * Loaded via footer injection if enabled in settings.
 */

$title    = get_option('homad_splash_title', 'Welcome');
$subtitle = get_option('homad_splash_subtitle', 'Experience Homad.');
$image    = get_option('homad_splash_image', ''); // URL

?>
<div id="homad-splash-screen" class="homad-splash">
    <div class="homad-splash__bg" style="background-image: url('<?php echo esc_url($image); ?>');"></div>
    <div class="homad-splash__content">
        <?php if($title): ?><h1><?php echo esc_html($title); ?></h1><?php endif; ?>
        <?php if($subtitle): ?><p><?php echo esc_html($subtitle); ?></p><?php endif; ?>
        <button id="homad-splash-btn" class="homad-btn homad-btn--primary">Get Started</button>
    </div>
</div>
