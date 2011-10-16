<?php
/**
 * Generic.php
 *
 * @author Keisuke SATO <riaf@me.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @package    Ethna
 * @version    $Id$
 */

/**
 * Ethna_Plugin_Twig_Generic
 *
 * @author Keisuke SATO <riaf@me.com>
 * @package    Ethna
 */
class Ethna_Plugin_Twig_Generic extends Twig_Extension
{
    public function getFunctions()
    {
        return array(
            'url' => new Twig_Function_Function('ethna_twig_url'),
        );
    }

    public function getName()
    {
        return 'generic';
    }
}

function ethna_twig_url(array $params=array())
{
    $action = $path = $path_key = null;
    $query = $params;

    foreach (array('action', 'anchor', 'scheme') as $key) {
        if (isset($params[$key])) {
            ${$key} = $params[$key];
        } else {
            ${$key} = null;
        }
        unset($query[$key]);
    }

    $c = Ethna_Controller::getInstance();
    $config = $c->getConfig();
    $url_handler = $c->getUrlHandler();
    list($path, $path_key) = $url_handler->actionToRequest($action, $query);

    if ($path != "") {
        if (is_array($path_key)) {
            foreach ($path_key as $key) {
                unset($query[$key]);
            }
        }
    } else {
        $query = $url_handler->buildActionParameter($query, $action);
    }
    $query = $url_handler->buildQueryParameter($query);

    $url = sprintf('%s%s', $config->get('url'), $path);

    if (preg_match('|^(\w+)://(.*)$|', $url, $match)) {
        if ($scheme) {
            $match[1] = $scheme;
        }
        $match[2] = preg_replace('|/+|', '/', $match[2]);
        $url = $match[1] . '://' . $match[2];
    }

    $url .= $query ? "?$query" : "";
    $url .= $anchor ? "#$anchor" : "";

    return $url;
}

