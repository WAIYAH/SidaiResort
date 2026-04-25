<?php declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/app/includes/init.php';
require_once APP_PATH . '/api/mpesa-initiate.php';
require_once APP_PATH . '/api/mpesa-status.php';

sidai_handle_mpesa_status_request();
