<?php
/**
 * Email Header - Custom Homad Style
 *
 * @package Homad_Child
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

$logo_url = get_option('homad_logo_url');
$primary_color = get_option('homad_primary_color', '#333');

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=<?php bloginfo( 'charset' ); ?>" />
        <title><?php echo get_bloginfo( 'name', 'display' ); ?></title>
        <style>
            body { background-color: #f7f7f7; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; }
            #wrapper { background-color: #ffffff; margin: 20px auto; max-width: 600px; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
            #header { background-color: <?php echo esc_attr($primary_color); ?>; padding: 30px; text-align: center; }
            #header img { max-height: 50px; }
            #body_content { padding: 40px; color: #555; line-height: 1.6; }
            h1, h2, h3 { color: #333; font-weight: 600; }
            a { color: <?php echo esc_attr($primary_color); ?>; text-decoration: none; }
            #footer { text-align: center; padding: 20px; font-size: 12px; color: #999; }
        </style>
    </head>
    <body <?php echo is_rtl() ? 'rightmargin' : 'leftmargin'; ?>="0" marginwidth="0" topmargin="0" marginheight="0" offset="0">
        <div id="wrapper">
            <div id="header">
                <?php if($logo_url): ?>
                    <img src="<?php echo esc_url($logo_url); ?>" alt="<?php echo get_bloginfo( 'name', 'display' ); ?>" />
                <?php else: ?>
                    <h1 style="color:#fff; margin:0;"><?php echo get_bloginfo( 'name', 'display' ); ?></h1>
                <?php endif; ?>
            </div>
            <div id="body_content">
