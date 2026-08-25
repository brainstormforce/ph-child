# SureFeedback Client Site (`ph-child`)

The **client side** of a two-plugin system. It stores the connection options the parent site writes, decides whether a given viewer gets the feedback widget, and emits one signed `<script>` tag pointing at the parent. Everything else — reviewer identity, login, comment storage, pin placement, the widget UI — belongs to the parent.

Read `architecture.md` before changing this plugin.

<!-- codedna:start — managed section. Edit freely; keep the markers so a
     teammate running `setup` updates this block instead of duplicating it. -->

The whole plugin is one module: five flat PHP files, no code subdirectories, so
this file *is* the module doc. Pinned in `.codedna/modules.json`.

**Never guess a shape.** Option names, query args, the HMAC input string, REST
namespaces and nonce actions are all written down here — open the definition and
use it. Half the contract lives in the parent repo and cannot be verified from
this one, so treat any change to a query arg or the signed string as a
cross-repo change.

**After changing code**, update the rules or gotchas below in the same change
when knowledge shifts, and put the reasoning in `architecture.md` instead when
it explains *why* rather than constraining an edit. Never both. Skip it for
renames, formatting, or refactors nothing observable depends on, and add no
timestamps. Rules and Gotchas are append-only on merge: keep both sides.
<!-- codedna:end -->

## Rules

- `PH_Child::$whitelist_option_names` (`ph-child.php:88-113`) is the **single definition of the connection contract** — `ph_child_id`, `ph_child_api_key`, `ph_child_access_token`, `ph_child_parent_url`, `ph_child_signature`, `ph_child_installed`. A new connection field must be added there or it is invisible to both write paths.
- `ph_child_id` is a **post ID in the parent's database**, not a local one — proven by the dashboard URL built at `ph-child.php:815`.
- **Two write paths with different sanitisation.** The parent writes over XML-RPC, where core's `wp_setOptions` calls `update_option()` directly, so **the `sanitize_callback` entries are bypassed entirely on that path**. They run only for the manual-connection textarea (`manual_import()`, `:708-722`). Treat every inbound connection option as unsanitised.
- Automatic connection therefore needs `xmlrpc.php` reachable on the client site; the manual textarea is the documented fallback.
- **The signature itself stays on the server — only HMACs of it.** `hash_hmac( 'sha256', 'guest', … )` for guests (`:1068`) and `hash_hmac( 'sha256', sanitize_email( str_replace( '+', '%2B', $user->user_email ) ), … )` for logged-in users (`:1074`). The parent must HMAC the *identical* `%2B`-substituted string.
- Exactly one REST namespace and route: `surefeedback/v1` `/pages` (`ph-child-rest-api.php:25`). GET is gated by `verify_access` using timing-safe `hash_equals` (`:45`); OPTIONS is deliberately open for preflight and returns nothing (`:31-35`). No `args` schema is declared, so `search` has no validation contract.
- **CORS is scoped by route prefix** — `if ( strpos( $route, '/surefeedback/' ) !== 0 ) return $served;` (`ph-child-rest-api.php:92`). Keep that check: `readme.txt:63-65` records that widening it broke OPTIONS for the WordPress and WooCommerce REST APIs and for Zapier.
- Exactly one AJAX action, no `nopriv`: nonce action `ph_child_dismiss_nonce` in field `nonce`, capability `manage_options` (`ph-child-functions.php:52,84,92`). Both notices that would trigger it are currently commented out, so the handler is unreachable.
- The parent is embedded as a **protocol-relative `<script>`** inserted before the first existing script tag (`ph-child.php:1082,1088-1105`), so it is fetched over the client page's scheme. This plugin sets no cookie, builds no iframe, and makes no server-side call to the parent — every crossing is browser-side.
- Version lockstep: `defined( 'PH_VERSION' )` is the parent-plugin sentinel and makes this plugin no-op with a notice (`:83-86`). The wp.org slug `projecthuddle-child-site` is hardcoded at `:243` **and** is the deploy slug in the GitHub workflow — renaming either silently kills the white-label plugin-row links. `PH_HIDE_WHITE_LABEL` is an externally-defined constant that hides a whole settings tab.

## Gotchas

- **`ph_user_data()` runs on every front-end request with no gate at all** (`ph-child.php:903-919`) — no connection check, no `$allowed` check, not even the `ph_script_should_start_loading` filter. It prints the current user's login and email into `window.PH_Child` using `json_encode` rather than `wp_json_encode`. On a client's membership or commerce site that means every page's HTML carries the logged-in customer's email, and a full-page cache that ignores login state can serve one user's to the next visitor. Nothing in this repo reads that global, so it cannot be removed from here without checking the parent.
- **Registration order is the only thing sequencing the two footer hooks.** `ph_user_data` and `script` are both on `wp_footer` at default priority (`:122`, `:126`); swap the two `add_action` calls and the parent's script can execute before its identity global exists.
- **`add_query_arg` does not urlencode values**, which is why the display name is hand-`urlencode()`d and the email's `+` is hand-replaced with `%2B` (`:1072-1074`, bug report at `readme.txt:164`). The HMAC is computed over the *substituted* string, so "tidying up" either the `urlencode` or the `str_replace` changes the signed value and every reviewer with a `+`-address fails verification on the parent.
- The `static $loaded` guard in `script()` (`:1033-1038`) exists because some themes call `wp_footer()` twice — remove it and the parent script is injected twice (`readme.txt:158`).
- **`ph-child.php:201` is `return false; // TODO: remove once we can get pageX, pageY inside iframe.`** so the twenty lines below it are dead: the widget is unconditionally off on any URL carrying `ct_builder`, and the `ph_child_admin`/`oxygen_iframe` logic reads as live behaviour but never runs. `phpstan-baseline.neon` globally ignores `Unreachable statement`, so nothing flags it.
- **`register_setting( 'ph_child_general_options', 'ph_child_enabled_comment_roles', 'ph_child_help_link' )`** (`:469`) passes a settings-field ID where a callable is expected. Core's `is_callable()` back-compat branch therefore stays unentered and the string is parsed as a query string — so the option deciding **who receives the access token** is stored with no sanitize callback at all. A guard that looks present and is not.
- While `ph_child_enabled_comment_roles` has never been saved, **every logged-in user is an allowed commenter** (`ph-child-functions.php:25-30`), and the settings UI renders every role pre-checked (`ph-child.php:735-737`) — so it looks like a stored allow-list that does not exist.
- **`maybe_disconnect()` checks a nonce but has no capability check**, passes `$_GET` to `wp_verify_nonce` unslashed, and calls `wp_redirect()` with **no `exit`** (`:332-347`) — so execution continues through the rest of `admin_init` and the whole admin page after the redirect header is queued.
- **Disconnect deletes only `$whitelist_option_names`** (`:342-344`). `ph_child_manual_connection` and all five white-label options survive it. And `uninstall.php` deletes eleven keys, **four of which this plugin never writes**, while leaving behind `ph_child_id`, `ph_child_admin`, `ph_child_enabled_comment_roles`, all five white-label keys and every `dismissed-*` option and site option.
- `has_valid_cookie()` reads `$_COOKIE['ph_access_token']` (`:1008-1024`) but **no code in this repo sets that cookie** — the token moved to `localStorage` (`readme.txt:131`) — and it compares with `===` rather than `hash_equals`, unlike the REST path. Treat the cookie branch as an unverifiable parent-side or legacy contract.
- The loader writes the token from the URL into `localStorage` and re-appends it on every later page load (`:1094-1095`), with no expiry and nothing clearing it on Disconnect or uninstall. One visit to an access link arms that browser indefinitely.
- Whether the manual-connection textarea is visible is decided by **three overlapping mechanisms** (`:786-811`, `:839-843`, `:886-896`): a `<style>` hiding the row, a second `<style>` un-hiding it when disconnected, and a jQuery snippet that adds the hiding class. Touch one and either the box vanishes on a broken site — leaving no way to reconnect — or it stays visible after connecting.
- `ph_custom_inline_script()` is on `admin_init` with no page check (`:120`, `:893`), so it enqueues on **every** wp-admin page of the client's site, and declares no jQuery dependency while the snippet is jQuery.
- The `gettext` filter is attached only when `is_admin() && 'plugins.php' === $pagenow`, read from the global at plugin-load time (`:157-161`, reasons at `readme.txt:161,170`). Widen that gate and `white_label()` runs for every translated string on every admin request.
- `compatiblity_blacklist()` loops **every** `$_GET` key with non-strict `in_array` on every front-end request (`:191-197`), and `phpcs.xml.dist` explicitly excludes the strict-comparison sniff, so the linter stays silent on it. A loosely-equal query var silently kills the widget; page-builder support is meant to be added through the `ph_disable_for_query_vars` filter, not by editing the array.
- `add_cors_headers()` registers a `rest_pre_serve_request` closure **inside itself** on `rest_api_init` (`ph-child-rest-api.php:89-103`), so a nested closure is added on every REST request.
- `register_activation_hook`/`register_deactivation_hook` are called in the constructor *after* the `PH_VERSION` early return (`:147-148`, `:83-86`) — so if the parent plugin is active at load time those hooks go unregistered and `ph_child_installed` stops tracking.
- The only diagnostics the loader emits are two HTML comments covering unset options (`:1047`, `:1052`); a scheme mismatch or a blocked script produces no signal at all.
- Hunt result: no `HACK`/`FIXME`/`XXX`/"do not remove" comments, exactly one `TODO` (above). No `setcookie`, no `SameSite`, no iframe, no `postMessage`, no transients, no cron, no `$wpdb`, and no `wp_remote_*` anywhere.

## After changes

- Rule, boundary or reasoning changed? Update `architecture.md` in the same change.
- Not for renames or formatting.
- Run: `composer lint` and the PHPUnit suite.
