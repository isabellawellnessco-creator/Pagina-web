(function () {
	const sendButton = document.getElementById('sk-send-test-email');
	const feedback = document.getElementById('sk-test-feedback');
	const previewButton = document.getElementById('sk-preview-whatsapp');
	const whatsappPreview = document.getElementById('sk-whatsapp-preview');

	if (!sendButton || !feedback) {
		return;
	}

	sendButton.addEventListener('click', () => {
		const email = document.getElementById('sk-test-email');
		const orderId = document.getElementById('sk-test-order');

		if (!email || !orderId) {
			return;
		}

		const formData = new FormData();
		formData.append('action', 'sk_send_test_email');
		formData.append('nonce', window.sk_admin_vars ? sk_admin_vars.nonce : '');
		formData.append('email', email.value);
		formData.append('order_id', orderId.value);
		formData.append('template', 'confirm');

		feedback.innerHTML = '<div class="sk-admin-report__card"><p>Enviando...</p></div>';

		fetch(ajaxurl, {
			method: 'POST',
			credentials: 'same-origin',
			body: formData,
		})
			.then((response) => response.json())
			.then((data) => {
				if (!data || !data.success) {
					feedback.innerHTML = `<div class="sk-admin-report__card sk-admin-report__card--error"><p>${data && data.data ? data.data.message : 'No se pudo enviar.'}</p></div>`;
					return;
				}
				feedback.innerHTML = `<div class="sk-admin-report__card"><p>${data.data.message}</p></div>`;
			})
			.catch(() => {
				feedback.innerHTML = '<div class="sk-admin-report__card sk-admin-report__card--error"><p>Error de conexión.</p></div>';
			});
	});

	if (previewButton && whatsappPreview) {
		previewButton.addEventListener('click', () => {
			const phoneInput = document.getElementById('sk-test-whatsapp');
			const templateField = document.querySelector('textarea[name="templates[whatsapp_confirm]"]');
			if (!templateField || !phoneInput) {
				return;
			}

			const placeholders = {
				'{order_number}': '1024',
				'{total}': 'S/ 120.00',
				'{customer_name}': 'Cliente Demo',
				'{carrier}': 'Transportista: Olva.',
				'{tracking_url}': 'Seguimiento: https://tracking.demo',
			};

			let message = templateField.value || '';
			Object.keys(placeholders).forEach((key) => {
				message = message.split(key).join(placeholders[key]);
			});

			whatsappPreview.innerHTML = `
				<div class="sk-admin-report__card">
					<p><strong>Vista previa</strong></p>
					<p>${message}</p>
					<p><em>Debug only: vista previa sin envío manual.</em></p>
				</div>
			`;
		});
	}
})();
