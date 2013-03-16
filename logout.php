<?

session_start();
session_destroy();
header('Location: /landing/?logout');
exit;

