(function () {
	const tabContainers = document.querySelectorAll('.sk-admin-tabs');
	if (tabContainers.length) {
		tabContainers.forEach((container) => {
			const tabs = container.querySelectorAll('.sk-admin-tab');
			const panels = container.parentElement.querySelectorAll('.sk-admin-panel');
			if (!tabs.length || !panels.length) {
				return;
			}
			tabs.forEach((tab) => {
				tab.addEventListener('click', () => {
					tabs.forEach((item) => item.classList.remove('is-active'));
					panels.forEach((panel) => panel.classList.remove('is-active'));
					tab.classList.add('is-active');
					const panel = document.getElementById(tab.dataset.target);
					if (panel) {
						panel.classList.add('is-active');
						panel.scrollIntoView({ behavior: 'smooth', block: 'start' });
					}
				});
			});
		});
	}

	const quickLinks = document.querySelectorAll('.sk-admin-quick-links a[href^="#"]');
	quickLinks.forEach((link) => {
		link.addEventListener('click', (event) => {
			const target = document.querySelector(link.getAttribute('href'));
			if (!target) {
				return;
			}
			event.preventDefault();
			target.scrollIntoView({ behavior: 'smooth', block: 'start' });
			const activeTab = document.querySelector(`.sk-admin-tab[data-target="${target.id}"]`);
			if (activeTab) {
				activeTab.click();
			}
		});
	});
})();
