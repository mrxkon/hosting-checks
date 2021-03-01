## plugin-tpl

Re-creating the same setup every time is annoying so I thought of creating an easy to use (for me at least :D) template.

---

![Tests](https://github.com/mrxkon/plugin-tpl/workflows/Checks/badge.svg)
[![PHP Compatibility 7.0+](https://img.shields.io/badge/PHP%20Compatibility-7.0+-8892BF)](https://github.com/PHPCompatibility/PHPCompatibility)
[![WordPress Coding Standards](https://img.shields.io/badge/WordPress%20Coding%20Standards-latest-blue)](https://github.com/WordPress/WordPress-Coding-Standards)

#### Setup

`composer install && npm install`

Search and replace the instances of `plugin-tpl`

#### Available commands
- `npm run lint` - Runs all lints.
	- `php:lint` - Lints all `.php` files.
	- `css:lint` - Lints all css files inside the `css` directory.
	- `js:lint` - Lints all css files inside the `js` directory.
- `npm run fix` - Runs all fixes.
	- `php:fix`  - Fixes all `.php` files.
	- `css:fix` - Fixes any issues inside the `css` directory.
	- `js:fix` - Fixes any issues inside the `js` directory.
- `php:compat` - Checks all `.php` files inside the `src` directory for compatibility with PHP 7.0+.
- `npm run copy` - Copies `css`, `js`, `php`, `vendor` directories & `{plugin-name}.php` into `build/{plugin-name}` directory.
- `npm run watch` - Watches for file changes and runs `npm run copy`.
- `npm run build` - Runs `npm run copy` and creates a `{plugin-name}.zip` inside the `build` directory.
