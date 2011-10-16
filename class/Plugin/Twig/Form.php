<?php
/**
 * Form.php
 *
 * @author Keisuke SATO <riaf@me.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @package    Ethna
 * @version    $Id$
 */

/**
 * Ethna_Plugin_Twig_Form
 *
 * @author Keisuke SATO <riaf@me.com>
 * @package    Ethna
 */
class Ethna_Plugin_Twig_Form extends Twig_Extension
{
    public function getFunctions()
    {
        return array(
            'form_input' => new Twig_Function_Function('ethna_twig_form_input'),
            'form_name' => new Twig_Function_Function('ethna_twig_form_name'),
            'form_submit' => new Twig_Function_Function('ethna_twig_form_submit'),
            'csrfid' => new Twig_Function_Function('ethna_twig_csrfid'),
            'checkbox_list' => new Twig_Function_Function('ethna_twig_checkbox_list'),
            'message' => new Twig_Function_Function('ethna_twig_message'),
            'select' => new Twig_Function_Function('ethna_twig_select'),
            'uniqid' => new Twig_Function_Function('ethna_twig_uniqid'),
        );
    }

    public function getName()
    {
        return 'form';
    }
}

function ethna_twig_form_input($name, array $params=array())
{
    // view object
    $c = Ethna_Controller::getInstance();
    $view = $c->getView();
    if ($view === null) {
        return null;
    }

    // action
    $action = null;
    if (isset($params['action'])) {
        $action = $params['action'];
        unset($params['action']);
    }
    if ($action !== null) {
        $view->addActionFormHelper($action, true);
    }

    // 現在のアクションで受け取ったフォーム値を補正する
    $af = $c->getActionForm();
    $val = $af->get($name);
    $cur_form_id = $af->get('ethna_fid');
    $can_fill = $cur_form_id == null;
    if ($can_fill && $val !== null) {
        $params['default'] = $val;
    }

    return $view->getFormInput($name, $action, $params);
}

function ethna_twig_form_name($name, array $params=array())
{
    // view object
    $c = Ethna_Controller::getInstance();
    $view = $c->getView();
    if ($view === null) {
        return null;
    }

    // action
    $action = null;
    if (isset($params['action'])) {
        $action = $params['action'];
        unset($params['action']);
    }
    if ($action !== null) {
        $view->addActionFormHelper($action);
    }

    return $view->getFormName($name, $action, $params);
}

function ethna_twig_form_submit(array $params=array())
{
    $c = Ethna_Controller::getInstance();
    $view = $c->getView();
    if ($view === null) {
        return null;
    }

    return $view->getFormSubmit($params);
}

function ethna_twig_csrfid(array $params=array())
{
    $c = Ethna_Controller::getInstance();
    $name = $c->config->get('csrf');
    if (is_null($name)) {
        $name = 'Session';
    }
    $plugin = $c->getPlugin();
    $csrf = $plugin->getPlugin('Csrf', $name);
    $csrfid = $csrf->get();
    $token_name = $csrf->getName();
    if (isset($params['type']) && $params['type'] == 'get') {
        return sprintf("%s=%s", $token_name, $csrfid);
    } else {
        return sprintf("<input type=\"hidden\" name=\"%s\" value=\"%s\" />\n", $token_name, $csrfid);
    }
}

function ethna_twig_checkbox_list($params=array())
{
    extract($params);

    if (isset($key) == false) {
        $key = null;
    }
    if (isset($value) == false) {
        $value = null;
    }
    if (isset($checked) == false) {
        $checked = "checked";
    }

    if (is_null($key) == false) {
        if (isset($form[$key])) {
            if (is_null($value)) {
                print $checked;
            } else {
                if (strcmp($form[$key], $value) == 0) {
                    print $checked;
                }
            }
        }
    } else if (is_null($value) == false) {
        if (is_array($form)) {
            if (in_array($value, $form)) {
                print $checked;
            }
        } else {
            if (strcmp($value, $form) == 0) {
                print $checked;
            }
        }
    }
}

function ethna_twig_message($name, array $params=array())
{
    $c = Ethna_Controller::getInstance();
    $action_error = $c->getActionError();

    $message = $action_error->getMessage($params['name']);
    if ($message === null) {
        return '';
    }

    $id = isset($params['id']) ? $params['id']
        : str_replace("_", "-", "ethna-error-" . $name);
    $class = isset($params['class']) ? $params['class'] : "ethna-error";

    return sprintf('<span class="%s" id="%s">%s</span>',
        $class, $id, htmlspecialchars($message));
}

function ethna_twig_select(array $params=array())
{
    extract($params);

    //  empty="...." を加えると、無条件に追加される
    //  ない場合は追加されない
    print "<select name=\"$name\">\n";
    if ($empty) {
        printf("<option value=\"\">%s</option>\n", $empty);
    }
    foreach ($list as $id => $elt) {
        //    標準に合わせる
        //    @see http://www.w3.org/TR/html401/interact/forms.html#adef-selected
        //    @see http://www.w3.org/TR/2000/REC-xhtml1-20000126/DTD/xhtml1-transitional.dtd
        //    @see http://www.w3.org/TR/2000/REC-xhtml1-20000126/DTD/xhtml1-strict.dtd
        //    @see http://www.w3.org/TR/2000/REC-xhtml1-20000126/DTD/xhtml1-frameset.dtd
        //    @see http://www.w3.org/TR/xhtml-modularization/abstract_modules.html#s_sformsmodule
        printf("<option value=\"%s\" %s>%s</option>\n",
               $id, $id == $value ? 'selected="selected"' : '', $elt['name']);
    }
    print "</select>\n";
}

function ethna_twig_uniqid(array $params=array())
{
    $uniqid = Ethna_Util::getRandom();
    if (isset($params['type']) && $params['type'] == 'get') {
        return "uniqid=$uniqid";
    } else {
        return "<input type=\"hidden\" name=\"uniqid\" value=\"$uniqid\" />\n";
    }
}

