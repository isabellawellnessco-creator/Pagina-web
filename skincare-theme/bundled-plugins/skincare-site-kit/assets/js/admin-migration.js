(function () {
	const form = document.getElementById('sk-migration-form');
	const applyButton = document.getElementById('sk-migration-apply');
	const report = document.getElementById('sk-migration-report');

	if (!form || !applyButton || !report) {
		return;
	}

	const renderList = (items, title) => {
		if (!items || !items.length) {
			return '';
		}
		return `
			<div class="sk-admin-report__section">
				<h4>${title}</h4>
				<ul>
					${items.map((item) => `<li>${item}</li>`).join('')}
				</ul>
			</div>
		`;
	};

	const renderPlugins = (plugins) => {
		if (!plugins || !plugins.length) {
			return '';
		}
		return `
			<div class="sk-admin-report__section">
				<h4>Plugins y tema</h4>
				<ul>
					${plugins
						.map(
							(plugin) =>
								`<li><span class="sk-admin-status sk-admin-status--${plugin.status}">${plugin.label}</span> ${plugin.message}</li>`
						)
						.join('')}
				</ul>
			</div>
		`;
	};

	const renderWarnings = (warnings) => {
		if (!warnings || !warnings.length) {
			return '';
		}
		return `
			<div class="sk-admin-report__section sk-admin-report__section--warn">
				<h4>Advertencias</h4>
				<ul>
					${warnings.map((item) => `<li>${item}</li>`).join('')}
				</ul>
			</div>
		`;
	};

	const renderReport = (data) => {
		report.innerHTML = `
			<div class="sk-admin-report__card">
				<p><strong>${data.dry_run ? 'Simulación lista' : 'Importación aplicada'}</strong></p>
				${renderList(data.changes, 'Cambios detectados')}
				${renderList(data.pages, 'Páginas WooCommerce')}
				${renderPlugins(data.plugins)}
				${renderWarnings(data.warnings)}
				${renderList(data.checklist, 'Checklist post-import')}
			</div>
		`;
	};

	const handleRequest = (action) => {
		const formData = new FormData(form);
		formData.set('action', action);
		report.innerHTML = '<div class="sk-admin-report__card"><p>Cargando...</p></div>';

		fetch(ajaxurl, {
			method: 'POST',
			credentials: 'same-origin',
			body: formData,
		})
			.then((response) => response.json())
			.then((data) => {
				if (!data || !data.success) {
					report.innerHTML = `<div class="sk-admin-report__card sk-admin-report__card--error"><p>${data && data.data ? data.data.message : 'No se pudo completar la acción.'}</p></div>`;
					return;
				}
				renderReport(data.data);
			})
			.catch(() => {
				report.innerHTML = '<div class="sk-admin-report__card sk-admin-report__card--error"><p>Error de conexión. Intenta de nuevo.</p></div>';
			});
	};

	form.addEventListener('submit', (event) => {
		event.preventDefault();
		handleRequest('sk_migration_dry_run');
	});

	applyButton.addEventListener('click', () => {
		handleRequest('sk_migration_apply');
	});
})();
