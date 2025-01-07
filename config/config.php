<?php
$baseUrl = dirname($_SERVER['REQUEST_URI']);
if (strlen($baseUrl) > 1) {
    define('BASE_URL', $baseUrl . '/');
} else {
    define('BASE_URL', '/');
}
