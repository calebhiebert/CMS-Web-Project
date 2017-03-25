<?php

// Database config
define('DB_HOST', 'piikl.com');
define('DB_USER', 'creature');
define('DB_PASSWORD', 'creature');

// Users
const CLEARANCE_LEVELS_REV = [1=>'EMPLOYEE', 6=>'ADVANCED_EMPLOYEE', 9=>'ADMIN'];
define('USERNAME_MIN_LENGTH', 5);
define('USERNAME_MAX_LENGTH', 60);
define('EMAIL_MAX_LENGTH', 255);
define('EMPLOYEE_CODE_LENGTH', 16);
define('TOKEN_LIFE', 60*60*24*30);
define('TOKEN_LENGTH', 32);

// Entity
const DISALLOWED_NAME_CHARS = ['/', '\\'];
define('ENTITY_NAME_MAX_LENGTH', 100);
define('ENTITY_NAME_MIN_LENGTH', 3);
define('DESCRIPTION_MIN_LENGTH', 3);

/**
 * Index Page
 */
define('ENTITIES_TO_DISPLAY', 11);
define('INDEX_IMAGE_DISPLAY_SIZE', 'medium');
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
define('IMAGE_DISPLAY_SIZE', 'medium');
define('BACKGROUND_IMAGE_SIZE', 'tiny');
define('SHOW_BACKGROUND_IMAGE', true);
define('BACKGROUND_BLUR_INTENSITY', 5);

/**
 * Search Page
 */
define('DEFAULT_RESULTS_PER_PAGE', 10);
define('MINIMUM_RESULTS_PER_PAGE', 10);
define('MAXIMUM_RESULTS_PER_PAGE', 250);

/**
 * Images
 */
define('IMAGE_LOCATION', '..'.DIRECTORY_SEPARATOR.'images');
define('IMAGE_EDIT_PAGE_IMAGE_SIZE', 'full');
const IMAGE_FILE_TYPES = ['png', 'jpg', 'jpeg', 'gif'];
// coresponds to the width of the image
const IMAGE_FILE_SIZES = ['tiny'=>50, 'medium'=>768, 'full'=>1200];