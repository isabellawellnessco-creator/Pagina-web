<?php
/**
 * Template Name: Shipping & Returns
 * Skin: Skincare
 */

get_header();
?>

<div class="homad-skin-page page-text">
    <div class="container narrow">
        <h1 class="page-title">Shipping & Returns</h1>

        <div class="text-content">
            <h3>Shipping Information</h3>
            <p>We offer free shipping on orders over $50. All orders are processed within 1-2 business days.</p>

            <table class="shipping-table">
                <thead>
                    <tr>
                        <th>Region</th>
                        <th>Cost</th>
                        <th>Time</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>USA & Canada</td>
                        <td>$6.99 (Free over $50)</td>
                        <td>3-5 Days</td>
                    </tr>
                    <tr>
                        <td>UK & Europe</td>
                        <td>£4.99 (Free over £40)</td>
                        <td>2-4 Days</td>
                    </tr>
                    <tr>
                        <td>Rest of World</td>
                        <td>$15.00</td>
                        <td>7-14 Days</td>
                    </tr>
                </tbody>
            </table>

            <hr>

            <h3>Returns Policy</h3>
            <p>If you are not completely happy with your purchase, please contact us within 14 days of receiving your order.</p>
            <p><strong>Note:</strong> Used skincare products cannot be returned for hygiene reasons.</p>
        </div>
    </div>
</div>

<style>
.page-text { padding: 40px 20px; }
.shipping-table {
    width: 100%;
    border-collapse: collapse;
    margin: 20px 0;
}
.shipping-table th, .shipping-table td {
    border: 1px solid var(--skin-color-border);
    padding: 12px;
    text-align: left;
}
.shipping-table th {
    background: var(--skin-color-bg-alt);
    font-weight: 700;
}
</style>

<?php get_footer(); ?>
