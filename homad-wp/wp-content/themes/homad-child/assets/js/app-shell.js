(function(){
  "use strict";

  // Toggle menú móvil (requiere .homad-menu-toggle y .homad-mobile-nav)
  const menuButton=document.querySelector(".homad-menu-toggle");
  const mobileNav=document.querySelector(".homad-mobile-nav");
  if(menuButton && mobileNav){
    menuButton.addEventListener("click",()=>{
      mobileNav.classList.toggle("is-open");
      document.body.classList.toggle("no-scroll",mobileNav.classList.contains("is-open"));
    });
  }

  // Placeholder para slider del hero (extiende con Swiper.js o similar)
})();
