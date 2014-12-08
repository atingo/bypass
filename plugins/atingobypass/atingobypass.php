<?php
defined('_JEXEC') or die('Restricted access');

/**
 *
 */
class PlgSystemAtingoBypass extends JPlugin
{
    public function onAfterRoute()
    {

        $app = JFactory::getApplication();

        $option = $app->input->getWord('option', null);
        $task   = $app->input->getWord('task', null);
        $token  = $app->input->getWord('token', null);

        //http://localhost/~vifito/sch/administrator/
        //?secure_key=secure_value&option=com_login&token=qwerty&task=login&return=aW5kZXgucGhwP29wdGlvbj1jb21fYXRpbmdvZXZlbnQ=

        $plg = JPluginHelper::getPlugin('authentication', 'atingoauth');
        $registry = new JRegistry($plg->params);

        if($app->isAdmin() && ($token !== null) &&
            ($option === null || $option === 'com_login') && ($task === 'login'))
        {
            $user     = JFactory::getUser();
            $session  = JFactory::getSession();

            $forceNew = false;
            $tkn      = $session->getToken($forceNew);
            $tknForm  = $session->getFormToken($forceNew);

            $_REQUEST[$tkn] = '1';
            $_REQUEST[$tknForm] = '1';
            $session->set('session.token', $tkn);

            // Fake values
            $lixo = md5(rand());
            $_REQUEST['username'] = $registry->get('username');
            $_REQUEST['password'] = $_REQUEST['passwd'] = $lixo;

            $_POST['username'] = $registry->get('username');
            $_POST['password'] = $_POST['passwd'] = $lixo;

            $app->input = new JInput();

            //xdebug_var_dump($app->input);
        }



        return true;
    }


}
