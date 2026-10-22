<?php
/**
 * test_connection.php  -  troubleshooting helper. DELETE before final deployment.
 */
echo '<pre>';
echo 'PHP version: ' . PHP_VERSION . "\n";
echo 'pdo_sqlsrv loaded: ' . (extension_loaded('pdo_sqlsrv') ? 'YES' : 'NO') . "\n";
require __DIR__ . '/includes/db_config.php';
echo 'Connected as: ' . $pdo->query('SELECT SUSER_NAME()')->fetchColumn() . "\n";
echo 'Task rows: '    . $pdo->query('SELECT COUNT(*) FROM dbo.Tasks')->fetchColumn() . "\n";
echo '</pre>';
