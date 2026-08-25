---
module: ph-child
owner: UNKNOWN
---

# ph-child

## Responsibility

- Owns the client side of the connection contract: storing the six connection options the parent site writes, deciding whether a given viewer gets the feedback widget, and emitting one signed `<script>` tag pointing at the parent.
- Ownership stops there. Reviewer identity, login, comment storage, pin placement and the widget UI itself all belong to the parent site. There is no reviewer authentication in this plugin and no server-to-server call — every crossing is browser-side.

## Why it is this way

- The line count is misleading, and that is the main argument for documenting it. The *contract* is not in the code: six option names, an HMAC input string that includes a `%2B` substitution a well-meaning cleanup would delete, two write paths with asymmetric sanitisation, a version pin recorded once in a changelog line, and a wp.org slug that is load-bearing in two places. None of that is discoverable by reading the main file top to bottom, and half of it lives in a repo the reader may not have.
- It ships to third-party production sites, which changes the calculus on anything that runs per request. Code that is invisible on a developer's local install is expensive on a client's cached, logged-in commerce site — so "runs on every front-end request" is recorded as a gotcha here where in a first-party app it would barely rate a mention.
- The connection is deliberately one-directional: the parent pushes options in over XML-RPC and this plugin never calls out. That keeps the client site cheap and firewall-friendly, and it is why the manual-paste textarea exists as a fallback for hosts where `xmlrpc.php` is blocked. It is also why the `sanitize_callback` entries are misleading — they only ever run on the fallback path.
- The token moved from a cookie to `localStorage` to survive full-page caching on client sites. That decision is why a cookie-reading branch still exists with nothing setting the cookie, and why revocation has to happen by rotating the token on the parent rather than by clearing anything here.
- The file shape argues for the doc too: 1,111 lines in one class with the settings API, white-labelling, XML-RPC whitelisting and the loader interleaved. The loader — the entire product — is the last seventy-five lines, and the two most consequential behaviours in the plugin live there.

## Related ADRs

None yet.
