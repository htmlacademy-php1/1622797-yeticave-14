<?php

require_once __DIR__ . '/functions/validate_form.php';
require_once __DIR__ . '/functions/validate_signup.php';
require_once __DIR__ . '/functions/file.php';
require_once __DIR__ . '/functions/template.php';
require_once __DIR__ . '/functions/calculate.php';
require_once __DIR__ . '/functions/db.php';
require_once __DIR__ . '/data.php';
$config = require_once __DIR__ . '/config.php';
$link = connect_db($config['db']);
