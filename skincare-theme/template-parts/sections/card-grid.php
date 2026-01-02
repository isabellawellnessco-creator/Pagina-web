<?php
/**
 * Card grid section template part.
 *
 * @package SkincareThemeChild
 */

$title = $args['title'] ?? '';
$intro = $args['intro'] ?? '';
$cards = $args['cards'] ?? [];
?>

<section class="sk-card-grid">
	<div class="sk-container">
		<?php if ( $title ) : ?>
			<h2 class="sk-section-title"><?php echo esc_html( $title ); ?></h2>
		<?php endif; ?>

		<?php if ( $intro ) : ?>
			<p class="sk-section-intro"><?php echo esc_html( $intro ); ?></p>
		<?php endif; ?>

		<div class="sk-card-grid__items">
			<?php foreach ( $cards as $card ) : ?>
				<article class="sk-card">
					<?php if ( ! empty( $card['title'] ) ) : ?>
						<h3 class="sk-card__title"><?php echo esc_html( $card['title'] ); ?></h3>
					<?php endif; ?>

					<?php if ( ! empty( $card['text'] ) ) : ?>
						<p class="sk-card__text"><?php echo esc_html( $card['text'] ); ?></p>
					<?php endif; ?>

					<?php if ( ! empty( $card['link_url'] ) && ! empty( $card['link_label'] ) ) : ?>
						<a class="sk-card__link" href="<?php echo esc_url( $card['link_url'] ); ?>">
							<?php echo esc_html( $card['link_label'] ); ?>
						</a>
					<?php endif; ?>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
