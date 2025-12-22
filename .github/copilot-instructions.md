# Copilot Instructions for Sugeng Rahayu Bus Booking System

## Architecture Overview
This is a PHP-based bus transportation booking system using MySQL database. The application follows a procedural PHP structure with included header/footer files for consistent UI. Key components:
- **Authentication**: Session-based login/logout with role-based access (user/admin)
- **Booking Flow**: Search schedules → Select seats → Process payment → Confirmation
- **Admin Panel**: Manage schedules, bookings, and users
- **User Management**: Profile editing, booking history

## Key Patterns
- **Database Connection**: Always include `../koneksi.php` or `../config.php` for DB access
- **Session Management**: Use `is_logged_in()` and `is_admin()` from `includes/functions.php` for access control
- **Flash Messages**: Use `set_flash('success/error', 'message')` and `display_flash()` for user notifications
- **Currency Formatting**: Use `format_currency($amount)` for Rupiah display
- **Date Formatting**: Use `format_date($date)` for Indonesian date format
- **Color Palette**: Define colors in header.php (`$primary_blue = "#001BB7"`, etc.) for consistent theming

## Developer Workflows
- **Local Development**: Run via XAMPP (localhost/UAS), ensure MySQL database "sugeng" is created
- **Database Setup**: Import schema for tables: users, schedules, bookings (check existing queries for structure)
- **Testing**: Manual browser testing; no automated tests present
- **Deployment**: Upload files to web server, update `SITE_URL` in config.php

## Code Conventions
- **File Structure**: Place PHP logic at top, HTML below; use `include` for header/footer
- **Security**: Use `mysqli_real_escape_string()` for inputs; `password_hash()` for passwords
- **Transactions**: Use `mysqli_begin_transaction()` for multi-step DB operations (e.g., booking process)
- **Error Handling**: Display errors in development via config.php settings
- **Booking Codes**: Generate unique codes like "SR-" + uppercase MD5 hash

## Examples
- **User Authentication Check**: `if (!is_logged_in()) { header("Location: ../signin.php"); exit; }`
- **DB Query**: `$user = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM users WHERE id = '$user_id'"));`
- **Form Processing**: Check `$_SERVER['REQUEST_METHOD'] == 'POST'` before processing
- **Redirect with Message**: `set_flash('success', 'Updated!'); header("Location: profile.php");`

## Integration Points
- **Email**: Configured in config.php for future SMTP integration
- **Uploads**: Path defined in config.php for file uploads (images)
- **Pagination**: Use `ITEMS_PER_PAGE` constant for listing pages