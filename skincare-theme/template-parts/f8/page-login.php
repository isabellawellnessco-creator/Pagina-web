<?php
/**
 * Reference markup from refs/f8/Acc.html.
 */
?>

<main id="MainContent" role="main" tabindex="-1">
      <section id="shopify-section-template--21252932796651__login_header_hqWjhr" class="shopify-section"><div class="login-header block w-full px-5 lg:px-10 xl:px-15">
    <div class="login-header-inner block max-w-5.5xl mx-auto relative py-9 lg:py-15 max-lg:!rounded-[30px]" style="border-radius:70px;background:#ffd9d4;">
        <div class="login-header-content flex flex-col gap-2.5 lg:gap-5 px-2.5 lg:px-10 xl:px-15 relative">
            
            
                <h2 class="h1 text-center font-extrabold" style="color:#0f3062;">Welcome to Skin Cupid.</h2>
            
            
                <p class="text-center font-normal text-base lg:text-lg-xl text mb-1.5" style="color:#0f3062;">Please register your account below.
            
            </p><div class="flex lg:flex-wrap items-center gap-2.5 w-full justify-center">
                
                <a class="btn btn btn-tertiary max-lg:!text-sm max-lg:!px-5" href="https://www.skincupid.co.uk/account/register" pa-marked="1">
                    Create account
                </a>
                <div class="btn btn !translate-y-0 pointer-events-none btn-primary max-lg:!text-sm max-lg:!px-5">
                    Login to your account
                </div> 
            </div>
        </div>
        
    </div>
    <style data-shopify="">
        #shopify-section-template--21252932796651__login_header_hqWjhr {
            padding-bottom: 0px;
            padding-top: 20px;
        }
        #shopify-section-template--21252932796651__login_header_hqWjhr .section-icon-wrapper {
            position: absolute;
            bottom: -32px;
            left: 5%;
        }
        @media screen and (min-width: 1024px) {
            #shopify-section-template--21252932796651__login_header_hqWjhr {
                padding-bottom: 0px;
                padding-top: 40px;
            }
        }
    </style>
</div>

</section><div id="shopify-section-template--21252932796651__login" class="shopify-section"><style>
    #rc_login{
        display: none !important;
    }
</style>
<div class="px-[1.25rem] m-auto max-w-xl py-12 lg:py-24 relative" x-data="{ form: window.location.hash ? window.location.hash.substring(1) : &#39;login&#39; }">
    <div x-show="form === &#39;login&#39;">
    <h1 class="sr-only">Login to your account</h1><form method="post" action="https://www.skincupid.co.uk/account/login" id="customer_login_form" accept-charset="UTF-8" data-login-with-shop-sign-in="true" data-np-intersection-state="observed"><input type="hidden" name="form_type" value="customer_login"><input type="hidden" name="utf8" value="✓"><div class="flex flex-col gap-5 w-full lg:gap-6">
            <div class="custom-field">
                <input type="email" name="customer[email]" id="CustomerEmail" autocomplete="email" autocorrect="off" autocapitalize="off" placeholder="Enter your email address" pattern=".*\S+.*" required="" data-np-intersection-state="observed" fdprocessedid="wd7zc9">
                <label for="CustomerEmail">Email Address</label>       
            </div><div class="custom-field">
                    <input type="password" value="" name="customer[password]" id="CustomerPassword" autocomplete="current-password" placeholder="Enter your password" pattern=".*\S+.*" minlength="5" required="" data-np-intersection-state="observed" fdprocessedid="ojj4ko">
                    <label for="CustomerPassword">Password</label>
                </div><button type="submit" class="self-center btn btn-primary" pa-marked="1" fdprocessedid="2ai5j">
                Login to your account
                <svg width="100%" viewBox="0 0 16 13" fill="none" xmlns="http://www.w3.org/2000/svg">
    <g id="Group 6466">
    <line id="Line 5" x1="0.224976" y1="6.6251" x2="14.225" y2="6.6251" stroke="currentColor" stroke-width="1.2"></line>
    <path id="Vector 8" d="M9.22498 0.975098L14.725 6.4751L9.22498 11.9751" stroke="currentColor" stroke-width="1.2"></path>
    </g>
</svg>
    
            </button>
            <button type="button" @click="form = &#39;recover&#39;; window.location.hash = &#39;recover&#39;" class="text-secondary text-base self-center nav-link !overflow-visible after:!-bottom-0.5 transition-300" pa-marked="1" fdprocessedid="6hc6vu">I've forgotten my password</button>
        </div><input type="hidden" name="login_with_shop[analytics_trace_id]" value="94992819-bfd4-44d7-b3ca-c6397695b0df"></form></div>
    
    <div x-show="form === &#39;recover&#39;" style="display: none;">
        <form method="post" action="https://www.skincupid.co.uk/account/recover" accept-charset="UTF-8"><input type="hidden" name="form_type" value="recover_customer_password"><input type="hidden" name="utf8" value="✓">
            <h2 class="mb-6 text-center capitalize">Reset password</h2>
            <p class="mb-6 text-base text-center">We will send you an email to reset your password.</p>
            
                <div class="flex flex-col gap-5 w-full lg:gap-6">
                    <div class="custom-field">
                        <input type="email" name="email" id="RecoverEmail" required="" placeholder="Enter your email address" autocapitalize="off" data-np-intersection-state="observed">
                        <label for="RecoverEmail">Email Address</label>
                    </div><button type="submit" class="self-center btn btn-primary" pa-marked="1">
                        Reset Password
                        <svg width="100%" viewBox="0 0 16 13" fill="none" xmlns="http://www.w3.org/2000/svg">
    <g id="Group 6466">
    <line id="Line 5" x1="0.224976" y1="6.6251" x2="14.225" y2="6.6251" stroke="currentColor" stroke-width="1.2"></line>
    <path id="Vector 8" d="M9.22498 0.975098L14.725 6.4751L9.22498 11.9751" stroke="currentColor" stroke-width="1.2"></path>
    </g>
</svg>
    
                    </button>
                    <button type="button" @click="form = &#39;login&#39;; window.location.hash = &#39;login&#39;" class="text-secondary text-base self-center nav-link !overflow-visible after:!-bottom-0.5 transition-300" pa-marked="1">Already have an account? <strong>Login to your account</strong></button>
                </div>
            
        </form>
    </div>
      <div id="loop_login" class="flex justify-center mt-6">
        <a href="https://www.skincupid.co.uk/a/loop_subscriptions/customer" pa-marked="1">Manage subscriptions</a>
      </div>
</div>


<script>
  document.addEventListener('DOMContentLoaded', function() {
    console.log('Login form script loaded');
    const loginForm = document.getElementById('customer_login_form');
    
    if (loginForm) {
      console.log('Login form found:', loginForm);
      loginForm.addEventListener('submit', (event) => {
        console.log('Form submit event triggered');
        event.preventDefault();
        const form = event.currentTarget;
        
        const returnToPath = new URLSearchParams(window.location.search).get('return_to');
        console.log('return_to parameter from URL:', returnToPath);
        
        if (returnToPath) {
          console.log('return_to found, adding hidden input');
          // Remove existing return_to input if present
          const existingReturnTo = form.querySelector('input[name="return_to"]');
          if (existingReturnTo) {
            console.log('Removing existing return_to input');
            existingReturnTo.remove();
          }
          
          const hiddenInput = document.createElement('input');
          hiddenInput.type = 'hidden';
          hiddenInput.name = 'return_to';
          hiddenInput.value = returnToPath;
          form.appendChild(hiddenInput);
          console.log('Added hidden input with return_to:', returnToPath);
          
          // Verify it was added
          const addedInput = form.querySelector('input[name="return_to"]');
          console.log('Verification - found return_to input:', addedInput, 'with value:', addedInput?.value);
        } else {
          console.log('No return_to parameter found in URL');
        }
        
        // Block actual submission for testing
        console.log('Form submission blocked for testing');
        // form.submit();
      });
    } else {
      console.log('Login form not found!');
    }
  });
</script>

<div style="clear:both"></div></div>
    </main>
