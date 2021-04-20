<?php
/**
 * Renderer Trait
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

require_once __DIR__ . DIRECTORY_SEPARATOR . 'constants.php';

use const \W6\Renderer\DS;
use const \W6\Renderer\RETURN_OUTPUT;
use const \W6\Renderer\BASE_PATH;
use const \W6\Renderer\DEBUG;
use const \W6\Renderer\COMMENTS;

/**
 * Renderer Trait
 *
 * Variables de configuration :
 * ----------------------------
 * Les variables de configuration sont (type -> valeur par défaut) :
 *
 * returnOutput (bool   -> false)
 *              Détermine si l'on doit retourner le contenu au lieu de l'afficher.
 * basePath     (string -> $_SERVER['DOCUMENT_ROOT'])
 *              Dossier dans lequel chercher le template (sans / final).
 * debug        (bool   -> false)
 *              Détermine si l'on doit afficher des messages d'erreur.
 * comments     (bool   -> $data['debug'])
 *              Afficher les commentaires de délimitation.
 *              Avec $data['debug'] = true, un dump des variables est ajouté.
 *
 * Configuration globale :
 * -----------------------
 * La configuration globale se fait par constantes.
 * Exemple de fichier de configuration :
 *     ```php
 *     <?php
 *     namespace W6\Renderer;
 *     const RETURN_OUTPUT = true;
 *     const BASE_PATH     = '/var/www/templates';
 *     const DEBUG         = false;
 *     const COMMENTS      = true;
 *     ```
 *
 * Configuration pour la classe :
 * ------------------------------
 * Il est possible d'appliquer des réglages spécifiques à la classe.
 * Ces réglages surchargent la configuration globale.
 *     ```php
 *     <?php
 *     Class Card {
 *         use \W6\Renderer\RendererTrait;
 *         protected $rendererOptions = [
 *              'returnOutput' => true,
 *              'basePath'     => '/var/www/tpl/cards',
 *              'debug'        => true,
 *              'comments'     => false
 *         ];
 *     }
 *     ```
 *
 * Configuration pour la méthode :
 * -------------------------------
 * Il est possible de surcharger ces réglages directement dans la méthode render :
 * $card->render('dark-card', [
 *      'title' => 'My card title',
 *      // ... autres variables puis configuration :
 *      'returnOutput' => true,
 *      'basePath'     => '/var/www/tpl/cards',
 *      'debug'        => true,
 *      'comments'     => false
 * ]);
 *
 * @category PHP_Class
 * @package  W6/Renderer
 * @author   WEB6 <contact@web6.fr>
 * @license  https://web6.fr Private License
 * @link     https://github.com/web6-fr/php-renderer/
 */
trait RendererTrait
{
    protected $rendererOptions = [
        'returnOutput' => RETURN_OUTPUT,
        'basePath'     => BASE_PATH,
        'debug'        => DEBUG,
        'comments'     => COMMENTS,
    ];

    /**
     * Renders a template
     *
     * @param string $path Path du template (sans l'extension php).
     * @param mixed  $data Variables à passer au template.
     *                     Certaines clés sont réservées à la configuration.
     *
     * @return string|void
     */
    public function render($path, $data = [])
    {
        // Tri des options et des variables.
        list($options, $data) = $this->parseRendererConfig($data);

        // Extraction des options.
        extract($options, \EXTR_OVERWRITE);

        // Uniformisation des slashs.
        $path = ltrim(str_replace('/', DS, $path) . '.php', DS);
        $basePath = rtrim(str_replace('/', DS, $basePath), DS);

        // Chemin du fichier à inclure.
        $fullPath = $basePath . DS . $path;

        // Check du fichier.
        if (!file_exists($fullPath)) {
            $msg = sprintf('File "%s" not found.', $fullPath);
            throw new \InvalidArgumentException($msg);
        }

        ob_start();

        // Commentaire ouvrant.
        if ($comments) {
            if ($debug) {
                echo "\n\n<!-- \nStart Template `$path`\n";
                var_dump(compact('options', 'data'));
                echo "\n-->\n\n";
            } else {
                echo "\n\n<!-- Start Template `$path` -->\n\n";
            }
        }
        
        // On extrait les variables
        extract($data, \EXTR_OVERWRITE);

        // On inclue le template
        include $fullPath;

        // Extraction des options.
        extract($options, \EXTR_OVERWRITE);

        // Commentaire fermant.
        if ($comments) {
            echo "\n\n<!-- End Template `$path` -->\n\n";
        }

        // Contenu parsé.
        $content = ob_get_clean();

        // Gestion return / echo
        if ($returnOutput) {
            return $content;
        } else {
            echo $content;
        }
    }

    /**
     * Sépare la configuration des valeurs à passer au template.
     *
     * @param mixed $data Les données à parser sous forme de tableau.
     *                    Si booléen, remplace $data['returnOutput']
     *
     * @return array Un tableau contenant les options et la configuration
     */
    protected function parseRendererConfig($data = []):array
    {
        // Traitement de $data = bool
        if (!is_array($data)) {
            $data = array(
                'returnOutput' => (bool) $data
            );
        }

        // On vérifie que data ne comporte aucune entrée "options".
        if (array_key_exists('options', $data)) {
            $msg = 'Key `options` is not allowed in $data.';
            throw new \InvalidArgumentException($msg);
        }

        // On sépare les options des données.
        $options = [];
        foreach ($this->rendererOptions as $key => $value) {
            if (array_key_exists($key, $data)) {
                $options[$key] = $data[$key];
                unset($data[$key]);
            }
        }

        // On fusionne les options de la méthode + classe + globales.
        $options = $options + $this->rendererOptions + [
            'returnOutput' => RETURN_OUTPUT,
            'basePath'     => BASE_PATH,
            'debug'        => DEBUG,
            'comments'     => COMMENTS,
        ];

        // On retourne les options et les données séparées.
        return [$options, $data];
    }
}
