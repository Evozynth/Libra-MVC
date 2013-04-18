<?php
/**
 * Helpers for theming, available for all themes in their template files and functions.php.
 * This file is included right before the themes own functions.php.
 */

/**
 * Create a url by prepending the base_url.
 */
function base_url($url) {
    return CLibra::Instance()->request->base_url . trim($url, '/');
}
