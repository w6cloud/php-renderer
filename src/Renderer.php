<?php
/**
 * Renderer Class
 *
 * PHP version 7
 *
 * @category PHP_Class
 * @package  W6/Renderer
 * @author   WEB6 <contact@web6.fr>
 * @license  https://web6.fr Private License
 * @version  GIT: <git_id>
 * @link     https://github.com/web6-fr/php-renderer/
 * @since    1.0
 */

namespace W6\Renderer;

use \W6\Renderer\RendererTrait;

use const \W6\Renderer\BASE_PATH;

/**
 * Renderer Class
 *
 * Permet le rendu de templates.
 *
 * @category PHP_Class
 * @package  W6/Renderer
 * @author   WEB6 <contact@web6.fr>
 * @license  https://web6.fr Private License
 * @link     https://github.com/web6-fr/php-renderer/
 */
class Renderer
{
    use RendererTrait;

    /**
     * Constructor
     *
     * @param array $options Les options globales du renderer.
     */
    public function __construct($options = [])
    {
        $this->rendererOptions = $options + $this->rendererOptions;
    }
}
