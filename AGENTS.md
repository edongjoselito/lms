# AGENTS.md

## Stack
PHP, CodeIgniter 3, MySQL/MariaDB, Bootstrap, jQuery, AJAX, DataTables, Select2

## Rules
- Fix only what is broken
- Do not rewrite working code
- Preserve controller → model → view flow
- Match existing naming and structure
- No new tables/fields unless required

## Debug
- Start from exact error
- Find root cause first

Check:
- undefined variables
- wrong routes / URI
- bad joins / conditions
- missing columns
- session / validation issues
- AJAX mismatch
- wrong filters (SY, Sem, Course, Section, Major)

## Backend
- Follow CodeIgniter 3 style
- Do not change schema unless needed
- If DB change needed → give exact SQL
- Keep backward compatible

## UI
- Do not break forms, modals, AJAX, DataTables, Select2
- Keep layout intact
- Preserve print (A4)

## Security
- validate inputs
- prevent SQL injection / XSS
- check access control

## Output
- Show full function if changed
- Separate files clearly
- If "code all" → full code
- Paste-ready only
- No long explanation

## Priority
1. Fix issue
2. Root cause
3. Keep compatibility
4. Minimal change