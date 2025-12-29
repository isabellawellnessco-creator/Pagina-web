<?php
/**
 * Category chips section.
 */
?>
<section class="homad-category-chips">
    <div class="homad-container">
        <header class="homad-section-header">
            <div>
                <h2><?php echo esc_html__('Explorar categorías', 'homad'); ?></h2>
                <p><?php echo esc_html__('Acceso rápido a lo más buscado.', 'homad'); ?></p>
            </div>
        </header>
        <ul class="homad-chip-list" role="list">
            <li><button class="homad-chip is-active" type="button"><?php echo esc_html__('Roperos', 'homad'); ?></button></li>
            <li><button class="homad-chip" type="button"><?php echo esc_html__('Baño', 'homad'); ?></button></li>
            <li><button class="homad-chip" type="button"><?php echo esc_html__('Iluminación', 'homad'); ?></button></li>
            <li><button class="homad-chip" type="button"><?php echo esc_html__('Cocina', 'homad'); ?></button></li>
            <li><button class="homad-chip" type="button"><?php echo esc_html__('Decor', 'homad'); ?></button></li>
        </ul>
    </div>
</section>
