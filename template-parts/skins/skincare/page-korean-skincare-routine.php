<?php
/**
 * Template Name: Korean Skincare Routine
 * Skin: Skincare
 */

get_header();
?>

<div class="homad-skin-page page-routine">
    <div class="hero-section" style="background-color: var(--skin-color-bg-pink); padding: 60px 20px; text-align: center;">
        <h1>The 10-Step Korean Skincare Routine</h1>
        <p>Your guide to glowing glass skin.</p>
    </div>

    <div class="container">
        <div class="routine-steps">
            <!-- Step 1 -->
            <div class="routine-step">
                <div class="step-num">01</div>
                <h3>Oil Cleanser</h3>
                <p>Dissolve makeup and impurities.</p>
                <a href="/shop?cat=cleansers" class="link">Shop Oil Cleansers &rarr;</a>
            </div>
            <!-- Step 2 -->
            <div class="routine-step">
                <div class="step-num">02</div>
                <h3>Water Cleanser</h3>
                <p>Cleanse sweat and dirt.</p>
                <a href="/shop?cat=cleansers" class="link">Shop Water Cleansers &rarr;</a>
            </div>
             <!-- Step 3 -->
             <div class="routine-step">
                <div class="step-num">03</div>
                <h3>Exfoliator</h3>
                <p>Cleanse sweat and dirt.</p>
                <a href="/shop?cat=cleansers" class="link">Shop Exfoliator &rarr;</a>
            </div>
             <!-- Step 4 -->
             <div class="routine-step">
                <div class="step-num">04</div>
                <h3>Toner</h3>
                <p>Cleanse sweat and dirt.</p>
                <a href="/shop?cat=cleansers" class="link">Shop Toner &rarr;</a>
            </div>
             <!-- Step 5 -->
             <div class="routine-step">
                <div class="step-num">05</div>
                <h3>Essence</h3>
                <p>Cleanse sweat and dirt.</p>
                <a href="/shop?cat=cleansers" class="link">Shop Essence &rarr;</a>
            </div>
        </div>
    </div>
</div>

<style>
.routine-steps {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 30px;
    padding: 60px 20px;
}
.routine-step {
    border: 1px solid var(--skin-color-border);
    padding: 30px;
    border-radius: 4px;
}
.step-num {
    font-size: 40px;
    font-weight: 700;
    color: var(--skin-color-bg-pink);
    line-height: 1;
    margin-bottom: 10px;
}
</style>

<?php get_footer(); ?>
