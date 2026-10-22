# To-Do List (PHP + SQL Server + IIS)

## File structure
```
todo-app/
├── index.php            READ list + CREATE form
├── add.php              CREATE (insert)
├── edit.php             UPDATE (edit text / status)
├── toggle.php           UPDATE (one-click complete/reopen)
├── delete.php           DELETE
├── test_connection.php  troubleshooting helper (delete before final)
├── includes/
│   ├── db_config.php    PDO connection + helpers
│   ├── header.php
│   └── footer.php
└── sql/
    ├── 01_create_database.sql
    ├── 02_create_tables.sql
    ├── 03_sample_data.sql
    └── 04_create_app_user.sql
```

## Part A: Set up on your host machine

XAMPP is not needed for the database. XAMPP bundles MySQL, but this project uses
SQL Server. Use XAMPP only if you want its Apache + PHP; the simplest option is
PHP's built-in web server (Step 4).

1. **SQL Server Express** (free): install it, choose "Basic" or "Custom".
   - Enable **Mixed Mode** authentication (Windows + SQL logins) and set an `sa` password.
     Already installed? SSMS > right-click server > Properties > Security > "SQL Server and Windows Authentication mode", then restart the SQL Server service.
2. **SSMS** (SQL Server Management Studio): install it to run the scripts.
3. **PHP for Windows**: download from windows.php.net (or use XAMPP's PHP).
   - Copy `php.ini-development` to `php.ini`.
4. **Microsoft ODBC Driver 18 for SQL Server**: install it.
5. **Microsoft Drivers for PHP for SQL Server** (`pdo_sqlsrv`): download from Microsoft/GitHub.
   - Pick the DLL matching your **PHP version**, **x64**, and **TS** (XAMPP / php -S) or **NTS** (IIS FastCGI). Mismatches are the #1 cause of "driver not found".
   - Copy `php_pdo_sqlsrv_XX_ts_x64.dll` into PHP's `ext` folder and add this to `php.ini`:
     `extension=php_pdo_sqlsrv_XX_ts_x64.dll`
   - Verify: `php -m | findstr sqlsrv`
6. **Database**: in SSMS, run `sql/01` to `sql/04` in order. Edit the password in `04` first.
7. **App config**: edit `includes/db_config.php` (server name, password).
   - Server name: `localhost\SQLEXPRESS` for Express; `localhost` for a default instance.
8. **Run**: open a terminal in the `todo-app` folder:
   ```
   php -S localhost:8000
   ```
   Browse to `http://localhost:8000/test_connection.php`, then `http://localhost:8000/`.

## Part B: Moving to the Windows Server VM (later)
- Install IIS (with CGI), PHP **NTS** build, ODBC Driver 18, and the NTS `pdo_sqlsrv` DLL.
- Add a FastCGI handler mapping for `*.php` > `php-cgi.exe`.
- Install SQL Server Express on the VM and re-run the four SQL scripts.
- Copy `todo-app` to `C:\inetpub\todoapp`; give `IIS_IUSRS` read access.
- Allow ports 80 (and 1433 only if the DB is accessed remotely) in Windows Firewall.
- Remove `test_connection.php`.

## Security notes (for your report)
- Prepared statements on every query (SQL injection).
- Output escaped with `htmlspecialchars` (XSS).
- Create/update/delete use POST only.
- App login has only SELECT/INSERT/UPDATE/DELETE on `dbo.Tasks`.
- Possible improvements: CSRF tokens, HTTPS, login system.
