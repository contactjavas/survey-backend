<?php

/**
 * Helper Constants
 *
 * APP_ABSPATH uses trailingslash
 * APP_BASE_DIR doesn't use trailingslash
 * APP_BASE_URL doesn't use trailingslash
 */

define('APP_ABSPATH', __DIR__ . '/../../');
define('APP_BASE_DIR', rtrim(APP_ABSPATH, '/'));
define('APP_BASE_URL', getBaseUrl());
