<?php

// Database config
define('DB_HOST', 'piikl.com');
define('DB_USER', 'creature');
define('DB_PASSWORD', 'creature');

// Users
const CLEARANCE_LEVELS = ['EMPLOYEE'=>1, 'ADVANCED_EMPLOYEE'=>6, 'ADMIN'=>9];
const CLEARANCE_LEVELS_REV = [1=>'EMPLOYEE', 6=>'ADVANCED_EMPLOYEE', 9=>'ADMIN'];
define('USERNAME_MIN_LENGTH', 5);
define('USERNAME_MAX_LENGTH', 60);
define('EMAIL_MAX_LENGTH', 255);
define('EMPLOYEE_CODE_LENGTH', 16);
define('TOKEN_LIFE', 60*60*24*30);
define('TOKEN_LENGTH', 32);

// Entity
define('ENTITY_NAME_MAX_LENGTH', 100);
define('ENTITY_NAME_MIN_LENGTH', 3);
define('DESCRIPTION_MIN_LENGTH', 3);

/**
 * Index Page
 */
define('ENTITIES_TO_DISPLAY', 9);
define('ENTITY_DESCRIPTION_CHAR_TRUNCATION', 100);

/**
 * Admin Page
 */
define('EDITS_TO_DISPLAY', 15);

/**
 * Entity Page
 */
define('DISPLAY_SIBLINGS', true);
define('DISPLAY_CHILDREN', true);

/**
 * Search Page
 */
define('DEFAULT_RESULTS_PER_PAGE', 10);
define('MINIMUM_RESULTS_PER_PAGE', 10);
define('MAXIMUM_RESULTS_PER_PAGE', 250);