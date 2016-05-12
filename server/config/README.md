Application configs
==================

All file ending with .local suffix are not versioned and so every developer, web server can have different setting.
Config is cached by default on production environment. Don't forget to clean cache after deploy new version.
$env - is current application environment(production, development, testing, etc.)

Config is merged in order.

- *.global
- *.$env
- *.default.local
- *.$env.local