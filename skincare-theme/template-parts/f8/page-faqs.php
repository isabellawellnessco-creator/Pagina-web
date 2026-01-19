<?php
/**
 * Reference markup from refs/f8/FAQs.html.
 */
?>

<main id="MainContent" role="main" tabindex="-1">
      <div id="shopify-section-template--21252938399979__breadcrumbs_UGmPn7" class="shopify-section"><div class="breadcrumbs-section pb-5 pt-2 px-5 lg:px-10 xl:px-15" style="background-color:#fdfcfb;">
  <div class="container !px-0">
    <div class="breadcrumbs-wrapper">
        <nav role="navigation" aria-label="breadcrumbs">
    <ol class="flex flex-row items-center gap-2 text-secondary uppercase text-xs" itemscope="" itemtype="https://schema.org/BreadcrumbList">
    <li itemprop="itemListElement" itemscope="" itemtype="https://schema.org/ListItem">
        <a href="<?php echo esc_url( home_url( "/" ) ); ?>" itemprop="item" title="Home"><span itemprop="name">Home</span></a>
        <meta itemprop="position" content="1">
    </li>
    <li class=""><span aria-hidden="true" class="flex">|</span></li>
<li itemprop="itemListElement" itemscope="" itemtype="https://schema.org/ListItem">
        <a href="<?php echo esc_url( home_url( "/pages/main-faqs" ) ); ?>" itemprop="item" title="FAQs" aria-current="page"><span itemprop="name">FAQs</span></a>
        <meta itemprop="position" content="2">
    </li></ol>
</nav>
    </div>
    <style data-shopify="">
        #shopify-section-template--21252938399979__breadcrumbs_UGmPn7 .breadcrumbs-wrapper ol {
            color: #8798b0 !important;
        }
    </style>
</div>
</div>
  
  </div><section id="shopify-section-template--21252938399979__faq_page_t6KDwX" class="shopify-section"><div class="faqs-page container-fluid">
    <div class="container !px-0 flex flex-col gap-6 lg:gap-8">
        <h1 class="text-center"><strong>FAQ's</strong></h1>

        
            <div class="faq-page__content mx-auto max-w-[680px] text-center">
                <p>Below are some of the most frequently asked questions by our Cherubs.</p><p>If you have any other questions, please send us an email at <a href="mailto:hello@skincupid.co.uk" title="mailto:hello@skincupid.co.uk">hello@skincupid.co.uk</a></p>
            </div>
        


        
            <div class="faqs-page__filter mx-auto flex items-center gap-8 justify-center">
                <span>Filter by Topic</span>
                <select id="faq-filter" fdprocessedid="qjnir">
                    <option value="All">All</option>
                </select>
            </div>
        
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const filter = document.getElementById('faq-filter');
    const filterGroups = document.querySelectorAll('.faqs-section')
    if (filterGroups.length) {
        filterGroups.forEach((group) => {
            let option = document.createElement('option');
            option.value = group.dataset.title;
            option.textContent = group.dataset.title;
            filter.appendChild(option);
        })
    }

    filter.addEventListener('change', (e) => {
        if (e.target.value === 'All') {
            document.querySelectorAll('.faqs-section').forEach((section) => {
                section.style.display = 'block';
                section.closest('section').classList.remove('not-active');
            })
        } else {
            document.querySelectorAll('.faqs-section').forEach((section) => {
                section.style.display = 'none';
                section.closest('section').classList.add('not-active');
            });
            document.querySelector('.faqs-section[data-title="' + e.target.value + '"]').style.display = 'block';
            document.querySelector('.faqs-section[data-title="' + e.target.value + '"]').closest('section').classList.remove('not-active');
        }
    })
})
</script>
<style>
    #shopify-section-template--21252938399979__faq_page_t6KDwX {
        padding-bottom: 24px;
        padding-top: 0px;
    }

    @media screen and (min-width: 1024px) {
        #shopify-section-template--21252938399979__faq_page_t6KDwX {
            padding-bottom: 40px;
            padding-top: 0px;
        }
    }
</style>

</section><section id="shopify-section-template--21252938399979__faqs_LWLJi7" class="shopify-section">







<div class="block px-5 w-full faqs-section lg:px-10 xl:px-15" style="background-color:#ffffff;" data-title="🎄 Christmas Delivery Information 🎄">
        <div class="container !px-0">
            <div class="flex flex-col gap-10 mx-auto faqs-inner">
                
                    
                    <h3 class="text-left font-extrabold" style="color:#0f3062;">
                        🎄 Christmas Delivery Information 🎄
                    </h3>
                
    
                
                <div class="flex flex-col gap-6 faqs-wrapper" x-data="{ active: &#39;&#39; }">
                    
                        
                            
                <details x-effect="active != &#39;faq-1&#39; &amp;&amp; $el.removeAttribute(&#39;open&#39;)" id="faq_E4Gqrn" class="w-full faq" style="background:#f8f5f1;">
                    <summary class="p-6 cursor-pointer" style="color:#0f3062;" @click="active = &#39;faq-1&#39;">
                        <div class="inline-flex w-[calc(100%-6px)] items-center justify-between gap-2.5 faq__question">
                            Festive Delivery Update
                            <span aria-hidden="true" class="w-2 h-2 summary-icon transition-300"><svg width="100%" viewBox="0 0 15 9" fill="none" xmlns="http://www.w3.org/2000/svg">
    <path d="M1.58594 1.04285L7.50024 6.95715L13.4146 1.04285" stroke="currentColor" stroke-width="1.97144" stroke-linecap="round"></path>
</svg></span>
                        </div>
                    </summary>
                    <div class="px-6 pb-6 text-base rte" style="color:#0f3062;">
                        <p>Please note that due to the festive period, there may be slight delays to deliveries as our courier partners will be operating reduced services. There will be no courier collections from our warehouse on the following dates:</p><ul><li>Christmas Day (25th December)</li><li>Boxing Day (26th December)</li><li>New Year’s Day (1st January)</li></ul><p>Orders placed during this time will be processed as quickly as possible and dispatched once courier services resume. We really appreciate your patience and understanding during this busy period. </p><p>Thank you so much for your continued support, and we wish you a lovely festive season!</p>
                    </div>
                </details>
            
                <details x-effect="active != &#39;faq-2&#39; &amp;&amp; $el.removeAttribute(&#39;open&#39;)" id="faq_8gWWK6" class="w-full faq" style="background:#f8f5f1;">
                    <summary class="p-6 cursor-pointer" style="color:#0f3062;" @click="active = &#39;faq-2&#39;">
                        <div class="inline-flex w-[calc(100%-6px)] items-center justify-between gap-2.5 faq__question">
                            UK Delivery Cut-Offs
                            <span aria-hidden="true" class="w-2 h-2 summary-icon transition-300"><svg width="100%" viewBox="0 0 15 9" fill="none" xmlns="http://www.w3.org/2000/svg">
    <path d="M1.58594 1.04285L7.50024 6.95715L13.4146 1.04285" stroke="currentColor" stroke-width="1.97144" stroke-linecap="round"></path>
</svg></span>
                        </div>
                    </summary>
                    <div class="px-6 pb-6 text-base rte" style="color:#0f3062;">
                        <ul><li><strong>Royal Mail Tracked 48:</strong>&nbsp;Order by Wednesday 17th December </li><li><strong>Royal Mail Tracked 24/ DPD Next Day:</strong>&nbsp;Order by Thursday 18th December</li></ul>
                    </div>
                </details>
            
                <details x-effect="active != &#39;faq-3&#39; &amp;&amp; $el.removeAttribute(&#39;open&#39;)" id="faq_9pbz7h" class="w-full faq" style="background:#f8f5f1;">
                    <summary class="p-6 cursor-pointer" style="color:#0f3062;" @click="active = &#39;faq-3&#39;">
                        <div class="inline-flex w-[calc(100%-6px)] items-center justify-between gap-2.5 faq__question">
                            USA &amp; Canada Delivery Cut-Offs
                            <span aria-hidden="true" class="w-2 h-2 summary-icon transition-300"><svg width="100%" viewBox="0 0 15 9" fill="none" xmlns="http://www.w3.org/2000/svg">
    <path d="M1.58594 1.04285L7.50024 6.95715L13.4146 1.04285" stroke="currentColor" stroke-width="1.97144" stroke-linecap="round"></path>
</svg></span>
                        </div>
                    </summary>
                    <div class="px-6 pb-6 text-base rte" style="color:#0f3062;">
                        <ul><li><strong>International Express (DHL Express):</strong>&nbsp;Order by Friday 12th December</li></ul>
                    </div>
                </details>
            
                <details x-effect="active != &#39;faq-4&#39; &amp;&amp; $el.removeAttribute(&#39;open&#39;)" id="faq_CW9Kfz" class="w-full faq" style="background:#f8f5f1;">
                    <summary class="p-6 cursor-pointer" style="color:#0f3062;" @click="active = &#39;faq-4&#39;">
                        <div class="inline-flex w-[calc(100%-6px)] items-center justify-between gap-2.5 faq__question">
                            Europe Delivery Cut-Offs
                            <span aria-hidden="true" class="w-2 h-2 summary-icon transition-300"><svg width="100%" viewBox="0 0 15 9" fill="none" xmlns="http://www.w3.org/2000/svg">
    <path d="M1.58594 1.04285L7.50024 6.95715L13.4146 1.04285" stroke="currentColor" stroke-width="1.97144" stroke-linecap="round"></path>
</svg></span>
                        </div>
                    </summary>
                    <div class="px-6 pb-6 text-base rte" style="color:#0f3062;">
                        <ul><li><strong>International Express (DHL): </strong>Order by Tuesday 16th December</li><li><strong>Standard International Delivery: </strong>Order by Wednesday 10th December</li></ul>
                    </div>
                </details>
            
                <details x-effect="active != &#39;faq-5&#39; &amp;&amp; $el.removeAttribute(&#39;open&#39;)" id="faq_mBq3gx" class="w-full faq" style="background:#f8f5f1;">
                    <summary class="p-6 cursor-pointer" style="color:#0f3062;" @click="active = &#39;faq-5&#39;">
                        <div class="inline-flex w-[calc(100%-6px)] items-center justify-between gap-2.5 faq__question">
                            Rest Of World Delivery Cut-Offs
                            <span aria-hidden="true" class="w-2 h-2 summary-icon transition-300"><svg width="100%" viewBox="0 0 15 9" fill="none" xmlns="http://www.w3.org/2000/svg">
    <path d="M1.58594 1.04285L7.50024 6.95715L13.4146 1.04285" stroke="currentColor" stroke-width="1.97144" stroke-linecap="round"></path>
</svg></span>
                        </div>
                    </summary>
                    <div class="px-6 pb-6 text-base rte" style="color:#0f3062;">
                        <ul><li><strong>International Express (DHL): </strong>Order by Tuesday 9th December</li><li><strong>Standard International Delivery: </strong>Order by Tuesday 2nd December</li></ul>
                    </div>
                </details>
            
                        
                    
                
            </div>
        </div>
        <style data-shopify="">
            #shopify-section-template--21252938399979__faqs_LWLJi7 {
                padding-bottom: 40px;
                padding-top: 0px;
                background-color:#ffffff;
            }
            #shopify-section-template--21252938399979__faqs_LWLJi7 summary::marker {
                color: #f8f5f1;
            }
            #shopify-section-template--21252938399979__faqs_LWLJi7 .faqs-inner {
                width: 100%;
            }
    
            #shopify-section-template--21252938399979__faqs_LWLJi7 .faq {
                border-radius: 20px;
            }
            
            #shopify-section-template--21252938399979__faqs_LWLJi7 .faq__question {
                font-weight: 700;
                font-size: 14px;
            }
            
            @media screen and (min-width: 1024px) {
                #shopify-section-template--21252938399979__faqs_LWLJi7 {
                    padding-bottom: 60px;
                    padding-top: 0px;
                }
                #shopify-section-template--21252938399979__faqs_LWLJi7 .faqs-inner {
                    width: 100%;
                }
            
                #shopify-section-template--21252938399979__faqs_LWLJi7 .faq__question {
                    font-size: 16px;
                }
            }
    
            #shopify-section-template--21252938399979__faqs_LWLJi7.not-active {
                padding: 0;
            }
        </style>
    </div>


</div></section><section id="shopify-section-template--21252938399979__faqs_djmDTQ" class="shopify-section">







<div class="block px-5 w-full faqs-section lg:px-10 xl:px-15" style="background-color:#fdfcfb;" data-title="General Questions">
        <div class="container !px-0">
            <div class="flex flex-col gap-10 mx-auto faqs-inner">
                
                    
                    <h3 class="text-left font-extrabold" style="color:#0f3062;">
                        General Questions
                    </h3>
                
    
                
                <div class="flex flex-col gap-6 faqs-wrapper" x-data="{ active: &#39;&#39; }">
                    
                        
                            
                <details x-effect="active != &#39;faq-1&#39; &amp;&amp; $el.removeAttribute(&#39;open&#39;)" id="faq_4WVgwM" class="w-full faq" style="background:#f8f5f1;">
                    <summary class="p-6 cursor-pointer" style="color:#0f3062;" @click="active = &#39;faq-1&#39;">
                        <div class="inline-flex w-[calc(100%-6px)] items-center justify-between gap-2.5 faq__question">
                            I sent an email, when can I expect a response?
                            <span aria-hidden="true" class="w-2 h-2 summary-icon transition-300"><svg width="100%" viewBox="0 0 15 9" fill="none" xmlns="http://www.w3.org/2000/svg">
    <path d="M1.58594 1.04285L7.50024 6.95715L13.4146 1.04285" stroke="currentColor" stroke-width="1.97144" stroke-linecap="round"></path>
</svg></span>
                        </div>
                    </summary>
                    <div class="px-6 pb-6 text-base rte" style="color:#0f3062;">
                        <p>Our customer support service operates <strong>Monday to Friday from 10:00 AM to 7:00 PM British Time</strong>, excluding weekends and UK bank holidays. </p><p>We strive to respond within 24 hours during these service hours, and we always do our best to get back to you as soon as possible. However, response times may be delayed during peak or high-volume periods. We appreciate your understanding.</p>
                    </div>
                </details>
            
                <details x-effect="active != &#39;faq-2&#39; &amp;&amp; $el.removeAttribute(&#39;open&#39;)" id="faq_wmjgQg" class="w-full faq" style="background:#f8f5f1;">
                    <summary class="p-6 cursor-pointer" style="color:#0f3062;" @click="active = &#39;faq-2&#39;">
                        <div class="inline-flex w-[calc(100%-6px)] items-center justify-between gap-2.5 faq__question">
                            When are your out of stock products back?
                            <span aria-hidden="true" class="w-2 h-2 summary-icon transition-300"><svg width="100%" viewBox="0 0 15 9" fill="none" xmlns="http://www.w3.org/2000/svg">
    <path d="M1.58594 1.04285L7.50024 6.95715L13.4146 1.04285" stroke="currentColor" stroke-width="1.97144" stroke-linecap="round"></path>
</svg></span>
                        </div>
                    </summary>
                    <div class="px-6 pb-6 text-base rte" style="color:#0f3062;">
                        <p>If a product you’re interested in is currently out of stock, please use the “Notify Me When Available” button on the product page. </p><p>This way, you’ll receive an email notification as soon as the item is back in stock and ready for purchase.</p>
                    </div>
                </details>
            
                <details x-effect="active != &#39;faq-3&#39; &amp;&amp; $el.removeAttribute(&#39;open&#39;)" id="faq_x4jAGH" class="w-full faq" style="background:#f8f5f1;">
                    <summary class="p-6 cursor-pointer" style="color:#0f3062;" @click="active = &#39;faq-3&#39;">
                        <div class="inline-flex w-[calc(100%-6px)] items-center justify-between gap-2.5 faq__question">
                            What payment methods do you offer?
                            <span aria-hidden="true" class="w-2 h-2 summary-icon transition-300"><svg width="100%" viewBox="0 0 15 9" fill="none" xmlns="http://www.w3.org/2000/svg">
    <path d="M1.58594 1.04285L7.50024 6.95715L13.4146 1.04285" stroke="currentColor" stroke-width="1.97144" stroke-linecap="round"></path>
</svg></span>
                        </div>
                    </summary>
                    <div class="px-6 pb-6 text-base rte" style="color:#0f3062;">
                        <p>You can pay online with any of the following payment methods:</p><ul><li>Credit/debit card (VISA, Mastercard, AMEX, Discover)</li><li>Klarna</li><li>Google Pay</li><li>PayPal</li><li>Shop Pay</li></ul>
                    </div>
                </details>
            
                <details x-effect="active != &#39;faq-4&#39; &amp;&amp; $el.removeAttribute(&#39;open&#39;)" id="faq_UqpqaU" class="w-full faq" style="background:#f8f5f1;">
                    <summary class="p-6 cursor-pointer" style="color:#0f3062;" @click="active = &#39;faq-4&#39;">
                        <div class="inline-flex w-[calc(100%-6px)] items-center justify-between gap-2.5 faq__question">
                            Do you have a loyalty scheme?
                            <span aria-hidden="true" class="w-2 h-2 summary-icon transition-300"><svg width="100%" viewBox="0 0 15 9" fill="none" xmlns="http://www.w3.org/2000/svg">
    <path d="M1.58594 1.04285L7.50024 6.95715L13.4146 1.04285" stroke="currentColor" stroke-width="1.97144" stroke-linecap="round"></path>
</svg></span>
                        </div>
                    </summary>
                    <div class="px-6 pb-6 text-base rte" style="color:#0f3062;">
                        <p>Absolutely! Feel free to join our loyalty programme by <a href="<?php echo esc_url( home_url( "/pages/rewards" ) ); ?>" title="Rewards Program">signing up here</a>.</p><p>If you already have a Skin Cupid account, you don't need to sign up as you are automatically enrolled to our program. Simply log in to our website and go to the Rewards page to check your points and rewards.</p><p>We hold our cherished Cherubs in high regard, and the points and coupons you'll earn are our way of showing our appreciation for your loyalty.</p>
                    </div>
                </details>
            
                <details x-effect="active != &#39;faq-5&#39; &amp;&amp; $el.removeAttribute(&#39;open&#39;)" id="faq_n3AMaQ" class="w-full faq" style="background:#f8f5f1;">
                    <summary class="p-6 cursor-pointer" style="color:#0f3062;" @click="active = &#39;faq-5&#39;">
                        <div class="inline-flex w-[calc(100%-6px)] items-center justify-between gap-2.5 faq__question">
                            Do you offer any discount codes?
                            <span aria-hidden="true" class="w-2 h-2 summary-icon transition-300"><svg width="100%" viewBox="0 0 15 9" fill="none" xmlns="http://www.w3.org/2000/svg">
    <path d="M1.58594 1.04285L7.50024 6.95715L13.4146 1.04285" stroke="currentColor" stroke-width="1.97144" stroke-linecap="round"></path>
</svg></span>
                        </div>
                    </summary>
                    <div class="px-6 pb-6 text-base rte" style="color:#0f3062;">
                        <p>Yes! We offer a 10% off discount code for your first order. You can get this discount code by subscribing to our newsletter.</p><p>Please note that this discount code cannot be used for subscription boxes, sets, minis, products on sale and certain collections.</p>
                    </div>
                </details>
            
                <details x-effect="active != &#39;faq-7&#39; &amp;&amp; $el.removeAttribute(&#39;open&#39;)" id="faq_9hUF6F" class="w-full faq" style="background:#f8f5f1;">
                    <summary class="p-6 cursor-pointer" style="color:#0f3062;" @click="active = &#39;faq-7&#39;">
                        <div class="inline-flex w-[calc(100%-6px)] items-center justify-between gap-2.5 faq__question">
                            How do I use my discount?
                            <span aria-hidden="true" class="w-2 h-2 summary-icon transition-300"><svg width="100%" viewBox="0 0 15 9" fill="none" xmlns="http://www.w3.org/2000/svg">
    <path d="M1.58594 1.04285L7.50024 6.95715L13.4146 1.04285" stroke="currentColor" stroke-width="1.97144" stroke-linecap="round"></path>
</svg></span>
                        </div>
                    </summary>
                    <div class="px-6 pb-6 text-base rte" style="color:#0f3062;">
                        <p>If you want to apply a discount code to your order you can follow these steps:</p><p><strong>On a Computer</strong></p><ul><li>Proceed to the checkout page.</li><li>On the right-hand side of the page, you will see a list of the products you've selected.</li><li>Below the list of products, you will find a space to enter your discount code.</li><li>After entering the code, make sure to click on the "Apply" button.</li><li>You will see your discount is being applied to your order.</li></ul><p><strong>On a Mobile Phone</strong></p><ul><li>During the checkout process, you will first be asked to enter your shipping information and select your preferred shipping method on the initial screens.</li><li>On the third screen, you'll see a space to enter your discount code. This field appears before the payment information.</li><li>Enter your discount code and hit the "Arrow" button to apply the discount to your order.</li></ul><p>You can also use a gift card by following the same steps outlined above.</p>
                    </div>
                </details>
            
                <details x-effect="active != &#39;faq-8&#39; &amp;&amp; $el.removeAttribute(&#39;open&#39;)" id="faq_g63EPy" class="w-full faq" style="background:#f8f5f1;">
                    <summary class="p-6 cursor-pointer" style="color:#0f3062;" @click="active = &#39;faq-8&#39;">
                        <div class="inline-flex w-[calc(100%-6px)] items-center justify-between gap-2.5 faq__question">
                            Can I use discount codes, offers and/or promotions at once?
                            <span aria-hidden="true" class="w-2 h-2 summary-icon transition-300"><svg width="100%" viewBox="0 0 15 9" fill="none" xmlns="http://www.w3.org/2000/svg">
    <path d="M1.58594 1.04285L7.50024 6.95715L13.4146 1.04285" stroke="currentColor" stroke-width="1.97144" stroke-linecap="round"></path>
</svg></span>
                        </div>
                    </summary>
                    <div class="px-6 pb-6 text-base rte" style="color:#0f3062;">
                        <p>Offers, promotions, discounts and discount codes are not stackable, this means that they cannot be used in conjunction with other discount codes, offers, promotions or discounts.</p>
                    </div>
                </details>
            
                        
                    
                
            </div>
        </div>
        <style data-shopify="">
            #shopify-section-template--21252938399979__faqs_djmDTQ {
                padding-bottom: 40px;
                padding-top: 0px;
                background-color:#fdfcfb;
            }
            #shopify-section-template--21252938399979__faqs_djmDTQ summary::marker {
                color: #f8f5f1;
            }
            #shopify-section-template--21252938399979__faqs_djmDTQ .faqs-inner {
                width: 100%;
            }
    
            #shopify-section-template--21252938399979__faqs_djmDTQ .faq {
                border-radius: 20px;
            }
            
            #shopify-section-template--21252938399979__faqs_djmDTQ .faq__question {
                font-weight: 700;
                font-size: 14px;
            }
            
            @media screen and (min-width: 1024px) {
                #shopify-section-template--21252938399979__faqs_djmDTQ {
                    padding-bottom: 60px;
                    padding-top: 0px;
                }
                #shopify-section-template--21252938399979__faqs_djmDTQ .faqs-inner {
                    width: 100%;
                }
            
                #shopify-section-template--21252938399979__faqs_djmDTQ .faq__question {
                    font-size: 16px;
                }
            }
    
            #shopify-section-template--21252938399979__faqs_djmDTQ.not-active {
                padding: 0;
            }
        </style>
    </div>


</div></section><section id="shopify-section-template--21252938399979__faqs_EbbQg3" class="shopify-section">







<div class="block px-5 w-full faqs-section lg:px-10 xl:px-15" style="background-color:#fdfcfb;" data-title="Skincare and Products Questions">
        <div class="container !px-0">
            <div class="flex flex-col gap-10 mx-auto faqs-inner">
                
                    
                    <h3 class="text-left font-extrabold" style="color:#0f3062;">
                        Skincare and Products Questions
                    </h3>
                
    
                
                <div class="flex flex-col gap-6 faqs-wrapper" x-data="{ active: &#39;&#39; }">
                    
                        
                            
                <details x-effect="active != &#39;faq-1&#39; &amp;&amp; $el.removeAttribute(&#39;open&#39;)" id="faq_QXPzer" class="w-full faq" style="background:#f8f5f1;">
                    <summary class="p-6 cursor-pointer" style="color:#0f3062;" @click="active = &#39;faq-1&#39;">
                        <div class="inline-flex w-[calc(100%-6px)] items-center justify-between gap-2.5 faq__question">
                            How do I start my K-beauty journey?
                            <span aria-hidden="true" class="w-2 h-2 summary-icon transition-300"><svg width="100%" viewBox="0 0 15 9" fill="none" xmlns="http://www.w3.org/2000/svg">
    <path d="M1.58594 1.04285L7.50024 6.95715L13.4146 1.04285" stroke="currentColor" stroke-width="1.97144" stroke-linecap="round"></path>
</svg></span>
                        </div>
                    </summary>
                    <div class="px-6 pb-6 text-base rte" style="color:#0f3062;">
                        <p>If you are unsure of where to start or would like product recommendations based on your skin concerns, you may contact us at <a href="mailto:hello@skincupid.co.uk" target="_blank" title="mailto:hello@skincupid.co.uk">hello@skincupid.co.uk</a>. We will ask you a few questions to know more about your skin type and concerns to provide personalised recommendations for you.</p>
                    </div>
                </details>
            
                <details x-effect="active != &#39;faq-2&#39; &amp;&amp; $el.removeAttribute(&#39;open&#39;)" id="faq_Xe9d9g" class="w-full faq" style="background:#f8f5f1;">
                    <summary class="p-6 cursor-pointer" style="color:#0f3062;" @click="active = &#39;faq-2&#39;">
                        <div class="inline-flex w-[calc(100%-6px)] items-center justify-between gap-2.5 faq__question">
                            How should I introduce new products to my skincare routine?
                            <span aria-hidden="true" class="w-2 h-2 summary-icon transition-300"><svg width="100%" viewBox="0 0 15 9" fill="none" xmlns="http://www.w3.org/2000/svg">
    <path d="M1.58594 1.04285L7.50024 6.95715L13.4146 1.04285" stroke="currentColor" stroke-width="1.97144" stroke-linecap="round"></path>
</svg></span>
                        </div>
                    </summary>
                    <div class="px-6 pb-6 text-base rte" style="color:#0f3062;">
                        <p>When introducing a new product into your skincare routine it is highly important to perform a patch test. A patch test involves applying a small amount of the product to a discreet area of your skin, such as behind your ear or on your neck. By doing this, you can check for any adverse reactions, like redness, irritation, or allergies, before using the product on a larger area of your face or body.</p><p>When introducing multiple new products to your skincare routine, it's also advisable to follow a staggered approach. This means adding one new product at a time and allowing a few days to assess how your skin reacts before adding another new product. This approach helps you identify which product may be causing any issues or providing the desired benefits.</p><p>Following these recommendations will help to ensure that you get the best results while minimising the risk of any adverse reactions.</p>
                    </div>
                </details>
            
                        
                    
                
            </div>
        </div>
        <style data-shopify="">
            #shopify-section-template--21252938399979__faqs_EbbQg3 {
                padding-bottom: 40px;
                padding-top: 0px;
                background-color:#fdfcfb;
            }
            #shopify-section-template--21252938399979__faqs_EbbQg3 summary::marker {
                color: #f8f5f1;
            }
            #shopify-section-template--21252938399979__faqs_EbbQg3 .faqs-inner {
                width: 100%;
            }
    
            #shopify-section-template--21252938399979__faqs_EbbQg3 .faq {
                border-radius: 20px;
            }
            
            #shopify-section-template--21252938399979__faqs_EbbQg3 .faq__question {
                font-weight: 700;
                font-size: 14px;
            }
            
            @media screen and (min-width: 1024px) {
                #shopify-section-template--21252938399979__faqs_EbbQg3 {
                    padding-bottom: 60px;
                    padding-top: 0px;
                }
                #shopify-section-template--21252938399979__faqs_EbbQg3 .faqs-inner {
                    width: 100%;
                }
            
                #shopify-section-template--21252938399979__faqs_EbbQg3 .faq__question {
                    font-size: 16px;
                }
            }
    
            #shopify-section-template--21252938399979__faqs_EbbQg3.not-active {
                padding: 0;
            }
        </style>
    </div>


</div></section><section id="shopify-section-template--21252938399979__faqs_gmJLAp" class="shopify-section">







<div class="block px-5 w-full faqs-section lg:px-10 xl:px-15" style="background-color:#fdfcfb;" data-title="Order Questions">
        <div class="container !px-0">
            <div class="flex flex-col gap-10 mx-auto faqs-inner">
                
                    
                    <h3 class="text-left font-extrabold" style="color:#0f3062;">
                        Order Questions
                    </h3>
                
    
                
                <div class="flex flex-col gap-6 faqs-wrapper" x-data="{ active: &#39;&#39; }">
                    
                        
                            
                <details x-effect="active != &#39;faq-1&#39; &amp;&amp; $el.removeAttribute(&#39;open&#39;)" id="faq_d3Pk7x" class="w-full faq" style="background:#f8f5f1;">
                    <summary class="p-6 cursor-pointer" style="color:#0f3062;" @click="active = &#39;faq-1&#39;">
                        <div class="inline-flex w-[calc(100%-6px)] items-center justify-between gap-2.5 faq__question">
                            When can I expect my order to arrive?
                            <span aria-hidden="true" class="w-2 h-2 summary-icon transition-300"><svg width="100%" viewBox="0 0 15 9" fill="none" xmlns="http://www.w3.org/2000/svg">
    <path d="M1.58594 1.04285L7.50024 6.95715L13.4146 1.04285" stroke="currentColor" stroke-width="1.97144" stroke-linecap="round"></path>
</svg></span>
                        </div>
                    </summary>
                    <div class="px-6 pb-6 text-base rte" style="color:#0f3062;">
                        <p>We aim to dispatch orders within 24 to 48 business hours from the moment of order creation. Business hours are Monday to Friday, from 08:00 AM to 5:00 PM British Time, excluding weekends and UK bank holidays.</p><p>If the 48-hour period falls outside of our business hours, an additional day will be required for processing, as the order will be collected on the following day.</p><p>During sale periods and due to an increase of orders received, this time frame can be extended We kindly ask for your understanding and support during busy periods as we do our best to dispatch orders as soon as possible.</p><p>Once dispatched, your order should arrive within the time frame of your chosen delivery method, which you can double-check in your order confirmation email. For the UK this is typically within 2-4 working days and for international orders, it can be between 7-15 working days (this excludes weekends and public holidays). Please see <a href="<?php echo esc_url( home_url( "/policies/shipping-policy" ) ); ?>" title="Shipping Policy">our shipping policy</a> for more information.</p><p>Please note that for international deliveries there may be unforeseen delays that are out of Skin Cupid's control, such as local country customs processing times. Once your order is shipped, you should receive a shipping confirmation email which contains a tracking link. Please follow the tracking link to see the latest delivery update.</p>
                    </div>
                </details>
            
                <details x-effect="active != &#39;faq-2&#39; &amp;&amp; $el.removeAttribute(&#39;open&#39;)" id="faq_eXUgXR" class="w-full faq" style="background:#f8f5f1;">
                    <summary class="p-6 cursor-pointer" style="color:#0f3062;" @click="active = &#39;faq-2&#39;">
                        <div class="inline-flex w-[calc(100%-6px)] items-center justify-between gap-2.5 faq__question">
                            How can I track my order?
                            <span aria-hidden="true" class="w-2 h-2 summary-icon transition-300"><svg width="100%" viewBox="0 0 15 9" fill="none" xmlns="http://www.w3.org/2000/svg">
    <path d="M1.58594 1.04285L7.50024 6.95715L13.4146 1.04285" stroke="currentColor" stroke-width="1.97144" stroke-linecap="round"></path>
</svg></span>
                        </div>
                    </summary>
                    <div class="px-6 pb-6 text-base rte" style="color:#0f3062;">
                        <p>Following the dispatch of your order, an email confirming the shipment will be making its way to your inbox. This email will include tracking information to keep you informed about the status of your delivery. To ensure you receive this information promptly, we recommend inspecting your spam or junk folder, as occasionally our emails might find their way there. Furthermore, kindly verify the accuracy of your provided email address to facilitate seamless communication.</p>
                    </div>
                </details>
            
                <details x-effect="active != &#39;faq-3&#39; &amp;&amp; $el.removeAttribute(&#39;open&#39;)" id="faq_C9WqQR" class="w-full faq" style="background:#f8f5f1;">
                    <summary class="p-6 cursor-pointer" style="color:#0f3062;" @click="active = &#39;faq-3&#39;">
                        <div class="inline-flex w-[calc(100%-6px)] items-center justify-between gap-2.5 faq__question">
                            I have to change my shipping address, how can I do it?
                            <span aria-hidden="true" class="w-2 h-2 summary-icon transition-300"><svg width="100%" viewBox="0 0 15 9" fill="none" xmlns="http://www.w3.org/2000/svg">
    <path d="M1.58594 1.04285L7.50024 6.95715L13.4146 1.04285" stroke="currentColor" stroke-width="1.97144" stroke-linecap="round"></path>
</svg></span>
                        </div>
                    </summary>
                    <div class="px-6 pb-6 text-base rte" style="color:#0f3062;">
                        <p>If you need to update the shipping address for your order, please follow these steps:</p><p><strong>Before We Ship: </strong>If your order has not been shipped yet, please get in touch with us immediately at <a href="mailto:hello@skincupid.co.uk" target="_blank" title="mailto:hello@skincupid.co.uk">hello@skincupid.co.uk</a><br>with the subject "ADDRESS CHANGE - [Your Order Number]." Make sure to provide the correct address in the email to avoid delays. We'll do our best to accommodate the change but we cannot guarantee that it can be completed.</p><p><strong>Order Has Shipped:</strong> If your order is already on its way to you, unfortunately, we're unable to modify the address. If the order is returned to us due to an incorrect address, we will issue a refund after deducting shipping costs. Regrettably, we can't offer a refund or reshipment for parcels delivered to the wrong address or lost in transit due to the incorrect address.</p>
                    </div>
                </details>
            
                <details x-effect="active != &#39;faq-4&#39; &amp;&amp; $el.removeAttribute(&#39;open&#39;)" id="faq_wgyV9Q" class="w-full faq" style="background:#f8f5f1;">
                    <summary class="p-6 cursor-pointer" style="color:#0f3062;" @click="active = &#39;faq-4&#39;">
                        <div class="inline-flex w-[calc(100%-6px)] items-center justify-between gap-2.5 faq__question">
                            Can I cancel/modify my order?
                            <span aria-hidden="true" class="w-2 h-2 summary-icon transition-300"><svg width="100%" viewBox="0 0 15 9" fill="none" xmlns="http://www.w3.org/2000/svg">
    <path d="M1.58594 1.04285L7.50024 6.95715L13.4146 1.04285" stroke="currentColor" stroke-width="1.97144" stroke-linecap="round"></path>
</svg></span>
                        </div>
                    </summary>
                    <div class="px-6 pb-6 text-base rte" style="color:#0f3062;">
                        <p>Skin Cupid Team works hard to fulfil your orders as quickly as possible, so there is a very short timeframe in which we can make changes. If you would like to cancel or modify your order, please notify us through email at <a href="mailto:hello@skincupid.co.uk" target="_blank" title="mailto:hello@skincupid.co.uk">hello@skincupid.co.uk </a>within 1 hour of placing your order and we will do our best to accommodate the request.</p><p>If your email is a cancellation, please title your email "CANCEL - [Your Order Number]"</p><p>If your email is a modification, please title your email "MODIFICATION - [Your Order Number]"</p><p>We always strive to accommodate these requests; however, please note that we cannot guarantee them. To avoid any issues, we kindly ask that you double-check all information and ensure it is correct before completing your order.</p><p>Please note that only 1 modification chance is given per order, so please put all of your modification requests in one email.</p>
                    </div>
                </details>
            
                <details x-effect="active != &#39;faq-5&#39; &amp;&amp; $el.removeAttribute(&#39;open&#39;)" id="faq_ycy6Ka" class="w-full faq" style="background:#f8f5f1;">
                    <summary class="p-6 cursor-pointer" style="color:#0f3062;" @click="active = &#39;faq-5&#39;">
                        <div class="inline-flex w-[calc(100%-6px)] items-center justify-between gap-2.5 faq__question">
                            Can I exchange items?
                            <span aria-hidden="true" class="w-2 h-2 summary-icon transition-300"><svg width="100%" viewBox="0 0 15 9" fill="none" xmlns="http://www.w3.org/2000/svg">
    <path d="M1.58594 1.04285L7.50024 6.95715L13.4146 1.04285" stroke="currentColor" stroke-width="1.97144" stroke-linecap="round"></path>
</svg></span>
                        </div>
                    </summary>
                    <div class="px-6 pb-6 text-base rte" style="color:#0f3062;">
                        <p><strong>Black Friday Important Notice: </strong>All sales made during the Black Friday campaign period are final and cannot be refunded or exchanged. </p><p>We do not currently offer direct product exchanges. If you wish to order a different product or need a replacement for your current purchase, the most efficient way to handle this is to follow a two-step process:</p><p><strong>1. Initiate a Return: </strong>Contact us to inform us about the return of the product you would like to exchange. We will guide you through the return process and provide you with all the necessary information.</p><p><strong>2. Place a New Order:</strong> Once the return is completed, you can proceed to place a new order for the desired product on our website.</p>
                    </div>
                </details>
            
                <details x-effect="active != &#39;faq-6&#39; &amp;&amp; $el.removeAttribute(&#39;open&#39;)" id="faq_eCTGVd" class="w-full faq" style="background:#f8f5f1;">
                    <summary class="p-6 cursor-pointer" style="color:#0f3062;" @click="active = &#39;faq-6&#39;">
                        <div class="inline-flex w-[calc(100%-6px)] items-center justify-between gap-2.5 faq__question">
                            What should I do if my parcel was stolen?
                            <span aria-hidden="true" class="w-2 h-2 summary-icon transition-300"><svg width="100%" viewBox="0 0 15 9" fill="none" xmlns="http://www.w3.org/2000/svg">
    <path d="M1.58594 1.04285L7.50024 6.95715L13.4146 1.04285" stroke="currentColor" stroke-width="1.97144" stroke-linecap="round"></path>
</svg></span>
                        </div>
                    </summary>
                    <div class="px-6 pb-6 text-base rte" style="color:#0f3062;">
                        <p>Kindly be informed that if your parcel has been delivered to the shipping address entered in the order, and is subsequently stolen, we cannot be held responsible or accountable for the loss. We recommend that you promptly file a report with your local police to address the situation appropriately. Your understanding in this matter is greatly appreciated.</p>
                    </div>
                </details>
            
                <details x-effect="active != &#39;faq-7&#39; &amp;&amp; $el.removeAttribute(&#39;open&#39;)" id="faq_Upqke3" class="w-full faq" style="background:#f8f5f1;">
                    <summary class="p-6 cursor-pointer" style="color:#0f3062;" @click="active = &#39;faq-7&#39;">
                        <div class="inline-flex w-[calc(100%-6px)] items-center justify-between gap-2.5 faq__question">
                            Why haven't I gotten a confirmation email?
                            <span aria-hidden="true" class="w-2 h-2 summary-icon transition-300"><svg width="100%" viewBox="0 0 15 9" fill="none" xmlns="http://www.w3.org/2000/svg">
    <path d="M1.58594 1.04285L7.50024 6.95715L13.4146 1.04285" stroke="currentColor" stroke-width="1.97144" stroke-linecap="round"></path>
</svg></span>
                        </div>
                    </summary>
                    <div class="px-6 pb-6 text-base rte" style="color:#0f3062;">
                        <p>If you haven't received a confirmation email, please consider the following steps:</p><p><strong>1. Verify Your Email: </strong>Ensure that the email address you provided during the checkout process is accurate. Even a small typo can prevent emails from reaching you.</p><p><strong>2. Check Spam/Junk Folders: </strong>Confirmation emails might occasionally be directed to your spam or junk folders. Please check these folders to see if the email has been filtered there.</p><p>After reviewing these aspects and if you still can't locate your confirmation email, please don't hesitate to contact our customer support.</p>
                    </div>
                </details>
            
                <details x-effect="active != &#39;faq-8&#39; &amp;&amp; $el.removeAttribute(&#39;open&#39;)" id="faq_BP88TC" class="w-full faq" style="background:#f8f5f1;">
                    <summary class="p-6 cursor-pointer" style="color:#0f3062;" @click="active = &#39;faq-8&#39;">
                        <div class="inline-flex w-[calc(100%-6px)] items-center justify-between gap-2.5 faq__question">
                            There are products missing in my order, what do I do?
                            <span aria-hidden="true" class="w-2 h-2 summary-icon transition-300"><svg width="100%" viewBox="0 0 15 9" fill="none" xmlns="http://www.w3.org/2000/svg">
    <path d="M1.58594 1.04285L7.50024 6.95715L13.4146 1.04285" stroke="currentColor" stroke-width="1.97144" stroke-linecap="round"></path>
</svg></span>
                        </div>
                    </summary>
                    <div class="px-6 pb-6 text-base rte" style="color:#0f3062;">
                        <p>We sincerely apologise for any disappointment or inconvenience caused by missing products. In the rare event this occurs, please rest assured that we are fully committed to investigating the situation. </p><p>Kindly email us at <a>hello@skincupid.co.uk</a> with the subject line "MISSING ITEMS - [Your Order Number]" and include pictures of the box, shipping label, and products received.</p>
                    </div>
                </details>
            
                <details x-effect="active != &#39;faq-9&#39; &amp;&amp; $el.removeAttribute(&#39;open&#39;)" id="faq_cXkfxP" class="w-full faq" style="background:#f8f5f1;">
                    <summary class="p-6 cursor-pointer" style="color:#0f3062;" @click="active = &#39;faq-9&#39;">
                        <div class="inline-flex w-[calc(100%-6px)] items-center justify-between gap-2.5 faq__question">
                            My order is stopped at local customs, how long will it take until I receive it?
                            <span aria-hidden="true" class="w-2 h-2 summary-icon transition-300"><svg width="100%" viewBox="0 0 15 9" fill="none" xmlns="http://www.w3.org/2000/svg">
    <path d="M1.58594 1.04285L7.50024 6.95715L13.4146 1.04285" stroke="currentColor" stroke-width="1.97144" stroke-linecap="round"></path>
</svg></span>
                        </div>
                    </summary>
                    <div class="px-6 pb-6 text-base rte" style="color:#0f3062;">
                        <p>It's important to note that customs processing times can vary significantly depending on several factors, including the destination country, the specific customs regulations in place, and the volume of shipments being processed by customs at any given time.</p><p>As a company, we do not have direct control over customs processes, and we do not possess real-time information about the status of individual shipments once they enter the customs clearance phase.</p><p>To obtain more accurate information regarding the customs processing status of your order, we recommend contacting your local customs authorities directly. They will be able to provide you with the latest updates and estimated timelines for the clearance of your shipment.</p><p>If you need assistance with your order, email us at <a href="mailto:hello@skincupid.co.uk" target="_blank" title="mailto:hello@skincupid.co.uk">hello@skincupid.co.uk</a></p>
                    </div>
                </details>
            
                <details x-effect="active != &#39;faq-10&#39; &amp;&amp; $el.removeAttribute(&#39;open&#39;)" id="faq_gKGVG9" class="w-full faq" style="background:#f8f5f1;">
                    <summary class="p-6 cursor-pointer" style="color:#0f3062;" @click="active = &#39;faq-10&#39;">
                        <div class="inline-flex w-[calc(100%-6px)] items-center justify-between gap-2.5 faq__question">
                            What happens if my order cannot be delivered or is not collected?
                            <span aria-hidden="true" class="w-2 h-2 summary-icon transition-300"><svg width="100%" viewBox="0 0 15 9" fill="none" xmlns="http://www.w3.org/2000/svg">
    <path d="M1.58594 1.04285L7.50024 6.95715L13.4146 1.04285" stroke="currentColor" stroke-width="1.97144" stroke-linecap="round"></path>
</svg></span>
                        </div>
                    </summary>
                    <div class="px-6 pb-6 text-base rte" style="color:#0f3062;">
                        <p>If your order cannot be delivered due to an incomplete or invalid address or if it is not collected within the allotted timeframe, your package will be returned to us.</p><p>When the parcel is received, our team will verify and process the return as soon as possible. Once this process is completed, we will promptly issue a refund after deducting shipping costs. Regrettably, we can't offer a refund or reshipment for parcels delivered to the wrong address or lost in transit due to the incorrect address.</p><p>It's highly recommended to ensure that you enter the complete shipping address (including building, house or flat number) and to keep a close eye on the notifications sent by the courier regarding the progress of your shipment. These notifications may contain vital information regarding your package's status or may even require some action on your part to ensure a successful delivery.</p>
                    </div>
                </details>
            
                        
                    
                
            </div>
        </div>
        <style data-shopify="">
            #shopify-section-template--21252938399979__faqs_gmJLAp {
                padding-bottom: 40px;
                padding-top: 0px;
                background-color:#fdfcfb;
            }
            #shopify-section-template--21252938399979__faqs_gmJLAp summary::marker {
                color: #f8f5f1;
            }
            #shopify-section-template--21252938399979__faqs_gmJLAp .faqs-inner {
                width: 100%;
            }
    
            #shopify-section-template--21252938399979__faqs_gmJLAp .faq {
                border-radius: 20px;
            }
            
            #shopify-section-template--21252938399979__faqs_gmJLAp .faq__question {
                font-weight: 700;
                font-size: 14px;
            }
            
            @media screen and (min-width: 1024px) {
                #shopify-section-template--21252938399979__faqs_gmJLAp {
                    padding-bottom: 60px;
                    padding-top: 0px;
                }
                #shopify-section-template--21252938399979__faqs_gmJLAp .faqs-inner {
                    width: 100%;
                }
            
                #shopify-section-template--21252938399979__faqs_gmJLAp .faq__question {
                    font-size: 16px;
                }
            }
    
            #shopify-section-template--21252938399979__faqs_gmJLAp.not-active {
                padding: 0;
            }
        </style>
    </div>


</div></section><section id="shopify-section-template--21252938399979__faqs_X8xYm7" class="shopify-section">







<div class="block px-5 w-full faqs-section lg:px-10 xl:px-15" style="background-color:#fdfcfb;" data-title="Shipping Questions">
        <div class="container !px-0">
            <div class="flex flex-col gap-10 mx-auto faqs-inner">
                
                    
                    <h3 class="text-left font-extrabold" style="color:#0f3062;">
                        Shipping Questions
                    </h3>
                
    
                
                <div class="flex flex-col gap-6 faqs-wrapper" x-data="{ active: &#39;&#39; }">
                    
                        
                            
                <details x-effect="active != &#39;faq-1&#39; &amp;&amp; $el.removeAttribute(&#39;open&#39;)" id="faq_UEMBei" class="w-full faq" style="background:#f8f5f1;">
                    <summary class="p-6 cursor-pointer" style="color:#0f3062;" @click="active = &#39;faq-1&#39;">
                        <div class="inline-flex w-[calc(100%-6px)] items-center justify-between gap-2.5 faq__question">
                            Which countries do you ship to?
                            <span aria-hidden="true" class="w-2 h-2 summary-icon transition-300"><svg width="100%" viewBox="0 0 15 9" fill="none" xmlns="http://www.w3.org/2000/svg">
    <path d="M1.58594 1.04285L7.50024 6.95715L13.4146 1.04285" stroke="currentColor" stroke-width="1.97144" stroke-linecap="round"></path>
</svg></span>
                        </div>
                    </summary>
                    <div class="px-6 pb-6 text-base rte" style="color:#0f3062;">
                        <p>Skin Cupid ships worldwide, and we are always looking to open more destinations. </p><p>Currently, we ship to the following countries:</p><p><strong>Right at Home (2-3 working days): </strong>United Kingdom, Isle of Man, Jersey, Guernsey</p><p><strong>Across Europe (5-10 working days): </strong> Austria, Belgium, Bulgaria, Czechia, Denmark, Estonia, Finland, France, Germany, Greece, Ireland, Italy, Latvia, Lithuania, Luxembourg, Netherlands, Poland, Portugal, Romania, Sweden, Switzerland, Faroe Islands, Hungary, Iceland, Svalbard &amp; Jan Mayen, Gibraltar, Monaco, Falkland Islands, Slovakia, Slovenia</p><p><strong>United States (2-4 working days) </strong></p><p><strong>Canada (5-10 working days)</strong></p><p><strong>Worldwide Zone A (5-6 working days): </strong>Bahrain, Cyprus, Qatar, Saudi Arabia, United Arab Emirates, Kuwait, Oman, Lebanon, Malta</p><p><strong>Worldwide Zone B (10-15 working days): </strong>Australia, Morocco, New Zealand</p><p>If you don't spot your country on the list, it means we are not shipping to your location at this time. However, we are always looking for the right courier options to expand our offerings so please check again in the future.</p>
                    </div>
                </details>
            
                <details x-effect="active != &#39;faq-2&#39; &amp;&amp; $el.removeAttribute(&#39;open&#39;)" id="faq_xnfb64" class="w-full faq" style="background:#f8f5f1;">
                    <summary class="p-6 cursor-pointer" style="color:#0f3062;" @click="active = &#39;faq-2&#39;">
                        <div class="inline-flex w-[calc(100%-6px)] items-center justify-between gap-2.5 faq__question">
                            What is your shipping cost?
                            <span aria-hidden="true" class="w-2 h-2 summary-icon transition-300"><svg width="100%" viewBox="0 0 15 9" fill="none" xmlns="http://www.w3.org/2000/svg">
    <path d="M1.58594 1.04285L7.50024 6.95715L13.4146 1.04285" stroke="currentColor" stroke-width="1.97144" stroke-linecap="round"></path>
</svg></span>
                        </div>
                    </summary>
                    <div class="px-6 pb-6 text-base rte" style="color:#0f3062;">
                        <p><strong>UK Delivery</strong></p><ul><li>FREE Standard Delivery on UK Orders over £30 (2-3 working days from dispatch</li><li>FREE First Class Delivery on UK Orders over £60 (1-2 working days from dispatch)</li><li>FREE Special Next Day Delivery on UK Orders over £100 (1 working day from dispatch)</li><li>£3.99 - Royal Mail Tracked Standard Delivery (2-3 working days from dispatch)</li><li>£5.50 - Royal Mail Tracked First Class Delivery (1-2 working day from dispatch)</li></ul><p><strong>European Delivery</strong></p><ul><li>FREE standard delivery on all orders over €90</li><li>€6.99 –on orders between €50 and €89.99,&nbsp;within 4-10 working days from dispatch</li><li>€9.99 – on orders below €50,&nbsp;within 4-10 working days from dispatch</li></ul><p><strong>United States</strong></p><p>Orders to the US ship from both our US and UK warehouse, meaning you may see multiple shipping options at checkout.</p><p>For Orders shipping from our <em><strong>US</strong></em> warehouse&nbsp;</p><ul><li><strong>FREE DHL Express Delivery </strong>on orders over $50 (estimated delivery 2-3 business days from dispatch)*</li><li><strong>DHL Express Delivery&nbsp;</strong>- $5 (estimated delivery 2-3 business days from dispatch)</li></ul><p>For Orders shipping from our <em><strong>UK</strong></em> warehouse&nbsp;</p><ul><li><strong>FREE DHL Express Delivery </strong>on orders over $150 (estimated delivery 2-3 business days from dispatch)*</li><li><strong>DHL Express Delivery&nbsp;</strong>- $25 (estimated delivery 2-3 business days from dispatch)</li></ul><p>Customs duties will be paid by Skin Cupid, but please note local taxes may apply.</p><p><strong>Canada</strong></p><ul><li>FREE DHL Express Shipping on orders over CAD$200 (estimated delivery 2-3 business days from dispatch)*</li><li>DHL Express Shipping&nbsp;under CAD$200 - $45.99 (estimated delivery 2-3 business days from dispatch)*</li></ul><p><strong>Rest of the World</strong></p><ul><li>FREE standard delivery on all orders over £150 </li><li>Zone A: £15.99 – 5-6 working days from dispatch</li><li>Zone B: £12.99 – 10-15 working days from dispatch</li></ul><p>For more information on shipping, please view our <a href="<?php echo esc_url( home_url( "/policies/shipping-policy" ) ); ?>" title="Shipping Policy">shipping policy here</a>.</p>
                    </div>
                </details>
            
                <details x-effect="active != &#39;faq-3&#39; &amp;&amp; $el.removeAttribute(&#39;open&#39;)" id="faq_tEemHP" class="w-full faq" style="background:#f8f5f1;">
                    <summary class="p-6 cursor-pointer" style="color:#0f3062;" @click="active = &#39;faq-3&#39;">
                        <div class="inline-flex w-[calc(100%-6px)] items-center justify-between gap-2.5 faq__question">
                            Why hasn't my order been shipped yet?
                            <span aria-hidden="true" class="w-2 h-2 summary-icon transition-300"><svg width="100%" viewBox="0 0 15 9" fill="none" xmlns="http://www.w3.org/2000/svg">
    <path d="M1.58594 1.04285L7.50024 6.95715L13.4146 1.04285" stroke="currentColor" stroke-width="1.97144" stroke-linecap="round"></path>
</svg></span>
                        </div>
                    </summary>
                    <div class="px-6 pb-6 text-base rte" style="color:#0f3062;">
                        <p>Please note, our couriers do not operate over weekends or during UK bank holidays. </p><p>If you placed your order on a weekend or a holiday, rest assured it will be processed and shipped on the very next working day.</p><p>It's important to note that if your order was placed&nbsp;on a Friday, there is a chance that you have missed the day's last collection time. In such cases, your order will be processed and dispatched on the following working day.</p>
                    </div>
                </details>
            
                <details x-effect="active != &#39;faq-4&#39; &amp;&amp; $el.removeAttribute(&#39;open&#39;)" id="faq_H9GzEX" class="w-full faq" style="background:#f8f5f1;">
                    <summary class="p-6 cursor-pointer" style="color:#0f3062;" @click="active = &#39;faq-4&#39;">
                        <div class="inline-flex w-[calc(100%-6px)] items-center justify-between gap-2.5 faq__question">
                            Do you ship to PO boxes?
                            <span aria-hidden="true" class="w-2 h-2 summary-icon transition-300"><svg width="100%" viewBox="0 0 15 9" fill="none" xmlns="http://www.w3.org/2000/svg">
    <path d="M1.58594 1.04285L7.50024 6.95715L13.4146 1.04285" stroke="currentColor" stroke-width="1.97144" stroke-linecap="round"></path>
</svg></span>
                        </div>
                    </summary>
                    <div class="px-6 pb-6 text-base rte" style="color:#0f3062;">
                        <p>We can deliver to PO boxes within the UK. However, our delivery partners are unable to deliver to PO Boxes outside of the UK, which may result in parcels being returned to our facility. In such an event, we will gladly process a full refund for you, with shipping charges deducted from the refund. To prevent this from happening, please always provide a workplace or residential address.</p>
                    </div>
                </details>
            
                <details x-effect="active != &#39;faq-5&#39; &amp;&amp; $el.removeAttribute(&#39;open&#39;)" id="faq_KPheAp" class="w-full faq" style="background:#f8f5f1;">
                    <summary class="p-6 cursor-pointer" style="color:#0f3062;" @click="active = &#39;faq-5&#39;">
                        <div class="inline-flex w-[calc(100%-6px)] items-center justify-between gap-2.5 faq__question">
                            Do you ship to APO addresses?
                            <span aria-hidden="true" class="w-2 h-2 summary-icon transition-300"><svg width="100%" viewBox="0 0 15 9" fill="none" xmlns="http://www.w3.org/2000/svg">
    <path d="M1.58594 1.04285L7.50024 6.95715L13.4146 1.04285" stroke="currentColor" stroke-width="1.97144" stroke-linecap="round"></path>
</svg></span>
                        </div>
                    </summary>
                    <div class="px-6 pb-6 text-base rte" style="color:#0f3062;">
                        <p>Our courier can deliver parcels to APO addresses within the United States.</p><p>Regrettably, if the designated address falls outside the U.S. territory, we are unable to deliver to that location.</p>
                    </div>
                </details>
            
                        
                    
                
            </div>
        </div>
        <style data-shopify="">
            #shopify-section-template--21252938399979__faqs_X8xYm7 {
                padding-bottom: 40px;
                padding-top: 0px;
                background-color:#fdfcfb;
            }
            #shopify-section-template--21252938399979__faqs_X8xYm7 summary::marker {
                color: #f8f5f1;
            }
            #shopify-section-template--21252938399979__faqs_X8xYm7 .faqs-inner {
                width: 100%;
            }
    
            #shopify-section-template--21252938399979__faqs_X8xYm7 .faq {
                border-radius: 20px;
            }
            
            #shopify-section-template--21252938399979__faqs_X8xYm7 .faq__question {
                font-weight: 700;
                font-size: 14px;
            }
            
            @media screen and (min-width: 1024px) {
                #shopify-section-template--21252938399979__faqs_X8xYm7 {
                    padding-bottom: 60px;
                    padding-top: 0px;
                }
                #shopify-section-template--21252938399979__faqs_X8xYm7 .faqs-inner {
                    width: 100%;
                }
            
                #shopify-section-template--21252938399979__faqs_X8xYm7 .faq__question {
                    font-size: 16px;
                }
            }
    
            #shopify-section-template--21252938399979__faqs_X8xYm7.not-active {
                padding: 0;
            }
        </style>
    </div>


</div></section><section id="shopify-section-template--21252938399979__faqs_7ME3TR" class="shopify-section">







<div class="block px-5 w-full faqs-section lg:px-10 xl:px-15" style="background-color:#fdfcfb;" data-title="Payment Questions">
        <div class="container !px-0">
            <div class="flex flex-col gap-10 mx-auto faqs-inner">
                
                    
                    <h3 class="text-left font-extrabold" style="color:#0f3062;">
                        Payment Questions
                    </h3>
                
    
                
                <div class="flex flex-col gap-6 faqs-wrapper" x-data="{ active: &#39;&#39; }">
                    
                        
                            
                <details x-effect="active != &#39;faq-1&#39; &amp;&amp; $el.removeAttribute(&#39;open&#39;)" id="faq_bEe4QJ" class="w-full faq" style="background:#f8f5f1;">
                    <summary class="p-6 cursor-pointer" style="color:#0f3062;" @click="active = &#39;faq-1&#39;">
                        <div class="inline-flex w-[calc(100%-6px)] items-center justify-between gap-2.5 faq__question">
                            Will I be charged VAT/Customs?
                            <span aria-hidden="true" class="w-2 h-2 summary-icon transition-300"><svg width="100%" viewBox="0 0 15 9" fill="none" xmlns="http://www.w3.org/2000/svg">
    <path d="M1.58594 1.04285L7.50024 6.95715L13.4146 1.04285" stroke="currentColor" stroke-width="1.97144" stroke-linecap="round"></path>
</svg></span>
                        </div>
                    </summary>
                    <div class="px-6 pb-6 text-base rte" style="color:#0f3062;">
                        <p><strong>UK Orders:</strong> Skin Cupid is based in the UK. This means that any orders within the UK will not&nbsp;be subject to customs fees.</p><p><strong>EU Orders:</strong> We're happy to let our EU Cherubs know that we're registered with EU authorities. For all orders under €140 within the EU, we will&nbsp;cover your duties and taxes. This means your order will arrive straight to you without the extra hassle or cost. Before completing your order, please make sure that its total cost is below €140 before any discounts are applied.</p><p>If your order exceeds €150, the responsibility for customs and taxes will shift to the customer. In such cases, you can expect the courier or your local customs office to reach out directly with the invoice and payment method.</p><p><strong>USA Orders:</strong> For our Cherubs in the USA, here's some good news! Skin Cupid will cover customs duties. Please note, local taxes may still apply.</p><p><strong>Canada Orders:</strong> For our Canadian Cherubs, Skin Cupid will cover customs duties. Please note, local taxes may still apply.</p><p><strong>United Arab Emirates:</strong> All the duties and taxes will be covered by Skin Cupid.</p><p><strong>Global Destinations:</strong> For our valued global customers, we recommend&nbsp;taking a moment to familiarise yourself with your local customs office's guidelines. Customs policies and procedures can vary significantly from country to country, so consulting local resources will provide the most accurate information.</p><p>If you have any further questions or concerns, our team is always here to help. Your experience at Skin Cupid matters to us, and we want to ensure it's as smooth as possible.</p>
                    </div>
                </details>
            
                <details x-effect="active != &#39;faq-2&#39; &amp;&amp; $el.removeAttribute(&#39;open&#39;)" id="faq_6NcKBq" class="w-full faq" style="background:#f8f5f1;">
                    <summary class="p-6 cursor-pointer" style="color:#0f3062;" @click="active = &#39;faq-2&#39;">
                        <div class="inline-flex w-[calc(100%-6px)] items-center justify-between gap-2.5 faq__question">
                            My card is charged but I haven't gotten an order confirmation?
                            <span aria-hidden="true" class="w-2 h-2 summary-icon transition-300"><svg width="100%" viewBox="0 0 15 9" fill="none" xmlns="http://www.w3.org/2000/svg">
    <path d="M1.58594 1.04285L7.50024 6.95715L13.4146 1.04285" stroke="currentColor" stroke-width="1.97144" stroke-linecap="round"></path>
</svg></span>
                        </div>
                    </summary>
                    <div class="px-6 pb-6 text-base rte" style="color:#0f3062;">
                        <p>If you find yourself in a situation where your card has been charged, but you haven't received an order confirmation, please follow these steps:</p><p><strong>1. Double-Check Your Email: </strong>&nbsp;Please verify that the email you provided during checkout is accurate. Sometimes, slight typos can lead to order confirmations not being received.</p><p><strong>2. Check Your Bank Account: </strong>Take a look at your bank account to see if the payment is labelled as "pending." Pending payments might indicate that the transaction encountered an issue. Generally, pending payments are automatically reverted to your account within a few working days if no order confirmation has been received.&nbsp;</p><p>If after checking these two points you still haven't received your order confirmation, please don't hesitate to reach out to our customer support team. We're here to assist you and ensure that your shopping experience proceeds seamlessly.</p>
                    </div>
                </details>
            
                        
                    
                
            </div>
        </div>
        <style data-shopify="">
            #shopify-section-template--21252938399979__faqs_7ME3TR {
                padding-bottom: 40px;
                padding-top: 0px;
                background-color:#fdfcfb;
            }
            #shopify-section-template--21252938399979__faqs_7ME3TR summary::marker {
                color: #f8f5f1;
            }
            #shopify-section-template--21252938399979__faqs_7ME3TR .faqs-inner {
                width: 100%;
            }
    
            #shopify-section-template--21252938399979__faqs_7ME3TR .faq {
                border-radius: 20px;
            }
            
            #shopify-section-template--21252938399979__faqs_7ME3TR .faq__question {
                font-weight: 700;
                font-size: 14px;
            }
            
            @media screen and (min-width: 1024px) {
                #shopify-section-template--21252938399979__faqs_7ME3TR {
                    padding-bottom: 60px;
                    padding-top: 0px;
                }
                #shopify-section-template--21252938399979__faqs_7ME3TR .faqs-inner {
                    width: 100%;
                }
            
                #shopify-section-template--21252938399979__faqs_7ME3TR .faq__question {
                    font-size: 16px;
                }
            }
    
            #shopify-section-template--21252938399979__faqs_7ME3TR.not-active {
                padding: 0;
            }
        </style>
    </div>


</div></section><section id="shopify-section-template--21252938399979__faqs_3J6YCr" class="shopify-section">







<div class="block px-5 w-full faqs-section lg:px-10 xl:px-15" style="background-color:#fdfcfb;" data-title="Returns Questions">
        <div class="container !px-0">
            <div class="flex flex-col gap-10 mx-auto faqs-inner">
                
                    
                    <h3 class="text-left font-extrabold" style="color:#0f3062;">
                        Returns Questions
                    </h3>
                
    
                
                <div class="flex flex-col gap-6 faqs-wrapper" x-data="{ active: &#39;&#39; }">
                    
                        
                            
                <details x-effect="active != &#39;faq-1&#39; &amp;&amp; $el.removeAttribute(&#39;open&#39;)" id="faq_Ee9Y7c" class="w-full faq" style="background:#f8f5f1;">
                    <summary class="p-6 cursor-pointer" style="color:#0f3062;" @click="active = &#39;faq-1&#39;">
                        <div class="inline-flex w-[calc(100%-6px)] items-center justify-between gap-2.5 faq__question">
                            What is your returns policy?
                            <span aria-hidden="true" class="w-2 h-2 summary-icon transition-300"><svg width="100%" viewBox="0 0 15 9" fill="none" xmlns="http://www.w3.org/2000/svg">
    <path d="M1.58594 1.04285L7.50024 6.95715L13.4146 1.04285" stroke="currentColor" stroke-width="1.97144" stroke-linecap="round"></path>
</svg></span>
                        </div>
                    </summary>
                    <div class="px-6 pb-6 text-base rte" style="color:#0f3062;">
                        <p>We have a 14-day return policy, which means you have&nbsp;14 days after receiving your item to request a return.&nbsp;</p><p>To be eligible for a return, your item must be in the same condition that you received it, unused, unopened, sealed and in its original packaging. You’ll also need the receipt or proof of purchase.&nbsp;Customers will cover the postage to send the goods back.</p><p><strong>Please note that we do not offer returns for international orders.</strong></p>
                    </div>
                </details>
            
                <details x-effect="active != &#39;faq-2&#39; &amp;&amp; $el.removeAttribute(&#39;open&#39;)" id="faq_jLbGmU" class="w-full faq" style="background:#f8f5f1;">
                    <summary class="p-6 cursor-pointer" style="color:#0f3062;" @click="active = &#39;faq-2&#39;">
                        <div class="inline-flex w-[calc(100%-6px)] items-center justify-between gap-2.5 faq__question">
                            How can I start a return?
                            <span aria-hidden="true" class="w-2 h-2 summary-icon transition-300"><svg width="100%" viewBox="0 0 15 9" fill="none" xmlns="http://www.w3.org/2000/svg">
    <path d="M1.58594 1.04285L7.50024 6.95715L13.4146 1.04285" stroke="currentColor" stroke-width="1.97144" stroke-linecap="round"></path>
</svg></span>
                        </div>
                    </summary>
                    <div class="px-6 pb-6 text-base rte" style="color:#0f3062;">
                        <p>To begin a return process, kindly reach out to us via <a href="mailto:hello@skincupid.co.uk" target="_blank" title="mailto:hello@skincupid.co.uk">hello@skincupid.co.uk</a>. Please title the subject of your email as "RETURN - [Your Order Number], provide details such as the reason for the return, and the specific products you intend to return. It's important to note that we cannot accept items sent back without prior return authorisation. Additionally, please keep in mind that Skin Cupid does not cover the cost of return postage.</p>
                    </div>
                </details>
            
                <details x-effect="active != &#39;faq-3&#39; &amp;&amp; $el.removeAttribute(&#39;open&#39;)" id="faq_jD6dpk" class="w-full faq" style="background:#f8f5f1;">
                    <summary class="p-6 cursor-pointer" style="color:#0f3062;" @click="active = &#39;faq-3&#39;">
                        <div class="inline-flex w-[calc(100%-6px)] items-center justify-between gap-2.5 faq__question">
                            What if the product is damaged or incorrectly sent?
                            <span aria-hidden="true" class="w-2 h-2 summary-icon transition-300"><svg width="100%" viewBox="0 0 15 9" fill="none" xmlns="http://www.w3.org/2000/svg">
    <path d="M1.58594 1.04285L7.50024 6.95715L13.4146 1.04285" stroke="currentColor" stroke-width="1.97144" stroke-linecap="round"></path>
</svg></span>
                        </div>
                    </summary>
                    <div class="px-6 pb-6 text-base rte" style="color:#0f3062;">
                        <p>In the rare event that the product comes damaged or incorrect, we deeply apologise. Please email us with a picture of the damaged or incorrect products as well as your order number.</p>
                    </div>
                </details>
            
                <details x-effect="active != &#39;faq-4&#39; &amp;&amp; $el.removeAttribute(&#39;open&#39;)" id="faq_Cy8xb3" class="w-full faq" style="background:#f8f5f1;">
                    <summary class="p-6 cursor-pointer" style="color:#0f3062;" @click="active = &#39;faq-4&#39;">
                        <div class="inline-flex w-[calc(100%-6px)] items-center justify-between gap-2.5 faq__question">
                            When will I receive my refund?
                            <span aria-hidden="true" class="w-2 h-2 summary-icon transition-300"><svg width="100%" viewBox="0 0 15 9" fill="none" xmlns="http://www.w3.org/2000/svg">
    <path d="M1.58594 1.04285L7.50024 6.95715L13.4146 1.04285" stroke="currentColor" stroke-width="1.97144" stroke-linecap="round"></path>
</svg></span>
                        </div>
                    </summary>
                    <div class="px-6 pb-6 text-base rte" style="color:#0f3062;">
                        <p>It may take up to 10 working days for a refund to be reflected in your bank account, depending on the payment method and your bank’s processing times.</p><p>You will receive an email notification once it is completed on our side.</p><p>If you don’t receive your refund within 10 working days since you received our email, please email our customer service at <a href="mailto:hello@skincupid.co.uk" target="_blank" title="mailto:hello@skincupid.co.uk">hello@skincupid.co.uk</a>.</p>
                    </div>
                </details>
            
                <details x-effect="active != &#39;faq-5&#39; &amp;&amp; $el.removeAttribute(&#39;open&#39;)" id="faq_aAnbje" class="w-full faq" style="background:#f8f5f1;">
                    <summary class="p-6 cursor-pointer" style="color:#0f3062;" @click="active = &#39;faq-5&#39;">
                        <div class="inline-flex w-[calc(100%-6px)] items-center justify-between gap-2.5 faq__question">
                            What is your refund policy?
                            <span aria-hidden="true" class="w-2 h-2 summary-icon transition-300"><svg width="100%" viewBox="0 0 15 9" fill="none" xmlns="http://www.w3.org/2000/svg">
    <path d="M1.58594 1.04285L7.50024 6.95715L13.4146 1.04285" stroke="currentColor" stroke-width="1.97144" stroke-linecap="round"></path>
</svg></span>
                        </div>
                    </summary>
                    <div class="px-6 pb-6 text-base rte" style="color:#0f3062;">
                        <p>Each case will be thoroughly evaluated, and refunds, whether partial or full, will be issued accordingly.</p><p>For orders returned to us due to an incorrect or incomplete shipping address, rejected or not collected within the allotted timeframe  or if customs charges are rejected when applicable, we offer a refund with a deduction for shipping costs. This deduction applies for all orders, including those with free shipping. This policy ensures transparency and fairness in handling such situations.</p>
                    </div>
                </details>
            
                        
                    
                
            </div>
        </div>
        <style data-shopify="">
            #shopify-section-template--21252938399979__faqs_3J6YCr {
                padding-bottom: 0px;
                padding-top: 0px;
                background-color:#fdfcfb;
            }
            #shopify-section-template--21252938399979__faqs_3J6YCr summary::marker {
                color: #f8f5f1;
            }
            #shopify-section-template--21252938399979__faqs_3J6YCr .faqs-inner {
                width: 100%;
            }
    
            #shopify-section-template--21252938399979__faqs_3J6YCr .faq {
                border-radius: 20px;
            }
            
            #shopify-section-template--21252938399979__faqs_3J6YCr .faq__question {
                font-weight: 700;
                font-size: 12px;
            }
            
            @media screen and (min-width: 1024px) {
                #shopify-section-template--21252938399979__faqs_3J6YCr {
                    padding-bottom: 0px;
                    padding-top: 0px;
                }
                #shopify-section-template--21252938399979__faqs_3J6YCr .faqs-inner {
                    width: 100%;
                }
            
                #shopify-section-template--21252938399979__faqs_3J6YCr .faq__question {
                    font-size: 16px;
                }
            }
    
            #shopify-section-template--21252938399979__faqs_3J6YCr.not-active {
                padding: 0;
            }
        </style>
    </div>


</div></section><section id="shopify-section-template--21252938399979__simple_banner_t6tjAp" class="shopify-section"><div class="simple-banner block w-full max-lg:px-5 lg:px-10 xl:px-15">
    <div class="simple-banner-inner w-full block mx-auto relative py-6 lg:py-13 overflow-hidden max-w-5.5xl" style="border-radius:70px;">
        
        
        <div class="simple-banner-content flex flex-col gap-2.5 lg:gap-5 px-5 lg:px-10 xl:px-15 relative">
            
            
                <p class="text-center font-normal text-base lg:text-lg-xl mb-1.5" style="color:#0f3062;">Didn't find the answer to your question?
            
            
            </p><div class="simple-banner-buttons order-last flex items-center justify-center gap-5 flex-wrap mt-2.5 mx-auto">
                
                    <a href="<?php echo esc_url( home_url( "/pages/contact-us" ) ); ?>" class="btn w-max btn-primary" title="Contact Us">
                        Contact Us
                        <svg width="100%" viewBox="0 0 16 13" fill="none" xmlns="http://www.w3.org/2000/svg">
    <g id="Group 6466">
    <line id="Line 5" x1="0.224976" y1="6.6251" x2="14.225" y2="6.6251" stroke="currentColor" stroke-width="1.2"></line>
    <path id="Vector 8" d="M9.22498 0.975098L14.725 6.4751L9.22498 11.9751" stroke="currentColor" stroke-width="1.2"></path>
    </g>
</svg>
    
                    </a>
                
                
            </div>
        </div>
    </div>
    <style data-shopify="">
        #shopify-section-template--21252938399979__simple_banner_t6tjAp {
            padding-bottom: 0px;
            padding-top: 0px;
            background: ;
        }
        @media screen and (min-width: 1024px) {
            #shopify-section-template--21252938399979__simple_banner_t6tjAp {
                padding-bottom: 0px;
                padding-top: 0px;
            }
        }
    </style>
</div>

<style> #shopify-section-template--21252938399979__simple_banner_t6tjAp p {display: flex; flex-direction: column; font-size: 12px;} @media (min-width: 1024px) {#shopify-section-template--21252938399979__simple_banner_t6tjAp p {font-size: 14px; }} </style></section><section id="shopify-section-template--21252938399979__embed_content_weiWjc" class="shopify-section"><div class="embed-content block w-full px-5 lg:px-10 xl:px-15">
    
        <flickity-scroll data-delay="1" data-auto-play="true" data-pause="false">
            <div class="top-section-border" data-flickity="{ &quot;cellAlign&quot;: &quot;left&quot;, &quot;wrapAround&quot;: true, &quot;resize&quot;: false, &quot;pageDots&quot;: false, &quot;prevNextButtons&quot;: false }">
                <div class="top-section-border-inner-wrapper">
                    <div class="contents md:hidden">
                        <svg width="1485" height="61" viewBox="0 0 1485 61" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M868.351 41.8509C872.575 36.8107 905.056 -0.723208 953.563 1.05744C959.436 1.27418 995.138 9.28073 1014.3 23.2667C1023.54 30.0111 1030.32 37.0954 1035.02 42.7476C1035.88 43.7761 1037.36 44.0268 1038.5 43.3213C1052.16 34.8813 1083.87 18.1372 1127.16 18.7365C1168.36 19.3059 1198.38 35.2808 1211.73 43.5976C1212.67 44.1798 1213.86 44.133 1214.74 43.4786C1226.28 34.9578 1258.64 13.8195 1303.93 15.1114C1336.86 16.0506 1361.38 28.4047 1373.72 35.9097C1374.97 36.6662 1376.58 36.2965 1377.37 35.0725C1382.06 27.8309 1397.92 6.92211 1426.77 3.77729C1456.88 0.492224 1477.38 19.1657 1483.05 25.0729C1483.92 25.9823 1485.31 26.1608 1486.38 25.5063C1494.95 20.2791 1512.42 11.3971 1536.16 10.5769C1560.78 9.72696 1579.31 18.0012 1587.95 22.625C1588.75 23.05 1589.7 23.0457 1590.5 22.6165C1602.07 16.3608 1623.37 6.98586 1651.3 6.33565C1692.1 5.37945 1721.63 23.6237 1733 31.6642C1733.87 32.2804 1735.02 32.3187 1735.93 31.7705C1736.99 31.1288 1738.08 30.4913 1739.21 29.8623C1741.68 28.4812 1746.56 25.8166 1754.44 22.7865C1784.29 11.3164 1806.48 10.1392 1806.48 10.1392C1806.48 10.1392 1836.31 8.55827 1873.44 22.7865C1881.18 25.7528 1886.65 27.8479 1888.67 29.8623C1888.97 30.1556 1890.35 30.8015 1891.95 31.7705C1892.86 32.3229 1894.01 32.2804 1894.88 31.6642C1906.26 23.6237 1935.78 5.3837 1976.58 6.33565C2004.51 6.99011 2025.81 16.3608 2037.38 22.6165C2038.18 23.0457 2039.13 23.05 2039.93 22.625C2048.57 18.0012 2067.1 9.73121 2091.72 10.5769C2115.46 11.3971 2132.93 20.2791 2141.5 25.5063C2142.57 26.1608 2143.96 25.9823 2144.83 25.0729C2150.5 19.1657 2171 0.496474 2201.11 3.77729C2229.96 6.92211 2245.82 27.8309 2250.51 35.0725C2251.3 36.2922 2252.91 36.662 2254.16 35.9097C2266.5 28.4047 2291.02 16.0506 2323.95 15.1114C2369.24 13.8237 2401.61 34.962 2413.14 43.4786C2414.03 44.133 2415.22 44.1755 2416.15 43.5976C2429.51 35.285 2459.52 19.3059 2500.72 18.7365C2544.02 18.1415 2575.73 34.8856 2589.39 43.3213C2590.52 44.0268 2592 43.7761 2592.86 42.7476C2597.56 37.0954 2604.35 30.0111 2613.58 23.2667C2623.75 15.8509 2646.15 2.06464 2674.32 1.05744C2722.89 -0.680711 2755.26 36.7512 2759.53 41.8509V60.9961H-22V22.7822C-17.2148 24.5799 -12.0768 26.8832 -6.7646 29.8581C-5.63841 30.487 -4.54622 31.1245 -3.48378 31.7662C-2.57434 32.3187 -1.42265 32.2762 -0.555696 31.66C10.8209 23.6194 40.344 5.37945 81.1417 6.3314C109.075 6.98586 130.375 16.3566 141.947 22.6122C142.742 23.0415 143.694 23.0457 144.489 22.6207C153.133 17.997 171.662 9.72696 196.285 10.5727C220.02 11.3929 237.495 20.2749 246.062 25.5021C247.138 26.1565 248.523 25.978 249.394 25.0686C255.059 19.1614 275.56 0.492224 305.678 3.77304C334.525 6.91786 350.385 27.8267 355.073 35.0683C355.863 36.288 357.474 36.6577 358.719 35.9055C371.065 28.4004 395.586 16.0464 428.513 15.1072C473.807 13.8195 506.169 34.9578 517.703 43.4743C518.587 44.1288 519.777 44.1713 520.712 43.5933C534.065 35.2808 564.081 19.3017 605.282 18.7322C648.574 18.1372 680.286 34.8813 693.945 43.3171C695.084 44.0225 696.563 43.7718 697.421 42.7434C702.121 37.0912 708.908 30.0068 718.143 23.2625C737.309 9.28073 773.012 1.27418 778.885 1.05744C827.392 -0.723208 859.873 36.8107 864.097 41.8509C864.581 42.6414 865.452 43.1046 866.362 43.0536C867.633 42.9813 868.291 41.9444 868.347 41.8509H868.351Z" fill="currentColor"></path></svg>
                    </div>
                    <div class="hidden md:contents">
                        <svg width="4142" height="90" viewBox="0 0 4142 90" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1305.53 61.2822C1311.86 53.7219 1360.58 -2.57895 1433.34 0.0920227C1442.15 0.417129 1495.71 12.427 1524.45 33.4059C1538.31 43.5225 1548.48 54.149 1555.53 62.6273C1556.82 64.1699 1559.04 64.546 1560.75 63.4879C1581.24 50.8278 1628.8 25.7117 1693.74 26.6105C1755.54 27.4647 1800.57 51.427 1820.6 63.9022C1822 64.7755 1823.78 64.7054 1825.11 63.7237C1842.42 50.9426 1890.95 19.2351 1958.9 21.173C2008.29 22.5818 2045.07 41.1129 2063.59 52.3705C2065.45 53.5052 2067.87 52.9506 2069.06 51.1147C2076.09 40.2523 2099.88 8.88903 2143.15 4.17179C2188.33 -0.755805 2219.07 27.2544 2227.57 36.1151C2228.88 37.4793 2230.96 37.747 2232.57 36.7653C2245.43 28.9245 2271.64 15.6015 2307.24 14.3712C2344.17 13.0963 2371.97 25.5077 2384.93 32.4433C2386.12 33.0808 2387.55 33.0744 2388.74 32.4306C2406.1 23.0471 2438.05 8.98465 2479.95 8.00933C2541.16 6.57504 2585.44 33.9414 2602.5 46.0022C2603.81 46.9265 2605.53 46.9839 2606.89 46.1616C2608.48 45.199 2610.12 44.2428 2611.81 43.2994C2615.52 41.2276 2622.85 37.2307 2634.67 32.6856C2679.43 15.4804 2712.72 13.7146 2712.72 13.7146C2712.72 13.7146 2757.46 11.3433 2813.16 32.6856C2824.77 37.1351 2832.97 40.2778 2836.01 43.2994C2836.45 43.7392 2838.53 44.7082 2840.93 46.1616C2842.29 46.9903 2844.02 46.9265 2845.32 46.0022C2862.39 33.9414 2906.67 6.58141 2967.87 8.00933C3009.77 8.99103 3041.72 23.0471 3059.08 32.4306C3060.27 33.0744 3061.7 33.0808 3062.89 32.4433C3075.85 25.5077 3103.65 13.1027 3140.58 14.3712C3176.19 15.6015 3202.4 28.9245 3215.25 36.7653C3216.86 37.747 3218.94 37.4793 3220.25 36.1151C3228.74 27.2544 3259.5 -0.74943 3304.67 4.17179C3347.94 8.88903 3371.73 40.2523 3378.76 51.1147C3379.95 52.9442 3382.37 53.4988 3384.23 52.3705C3402.75 41.1129 3439.53 22.5818 3488.92 21.173C3556.87 19.2415 3605.41 50.9489 3622.72 63.7237C3624.04 64.7054 3625.83 64.7692 3627.23 63.9022C3647.26 51.4334 3692.28 27.4647 3754.08 26.6105C3819.02 25.7181 3866.59 50.8342 3887.08 63.4879C3888.79 64.546 3891.01 64.1699 3892.29 62.6273C3899.34 54.149 3909.52 43.5225 3923.38 33.4059C3938.62 22.2822 3972.23 1.60281 4014.48 0.0920227C4087.34 -2.51521 4135.89 53.6327 4142.3 61.2822V90L-30 90L-30 32.6792C-22.8222 35.3757 -15.1152 38.8307 -7.1469 43.293C-5.45762 44.2364 -3.81933 45.1926 -2.22567 46.1552C-0.8615 46.9839 0.866031 46.9202 2.16646 45.9958C19.2314 33.935 63.516 6.57504 124.713 8.00296C166.613 8.98465 198.563 23.0407 215.921 32.4242C217.113 33.0681 218.541 33.0744 219.733 32.437C232.699 25.5014 260.493 13.0963 297.427 14.3649C333.03 15.5952 359.242 28.9182 372.093 36.759C373.706 37.7407 375.784 37.4729 377.091 36.1088C385.589 27.248 416.34 -0.755805 461.517 4.16542C504.788 8.88266 528.578 40.2459 535.609 51.1083C536.795 52.9378 539.211 53.4924 541.079 52.3641C559.597 41.1065 596.379 22.5754 645.77 21.1666C713.711 19.2351 762.254 50.9426 779.554 63.7173C780.88 64.699 782.665 64.7628 784.068 63.8958C804.097 51.427 849.121 27.4584 910.923 26.6042C975.862 25.7117 1023.43 50.8278 1043.92 63.4815C1045.63 64.5397 1047.84 64.1636 1049.13 62.6209C1056.18 54.1426 1066.36 43.5161 1080.21 33.3995C1108.96 12.427 1162.52 0.417129 1171.33 0.0920227C1244.09 -2.57895 1292.81 53.7219 1299.15 61.2822C1299.87 62.4679 1301.18 63.1627 1302.54 63.0863C1304.45 62.9779 1305.44 61.4225 1305.52 61.2822H1305.53Z" fill="currentColor"></path></svg>
                    </div>
                </div>
                <div class="top-section-border-inner-wrapper">
                    <div class="contents md:hidden">
                        <svg width="1485" height="61" viewBox="0 0 1485 61" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M868.351 41.8509C872.575 36.8107 905.056 -0.723208 953.563 1.05744C959.436 1.27418 995.138 9.28073 1014.3 23.2667C1023.54 30.0111 1030.32 37.0954 1035.02 42.7476C1035.88 43.7761 1037.36 44.0268 1038.5 43.3213C1052.16 34.8813 1083.87 18.1372 1127.16 18.7365C1168.36 19.3059 1198.38 35.2808 1211.73 43.5976C1212.67 44.1798 1213.86 44.133 1214.74 43.4786C1226.28 34.9578 1258.64 13.8195 1303.93 15.1114C1336.86 16.0506 1361.38 28.4047 1373.72 35.9097C1374.97 36.6662 1376.58 36.2965 1377.37 35.0725C1382.06 27.8309 1397.92 6.92211 1426.77 3.77729C1456.88 0.492224 1477.38 19.1657 1483.05 25.0729C1483.92 25.9823 1485.31 26.1608 1486.38 25.5063C1494.95 20.2791 1512.42 11.3971 1536.16 10.5769C1560.78 9.72696 1579.31 18.0012 1587.95 22.625C1588.75 23.05 1589.7 23.0457 1590.5 22.6165C1602.07 16.3608 1623.37 6.98586 1651.3 6.33565C1692.1 5.37945 1721.63 23.6237 1733 31.6642C1733.87 32.2804 1735.02 32.3187 1735.93 31.7705C1736.99 31.1288 1738.08 30.4913 1739.21 29.8623C1741.68 28.4812 1746.56 25.8166 1754.44 22.7865C1784.29 11.3164 1806.48 10.1392 1806.48 10.1392C1806.48 10.1392 1836.31 8.55827 1873.44 22.7865C1881.18 25.7528 1886.65 27.8479 1888.67 29.8623C1888.97 30.1556 1890.35 30.8015 1891.95 31.7705C1892.86 32.3229 1894.01 32.2804 1894.88 31.6642C1906.26 23.6237 1935.78 5.3837 1976.58 6.33565C2004.51 6.99011 2025.81 16.3608 2037.38 22.6165C2038.18 23.0457 2039.13 23.05 2039.93 22.625C2048.57 18.0012 2067.1 9.73121 2091.72 10.5769C2115.46 11.3971 2132.93 20.2791 2141.5 25.5063C2142.57 26.1608 2143.96 25.9823 2144.83 25.0729C2150.5 19.1657 2171 0.496474 2201.11 3.77729C2229.96 6.92211 2245.82 27.8309 2250.51 35.0725C2251.3 36.2922 2252.91 36.662 2254.16 35.9097C2266.5 28.4047 2291.02 16.0506 2323.95 15.1114C2369.24 13.8237 2401.61 34.962 2413.14 43.4786C2414.03 44.133 2415.22 44.1755 2416.15 43.5976C2429.51 35.285 2459.52 19.3059 2500.72 18.7365C2544.02 18.1415 2575.73 34.8856 2589.39 43.3213C2590.52 44.0268 2592 43.7761 2592.86 42.7476C2597.56 37.0954 2604.35 30.0111 2613.58 23.2667C2623.75 15.8509 2646.15 2.06464 2674.32 1.05744C2722.89 -0.680711 2755.26 36.7512 2759.53 41.8509V60.9961H-22V22.7822C-17.2148 24.5799 -12.0768 26.8832 -6.7646 29.8581C-5.63841 30.487 -4.54622 31.1245 -3.48378 31.7662C-2.57434 32.3187 -1.42265 32.2762 -0.555696 31.66C10.8209 23.6194 40.344 5.37945 81.1417 6.3314C109.075 6.98586 130.375 16.3566 141.947 22.6122C142.742 23.0415 143.694 23.0457 144.489 22.6207C153.133 17.997 171.662 9.72696 196.285 10.5727C220.02 11.3929 237.495 20.2749 246.062 25.5021C247.138 26.1565 248.523 25.978 249.394 25.0686C255.059 19.1614 275.56 0.492224 305.678 3.77304C334.525 6.91786 350.385 27.8267 355.073 35.0683C355.863 36.288 357.474 36.6577 358.719 35.9055C371.065 28.4004 395.586 16.0464 428.513 15.1072C473.807 13.8195 506.169 34.9578 517.703 43.4743C518.587 44.1288 519.777 44.1713 520.712 43.5933C534.065 35.2808 564.081 19.3017 605.282 18.7322C648.574 18.1372 680.286 34.8813 693.945 43.3171C695.084 44.0225 696.563 43.7718 697.421 42.7434C702.121 37.0912 708.908 30.0068 718.143 23.2625C737.309 9.28073 773.012 1.27418 778.885 1.05744C827.392 -0.723208 859.873 36.8107 864.097 41.8509C864.581 42.6414 865.452 43.1046 866.362 43.0536C867.633 42.9813 868.291 41.9444 868.347 41.8509H868.351Z" fill="currentColor"></path></svg>
                    </div>
                    <div class="hidden md:contents">
                        <svg width="4142" height="90" viewBox="0 0 4142 90" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1305.53 61.2822C1311.86 53.7219 1360.58 -2.57895 1433.34 0.0920227C1442.15 0.417129 1495.71 12.427 1524.45 33.4059C1538.31 43.5225 1548.48 54.149 1555.53 62.6273C1556.82 64.1699 1559.04 64.546 1560.75 63.4879C1581.24 50.8278 1628.8 25.7117 1693.74 26.6105C1755.54 27.4647 1800.57 51.427 1820.6 63.9022C1822 64.7755 1823.78 64.7054 1825.11 63.7237C1842.42 50.9426 1890.95 19.2351 1958.9 21.173C2008.29 22.5818 2045.07 41.1129 2063.59 52.3705C2065.45 53.5052 2067.87 52.9506 2069.06 51.1147C2076.09 40.2523 2099.88 8.88903 2143.15 4.17179C2188.33 -0.755805 2219.07 27.2544 2227.57 36.1151C2228.88 37.4793 2230.96 37.747 2232.57 36.7653C2245.43 28.9245 2271.64 15.6015 2307.24 14.3712C2344.17 13.0963 2371.97 25.5077 2384.93 32.4433C2386.12 33.0808 2387.55 33.0744 2388.74 32.4306C2406.1 23.0471 2438.05 8.98465 2479.95 8.00933C2541.16 6.57504 2585.44 33.9414 2602.5 46.0022C2603.81 46.9265 2605.53 46.9839 2606.89 46.1616C2608.48 45.199 2610.12 44.2428 2611.81 43.2994C2615.52 41.2276 2622.85 37.2307 2634.67 32.6856C2679.43 15.4804 2712.72 13.7146 2712.72 13.7146C2712.72 13.7146 2757.46 11.3433 2813.16 32.6856C2824.77 37.1351 2832.97 40.2778 2836.01 43.2994C2836.45 43.7392 2838.53 44.7082 2840.93 46.1616C2842.29 46.9903 2844.02 46.9265 2845.32 46.0022C2862.39 33.9414 2906.67 6.58141 2967.87 8.00933C3009.77 8.99103 3041.72 23.0471 3059.08 32.4306C3060.27 33.0744 3061.7 33.0808 3062.89 32.4433C3075.85 25.5077 3103.65 13.1027 3140.58 14.3712C3176.19 15.6015 3202.4 28.9245 3215.25 36.7653C3216.86 37.747 3218.94 37.4793 3220.25 36.1151C3228.74 27.2544 3259.5 -0.74943 3304.67 4.17179C3347.94 8.88903 3371.73 40.2523 3378.76 51.1147C3379.95 52.9442 3382.37 53.4988 3384.23 52.3705C3402.75 41.1129 3439.53 22.5818 3488.92 21.173C3556.87 19.2415 3605.41 50.9489 3622.72 63.7237C3624.04 64.7054 3625.83 64.7692 3627.23 63.9022C3647.26 51.4334 3692.28 27.4647 3754.08 26.6105C3819.02 25.7181 3866.59 50.8342 3887.08 63.4879C3888.79 64.546 3891.01 64.1699 3892.29 62.6273C3899.34 54.149 3909.52 43.5225 3923.38 33.4059C3938.62 22.2822 3972.23 1.60281 4014.48 0.0920227C4087.34 -2.51521 4135.89 53.6327 4142.3 61.2822V90L-30 90L-30 32.6792C-22.8222 35.3757 -15.1152 38.8307 -7.1469 43.293C-5.45762 44.2364 -3.81933 45.1926 -2.22567 46.1552C-0.8615 46.9839 0.866031 46.9202 2.16646 45.9958C19.2314 33.935 63.516 6.57504 124.713 8.00296C166.613 8.98465 198.563 23.0407 215.921 32.4242C217.113 33.0681 218.541 33.0744 219.733 32.437C232.699 25.5014 260.493 13.0963 297.427 14.3649C333.03 15.5952 359.242 28.9182 372.093 36.759C373.706 37.7407 375.784 37.4729 377.091 36.1088C385.589 27.248 416.34 -0.755805 461.517 4.16542C504.788 8.88266 528.578 40.2459 535.609 51.1083C536.795 52.9378 539.211 53.4924 541.079 52.3641C559.597 41.1065 596.379 22.5754 645.77 21.1666C713.711 19.2351 762.254 50.9426 779.554 63.7173C780.88 64.699 782.665 64.7628 784.068 63.8958C804.097 51.427 849.121 27.4584 910.923 26.6042C975.862 25.7117 1023.43 50.8278 1043.92 63.4815C1045.63 64.5397 1047.84 64.1636 1049.13 62.6209C1056.18 54.1426 1066.36 43.5161 1080.21 33.3995C1108.96 12.427 1162.52 0.417129 1171.33 0.0920227C1244.09 -2.57895 1292.81 53.7219 1299.15 61.2822C1299.87 62.4679 1301.18 63.1627 1302.54 63.0863C1304.45 62.9779 1305.44 61.4225 1305.52 61.2822H1305.53Z" fill="currentColor"></path></svg>
                    </div>
                </div>
            </div>
        </flickity-scroll>
    
    <div class="embed-content-inner flex flex-col gap-8 lg:gap-10">
        <div class="embed-content-content max-w-4xl mx-auto">
            
            
            
            
        </div>
        
    </div>
    <style data-shopify="">
        #shopify-section-template--21252938399979__embed_content_weiWjc {
            padding-bottom: 0px;
            padding-top: 0px;
            background-color:#ffffff;
        }
        
        #shopify-section-template--21252938399979__embed_content_weiWjc flickity-scroll svg {
            color: #0f3062 !important;
        }
        @media screen and (min-width: 1024px) {
            #shopify-section-template--21252938399979__embed_content_weiWjc {
                padding-bottom: 0px;
                padding-top: 0px;
            }
        }
    </style>
    
</div>

</section>
    </main>
