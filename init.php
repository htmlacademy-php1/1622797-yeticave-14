<?php
session_start();

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/functions/db.php';
require_once __DIR__ . '/functions/user.php';
require_once __DIR__ . '/functions/template.php';
require_once __DIR__ . '/functions/file.php';
require_once __DIR__ . '/functions/validate_login.php';
require_once __DIR__ . '/functions/validate_bets.php';
require_once __DIR__ . '/functions/validate_form.php';
require_once __DIR__ . '/functions/validate_signup.php';
require_once __DIR__ . '/functions/calculate.php';

$config = require_once __DIR__ . '/config.php';
$link = connect_db($config['db']);
