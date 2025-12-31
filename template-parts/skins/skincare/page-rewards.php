<?php
/**
 * Template Name: Rewards (SkinCupid Replica)
 * Skin: Skincare
 */

get_header();
?>

<div class="homad-skin-page page-rewards">

    <!-- Hero Banner -->
    <div class="rewards-hero">
        <div class="container">
            <h1>Join Cupid World</h1>
            <p>Unlock exclusive gifts & offers with our loyalty program.</p>
            <div class="actions">
                <a href="#" class="button">Join Now</a>
                <a href="#" class="button button-outline">Log In</a>
            </div>
        </div>
    </div>

    <!-- Icon Grid: Ways to Earn -->
    <div class="rewards-section">
        <div class="container">
            <h2 class="section-title">Ways to Earn</h2>
            <div class="rewards-grid">
                <div class="reward-item">
                    <div class="icon">🎂</div>
                    <h3>Birthday Points</h3>
                    <p>200 Points</p>
                </div>
                <div class="reward-item">
                    <div class="icon">🛍️</div>
                    <h3>Shop</h3>
                    <p>5 Points per £1</p>
                </div>
                <div class="reward-item">
                    <div class="icon">📱</div>
                    <h3>Follow Us</h3>
                    <p>50 Points</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tiers Scroll -->
    <div class="rewards-section bg-alt">
        <div class="container">
            <h2 class="section-title">VIP Tiers</h2>
            <div class="tiers-scroll-container">
                <div class="tier-card">
                    <h3>Cherub</h3>
                    <p class="points">0 - 499 Points</p>
                    <ul>
                        <li>5 Points per £1</li>
                        <li>Birthday Gift</li>
                    </ul>
                </div>
                <div class="tier-card">
                    <h3>Cupid</h3>
                    <p class="points">500 - 1999 Points</p>
                    <ul>
                        <li>7 Points per £1</li>
                        <li>Early Access</li>
                    </ul>
                </div>
                <div class="tier-card">
                    <h3>Angel</h3>
                    <p class="points">2000+ Points</p>
                    <ul>
                        <li>10 Points per £1</li>
                        <li>Free Shipping</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

</div>

<style>
/* Specific Styles for Rewards Page (Inlined for speed, ideally in CSS) */
.page-rewards .rewards-hero {
    background-color: var(--skin-color-bg-pink);
    padding: 60px 20px;
    text-align: center;
}
.page-rewards .section-title {
    text-align: center;
    font-size: 24px;
    margin-bottom: 40px;
    text-transform: uppercase;
}
.page-rewards .rewards-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 30px;
    text-align: center;
}
.page-rewards .reward-item .icon {
    font-size: 40px;
    margin-bottom: 10px;
}
.page-rewards .tiers-scroll-container {
    display: flex;
    overflow-x: auto;
    gap: 20px;
    padding-bottom: 20px;
}
.page-rewards .tier-card {
    min-width: 280px;
    background: white;
    padding: 30px;
    border: 1px solid var(--skin-color-border);
}
</style>

<?php get_footer(); ?>
