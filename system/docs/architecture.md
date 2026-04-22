# Architecture

## Stack
CodeIgniter 3 (PHP), MySQL/MariaDB, Bootstrap, jQuery, AJAX

## Structure
- controllers → request handling
- models → database logic
- views → UI (server-rendered)
- assets → JS/CSS/plugins

## Flow
Controller → Model → View → (AJAX → Controller)

## UI
- DataTables for lists
- Select2 for dropdowns
- Bootstrap layout
- Print = A4 server-rendered

## Rules
- Do not break controller → model → view flow
- Do not move logic across layers unnecessarily