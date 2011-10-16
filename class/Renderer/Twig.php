<?php
/**
 *  Twig.php
 *
 *  @author     Keisuke SATO <riaf@me.com>
 *  @license    http://www.opensource.org/licenses/bsd-license.php The BSD License
 *  @package    Ethna
 *  @version    $Id$
 */

/**
 *  Twig Renderer
 *
 *  @author     Keisuke SATO <riaf@me.com>
 *  @package    Ethna
 */
class Ethna_Renderer_Twig extends Ethna_Renderer
{
    /** @protected  string path of Twig_Autoloader */
    protected $engine_path = 'Twig/Autoloader.php';

    /**
     *  Constructor for Ethna_Renderer_Twig
     *
     *  @access public
     */
    public function __construct($controller)
    {
        parent::__construct($controller);

        $this->loadEngine($this->config);
        Twig_Autoloader::register();

        require_once 'Ethna/class/Plugin/Twig/Generic.php';
        require_once 'Ethna/class/Plugin/Twig/Form.php';

        $loader = new Twig_Loader_Filesystem($controller->getTemplatedir());

        $config = $this->ctl->getConfig();
        $this->engine = new Twig_Environment($loader, array(
            'charset' => isset($this->config['charset']) ? $this->config['charset'] : 'UTF-8',
            'base_template_class' => isset($this->config['base_template_class']) ? $this->config['base_template_class'] : 'Twig_Template',
            'strict_variables' => isset($this->config['strict_variables']) ? $this->config['strict_variables'] == 'true' : false,
            'autoescape' => isset($this->config['autoescape']) ? $this->config['autoescape'] == 'true' : false,
            'cache' => $controller->getDirectory('template_c'),
            'auto_reload' => isset($this->config['auto_reload']) ? $this->config['auto_reload'] : null,
            'optimizations' => isset($this->config['optimizations']) ? $this->config['optimizations'] : -1,
            'debug' => (bool) $config->get('debug'),
        ));

        $this->engine->addExtension(new Ethna_Plugin_Twig_Generic());
        $this->engine->addExtension(new Ethna_Plugin_Twig_Form());
    }

    /**
     *  Display the template
     *
     *  @param  string  $template   template name
     *  @param  bool    $capture    if true, not display but return as string
     *
     *  @access public
     */
    public function perform($template = null, $capture = false)
    {
        if ($template === null && $this->template === null) {
            return Ethna::raiseWarning('template is not defined');
        }

        if ($template !== null) {
            $this->template = $template;
        }

        try {
            if (true === $capture) {
                return $this->engine->render($this->template, $this->prop);
            } else {
                echo $this->engine->render($this->template, $this->prop);
            }
        } catch (Twig_Error $e) {
            return Ethna::raiseWarning("twig error: msg='{$e->getMessage()}'", 500);
        }
    }
}

