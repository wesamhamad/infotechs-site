# Infotechs website redesign

Custom WordPress theme for [infotechs.co](https://infotechs.co) (Riyadh exhibition and conference organiser).

- `theme/` — the `infotechs` theme: GSAP ScrollTrigger + Lenis, bilingual (EN/AR, RTL), custom `event` post type, Gallery / Contacts / Join our team / About pages.
- `docker-compose.yml` — local stack (MariaDB + WordPress on http://localhost:8088 + wp-cli).
- `tools/export-static.py` — exports the running local site to `docs/` as a static copy (EN at `/`, AR at `/ar/`) for GitHub Pages.
- `docs/` — generated static preview. Forms do not submit in the static copy.

## Run locally

```bash
docker compose up -d
# admin: http://localhost:8088/wp-admin
```
