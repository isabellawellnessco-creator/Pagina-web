# Auditoría técnica (Phase 0)

## Endpoints AJAX (admin-ajax.php)

| Acción | Archivo/clase |
| --- | --- |
| `sk_theme_install_plugin` | `skincare-theme/inc/plugin-setup.php` |
| `sk_add_to_wishlist` | `bundled-plugins/skincare-site-kit/inc/modules/class-wishlist.php` |
| `sk_remove_from_wishlist` | `bundled-plugins/skincare-site-kit/inc/modules/class-wishlist.php` |
| `sk_get_wishlist_count` | `bundled-plugins/skincare-site-kit/inc/modules/class-wishlist.php` |
| `sk_contact_submit` | `bundled-plugins/skincare-site-kit/inc/modules/class-forms.php` |
| `sk_stock_notify` | `bundled-plugins/skincare-site-kit/inc/modules/class-stock-notifier.php` |
| `sk_ajax_search` | `bundled-plugins/skincare-site-kit/inc/modules/class-ajax-search.php` |
| `sk_switch_currency` | `bundled-plugins/skincare-site-kit/inc/modules/class-localization.php` |
| `sk_switch_language` | `bundled-plugins/skincare-site-kit/inc/modules/class-localization.php` |
| `sk_redeem_points` | `bundled-plugins/skincare-site-kit/inc/modules/class-rewards.php` |
| `sk_filter_products` | `bundled-plugins/skincare-site-kit/inc/modules/class-filter-handler.php` |
| `sk_send_test_email` | `bundled-plugins/skincare-site-kit/inc/modules/class-notifications.php` |
| `sk_drawer_update` | `bundled-plugins/skincare-site-kit/inc/modules/class-cart-drawer.php` |
| `sk_apply_coupon` | `bundled-plugins/skincare-site-kit/inc/modules/class-cart-drawer.php` |
| `sk_update_cart_item` | `bundled-plugins/skincare-site-kit/inc/modules/class-cart-drawer.php` |
| `sk_set_policy_status` | `bundled-plugins/skincare-site-kit/inc/modules/class-cart-drawer.php` |
| `sk_onboarding_run_step` | `bundled-plugins/skincare-site-kit/inc/admin/class-admin-onboarding.php` |
| `sk_add_stock` | `bundled-plugins/skincare-site-kit/inc/admin/class-stock-manager.php` |
| `sk_search_products` | `bundled-plugins/skincare-site-kit/inc/admin/class-stock-manager.php` |
| `sk_update_op_status` | `bundled-plugins/skincare-site-kit/inc/admin/class-operations-core.php` |
| `sk_migration_dry_run` | `bundled-plugins/skincare-site-kit/inc/admin/class-migration-center.php` |
| `sk_migration_apply` | `bundled-plugins/skincare-site-kit/inc/admin/class-migration-center.php` |
| `sk_log_whatsapp_click` | `bundled-plugins/skincare-site-kit/inc/admin/class-whatsapp-context.php` |

## Endpoints REST

| Ruta | Método | Archivo/clase |
| --- | --- | --- |
| `/skincare/v1/rewards` | GET | `bundled-plugins/skincare-site-kit/inc/core/class-rest-controller.php` |
| `/skincare/v1/rewards/redeem` | POST | `bundled-plugins/skincare-site-kit/inc/core/class-rest-controller.php` |
| `/skincare/v1/forms/contact` | POST | `bundled-plugins/skincare-site-kit/inc/core/class-rest-controller.php` |
| `/skincare/v1/quote` | POST | `bundled-plugins/skincare-site-kit/inc/core/class-rest-controller.php` |
| `/skincare/v1/cart/coupon` | POST | `bundled-plugins/skincare-site-kit/inc/core/class-rest-controller.php` |

## Ubicación de la lógica por módulo

- Drawer cart: `bundled-plugins/skincare-site-kit/inc/modules/class-cart-drawer.php`, JS `bundled-plugins/skincare-site-kit/assets/js/cart-drawer.js`.
- Wishlist: `bundled-plugins/skincare-site-kit/inc/modules/class-wishlist.php`, widget `bundled-plugins/skincare-site-kit/inc/widgets/class-wishlist-grid.php`, JS `bundled-plugins/skincare-site-kit/assets/js/site-kit.js`.
- Stock notifier: `bundled-plugins/skincare-site-kit/inc/modules/class-stock-notifier.php`, JS `bundled-plugins/skincare-site-kit/assets/js/site-kit.js`.
- Tracking: `bundled-plugins/skincare-site-kit/inc/modules/class-tracking-manager.php`, settings `bundled-plugins/skincare-site-kit/inc/admin/class-tracking-settings.php`.
- Rewards: `bundled-plugins/skincare-site-kit/inc/modules/class-rewards.php`, admin `bundled-plugins/skincare-site-kit/inc/admin/class-rewards-admin.php`, REST `bundled-plugins/skincare-site-kit/inc/core/class-rest-controller.php`.
- Shipping table: widget `bundled-plugins/skincare-site-kit/inc/widgets/class-shipping-table.php`.
- Store locator: widget `bundled-plugins/skincare-site-kit/inc/widgets/class-store-locator.php`.
- Swatches: `bundled-plugins/skincare-site-kit/inc/modules/class-swatches.php`.

## Mismatches críticos Front ↔ Back

- `assets/js/site-kit.js` usa REST para wishlist (`wishlist/add`) y stock notifier (`stock-notify`), pero no existen rutas REST equivalentes; sólo existen acciones AJAX (`sk_add_to_wishlist`, `sk_remove_from_wishlist`, `sk_stock_notify`).
- Wishlist: no hay render del botón en templates PHP (depende de HTML externo/Elementor), por lo que el toggle puede no estar presente en catálogo/PDP si no se inyecta.

