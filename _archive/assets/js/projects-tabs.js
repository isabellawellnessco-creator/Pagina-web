(function(){
  "use strict";
  const tabs = document.querySelectorAll('#projects-tabs a');
  tabs.forEach(tab => {
    tab.addEventListener('click', function(event) {
      event.preventDefault();
      // Cambiar estado activo
      tabs.forEach(t => t.classList.remove('homad-nav-pill--active'));
      this.classList.add('homad-nav-pill--active');
      // Scroll suave a la sección objetivo
      const targetId = this.getAttribute('href');
      const targetSection = document.querySelector(targetId);
      if (targetSection) {
        targetSection.scrollIntoView({ behavior: 'smooth' });
      }
    });
  });
})();
