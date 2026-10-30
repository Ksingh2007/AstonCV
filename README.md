# AstonCV

A PHP and MySQL web application where students can register, build a short CV profile and browse a directory of other programmers. Built as a university module project.

## Features
- Browse all CVs and search by name or key programming language
- View an individual CV in detail
- Register an account, log in and log out
- Update your own CV from a personal dashboard
- Contact page that opens the user's email app with a pre-filled message

## Technologies
- **Server side:** PHP, MySQL (MySQLi)
- **Client side:** HTML, CSS, JavaScript
- **Structure:** modular PHP, with shared `header.php`, `footer.php` and `db.php` files

## Security
- Prepared statements (MySQLi) to protect against SQL injection
- Passwords hashed with `password_hash()` and checked with `password_verify()`
- Output escaped with `htmlspecialchars()` to protect against XSS
- PHP sessions for login state

## Running it locally
1. Install a local server such as XAMPP, MAMP or WAMP.
2. Import `cvs.sql` into MySQL (it creates the `astoncv` database and the `cvs` table).
3. Copy `db.example.php` to `db.php` and add your own database username and password.
4. Put the project folder in your server's web directory and open `index.php` in your browser.

## Project files
| File | Purpose |
|------|---------|
| `index.php` | Directory of CVs with search |
| `view_cv.php` | Single CV view |
| `signup.php`, `login.php`, `logout.php` | Account handling |
| `dashboard.php` | Edit your own CV |
| `contact.php` | Contact form |
| `css/style.css`, `js/script.js` | Styling and client-side behaviour |
