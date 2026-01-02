<?php
/**
 * FAQ section template part.
 *
 * @package SkincareThemeChild
 */

$title = $args['title'] ?? '';
$faqs  = $args['faqs'] ?? [];
?>

<section class="sk-faq-section">
	<div class="sk-container">
		<?php if ( $title ) : ?>
			<h2 class="sk-section-title"><?php echo esc_html( $title ); ?></h2>
		<?php endif; ?>

		<div class="sk-faq-list">
			<?php foreach ( $faqs as $faq ) : ?>
				<details class="sk-faq-item">
					<?php if ( ! empty( $faq['question'] ) ) : ?>
						<summary class="sk-faq-question"><?php echo esc_html( $faq['question'] ); ?></summary>
					<?php endif; ?>
					<?php if ( ! empty( $faq['answer'] ) ) : ?>
						<div class="sk-faq-answer">
							<p><?php echo esc_html( $faq['answer'] ); ?></p>
						</div>
					<?php endif; ?>
				</details>
			<?php endforeach; ?>
		</div>
	</div>
</section>
