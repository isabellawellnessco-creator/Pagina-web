<?php
/**
 * Skincare Skin - Rewards Page Content
 * Replicates Sukoshi Rewards layout.
 */
?>

<div class="homad-rewards-page">

    <!-- Hero Section -->
    <section class="rewards-hero">
        <h1>Homad Rewards</h1>
        <p>Become a member to earn points & get exclusive rewards every time you shop.</p>

        <div class="actions">
            <a href="<?php echo wc_get_page_permalink( 'myaccount' ); ?>" class="button">Join or Sign in</a>
        </div>
    </section>

    <!-- Ways to Earn -->
    <section class="rewards-earn">
        <h2>Ways to Earn Points</h2>
        <div class="rewards-earn-grid">
            <div class="earn-item">
                <div class="icon">🎂</div>
                <h3>Birthday Gift</h3>
                <p>Enter your birthday</p>
            </div>
             <div class="earn-item">
                <div class="icon">🛍️</div>
                <h3>1 Point per $1</h3>
                <p>Every time you shop</p>
            </div>
             <div class="earn-item">
                <div class="icon">📸</div>
                <h3>Leave a Review</h3>
                <p>Earn 50 Points</p>
            </div>
        </div>
    </section>

    <!-- Tiers -->
    <section class="rewards-tiers">
        <h2>Membership Tiers</h2>
        <div class="rewards-tiers-scroll">
            <!-- Pink Tier -->
            <div class="tier-card">
                <h3 style="color: #ffb6c1;">Pink</h3>
                <p>Free to join</p>
                <ul>
                    <li>15% off every 10th order</li>
                    <li>Birthday Gift</li>
                </ul>
            </div>
            <!-- Blue Tier -->
             <div class="tier-card">
                <h3 style="color: #add8e6;">Blue</h3>
                <p>Spend $350+</p>
                <ul>
                    <li>15% off every 7th order</li>
                    <li>Premium Birthday Gift</li>
                    <li>Early Access</li>
                </ul>
            </div>
        </div>
    </section>

</div>
