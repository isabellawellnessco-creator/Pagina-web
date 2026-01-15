# Guía UX/UI — Skincare Site Kit

## Componentes UI (reutilizables)

> Todos los componentes usan el prefijo `sk-` y viven en `assets/css/site-kit.css`.

### Card
- **Clase:** `.sk-card`
- **Uso:** contenedores principales para módulos de cuenta, tracking y recompensas.

### Badge de estado
- **Clases:** `.sk-badge`, `.sk-badge--status`, `.sk-badge--neutral`
- **Uso:** estados de pedidos y mensajes cortos.

### Alertas
- **Clases:** `.sk-alert`, `.sk-alert--success`, `.sk-alert--error`
- **Uso:** mensajes de info, éxito o error para canjes y feedback.

### Empty State
- **Clases:** `.sk-empty-state`, `.sk-empty-state--compact`, `.sk-empty-state__icon`
- **Uso:** nunca dejar vistas vacías (pedidos, puntos, historial).

### Loader
- **Clases:** `.sk-loader`, `.sk-btn--loading`, `.is-loading`
- **Uso:** feedback inmediato en acciones asíncronas (canje de puntos, cambios de pestaña si aplica).

### Stepper / Timeline
- **Clases:** `.sk-stepper`, `.sk-step`, `.sk-step__dot`, `.is-active`, `.is-complete`
- **Uso:** visualización del tracking por etapas en pedidos.

### Tabs (account)
- **Clases:** `.sk-tab-btn`, `.sk-tab-pane`
- **Uso:** navegación interna del panel de cuenta sin recarga.

---

## Guía de pruebas UX (manual)

1. **Mi Cuenta (tabs)**
   - Cambiar entre “Mis Pedidos”, “Direcciones”, “Mis Puntos”.
   - Verificar transición suave y estado activo visible.

2. **Tracking por pedido**
   - Validar que el stepper muestre el estado actual.
   - Confirmar que “En camino” se active al tener número o URL de tracking.

3. **Puntos y canje**
   - Con más de 500 puntos: click en “Canjear 500 pts”.
   - Ver loader en el botón.
   - Validar estado success con cupón visible.
   - Validar estado error sin recarga completa.

4. **Empty states**
   - Usuario sin pedidos → mostrar guía visual con CTA.
   - Usuario sin historial de puntos → mostrar empty state con explicación.

5. **Mobile-first**
   - Revisar que tarjetas, stepper y tabs se apilen correctamente.
   - Verificar botones accesibles (tamaño y legibilidad).

---

## Checklist QA (funcional + visual)

### Funcional
- [ ] Tabs cambian sin recargar la página.
- [ ] Canje de puntos usa AJAX y muestra estados (loading/success/error).
- [ ] Tracking muestra timeline coherente con datos del pedido.
- [ ] Links externos (tracking) abren en nueva pestaña.

### Visual
- [ ] Consistencia con colores/tipografías del tema.
- [ ] Espaciado uniforme entre cards y secciones.
- [ ] Badges y alerts legibles en desktop y mobile.
- [ ] Empty states visibles y comprensibles.
