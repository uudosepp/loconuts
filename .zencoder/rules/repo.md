# Loconuts WordPress Project Overview

- **Stack**: WordPress (PHP), custom theme named "loconuts proov"
- **Key Theme Directory**: `/wp-content/themes/loconuts proov/`
- **Primary Files**:
  - `functions.php` — theme setup, enqueueing assets, social menu enhancements
  - `style.css` — theme stylesheet (check for branding, typography, layout)
  - Templates under `templates/`, `parts/` (if present)
- **Build & Tooling**: Standard WordPress theme pipeline, no custom build scripts detected yet
- **Environment**: Local development via LocalWP (PHP, MySQL, Apache/Nginx bundle)
- **Testing**: Manual testing through WordPress UI; no automated tests spotted

## Recommended Workflow
1. **Modify theme files** under `/wp-content/themes/loconuts proov/`.
2. **Clear caches** if using caching plugins or browser cache.
3. **Test via WP Admin** for content, menus, widgets.

## Notes
- Keep localization strings (`__(...)`, `_e(...)`) updated for translation support.
- Respect WordPress coding standards (spacing, escaping, naming).
- Confirm asset paths when enqueueing scripts/styles.