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

	const inlineForms = document.querySelectorAll('.sk-admin-inline-form');
	const toastRoot = document.createElement('div');
	toastRoot.className = 'sk-admin-toast-root';
	document.body.appendChild(toastRoot);

	const showToast = (message, type = 'success') => {
		const toast = document.createElement('div');
		toast.className = `sk-admin-toast sk-admin-toast--${type}`;
		toast.textContent = message;
		toastRoot.appendChild(toast);
		setTimeout(() => {
			toast.classList.add('is-visible');
		}, 10);
		setTimeout(() => {
			toast.classList.remove('is-visible');
			setTimeout(() => toast.remove(), 300);
		}, 2500);
	};

	const submitInlineForm = async (form) => {
		if (!window.skAdminDashboard?.ajaxUrl) {
			form.submit();
			return;
		}

		const formData = new FormData(form);
		formData.append('action', 'sk_operations_update_order');
		formData.append('nonce', window.skAdminDashboard.nonce || '');

		const buttons = form.querySelectorAll('button');
		buttons.forEach((button) => {
			button.disabled = true;
			button.classList.add('is-busy');
		});

		try {
			const response = await fetch(window.skAdminDashboard.ajaxUrl, {
				method: 'POST',
				credentials: 'same-origin',
				body: formData,
			});
			const data = await response.json();
			if (data.success) {
				showToast(data.data?.message || 'Actualizado');
			} else {
				showToast(data.data?.message || 'Error al guardar', 'error');
			}
		} catch (error) {
			showToast('Error al guardar', 'error');
		} finally {
			buttons.forEach((button) => {
				button.disabled = false;
				button.classList.remove('is-busy');
			});
		}
	};

	inlineForms.forEach((form) => {
		form.addEventListener('submit', (event) => {
			event.preventDefault();
			submitInlineForm(form);
		});

		form.querySelectorAll('select, input[type="text"], input[type="date"]').forEach((field) => {
			field.addEventListener('change', () => {
				submitInlineForm(form);
			});
		});
	});
})();
