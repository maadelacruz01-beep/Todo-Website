<?php
/**
 * db_config.php
 * Creates one PDO connection ($pdo) that every page re-uses.
 * Uses the PDO_SQLSRV driver and a least-privilege SQL login.
 */

// ---- Connection settings (edit these) --------------------------------
$DB_SERVER   = 'localhost\SQLEXPRESS';  // host: 'localhost\SQLEXPRESS' or 'localhost' (default instance)
$DB_NAME     = 'TodoDB';
$DB_USER     = 'todo_app';
$DB_PASSWORD = '<YOUR_PASSWORD_HERE>';  // <-- hide this in your documentation!
// ------------------------------------------------------------------------

try {
    // TrustServerCertificate=1 lets ODBC Driver 18 accept SQL Server's
    // self-signed certificate (fine for a local lab, not for production).
    $pdo = new PDO(
        "sqlsrv:Server=$DB_SERVER;Database=$DB_NAME;TrustServerCertificate=1",
        $DB_USER,
        $DB_PASSWORD,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // throw errors
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // rows as arrays
        ]
    );
} catch (PDOException $e) {
    // Log the real error server-side; show a generic message to visitors.
    error_log('DB connection failed: ' . $e->getMessage());
    http_response_code(500);
    exit('Database connection failed. Check includes/db_config.php and the PHP error log.');
}

session_start(); // used for one-time "flash" messages after an action

/** Shortcut: safely print text inside HTML (prevents XSS). */
function e(string $text): string {
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}

/** Store a message to show on the next page load, then redirect home. */
function redirect_with(string $type, string $message): void {
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
    header('Location: index.php');
    exit;
}
