<?php
/**
 * Hero section template part.
 *
 * @package SkincareThemeChild
 */

$title    = $args['title'] ?? '';
$subtitle = $args['subtitle'] ?? '';
$text     = $args['text'] ?? '';
$cta      = $args['cta'] ?? [];
$style    = $args['style'] ?? '';
?>

<section class="sk-hero-section"<?php echo $style ? ' style="' . esc_attr( $style ) . '"' : ''; ?>>
	<div class="sk-hero-content">
		<?php if ( $subtitle ) : ?>
			<p class="sk-hero-subtitle"><?php echo esc_html( $subtitle ); ?></p>
		<?php endif; ?>

		<?php if ( $title ) : ?>
			<h1 class="sk-hero-title"><?php echo esc_html( $title ); ?></h1>
		<?php endif; ?>

		<?php if ( $text ) : ?>
			<p class="sk-hero-text"><?php echo esc_html( $text ); ?></p>
		<?php endif; ?>

		<?php if ( ! empty( $cta['label'] ) && ! empty( $cta['url'] ) ) : ?>
			<a class="btn sk-btn" href="<?php echo esc_url( $cta['url'] ); ?>">
				<?php echo esc_html( $cta['label'] ); ?>
			</a>
		<?php endif; ?>
	</div>
</section>
