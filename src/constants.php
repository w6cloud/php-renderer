<?php
/**
 * Renderer Constants
 *
 * PHP version 7
 *
 * @category Constants
 * @package  W6/Renderer
 * @author   WEB6 <contact@web6.fr>
 * @license  https://web6.fr Private License
 * @version  GIT: <git_id>
 * @link     https://github.com/web6-fr/php-renderer/
 * @since    1.0
 */

namespace W6\Renderer;

const DS = DIRECTORY_SEPARATOR;

if (!defined('W6\Renderer\BASE_PATH')) {
    define(
        'W6\Renderer\BASE_PATH',
        $_SERVER['DOCUMENT_ROOT']
    );
}


if (!defined('W6\Renderer\RETURN_OUTPUT')) {
    define(
        'W6\Renderer\RETURN_OUTPUT',
        false
    );
}


if (!defined('W6\Renderer\DEBUG')) {
    define(
        'W6\Renderer\DEBUG',
        false
    );
}


if (!defined('W6\Renderer\COMMENTS')) {
    define(
        'W6\Renderer\COMMENTS',
        DEBUG
    );
}
