<?php
defined('_JEXEC') or die('Restricted access');

/**
 *
 */
class plgAuthenticationAtingoAuth extends JPlugin
{
    /**
     * This method should handle any authentication and report back to the subject
     * This example uses simple authentication - it checks if the password is the reverse
     * of the username (and the user exists in the database).
     *
     * @access    public
     * @param     array     $credentials    Array holding the user credentials ('username' and 'password')
     * @param     array     $options        Array of extra options
     * @param     object    $response       Authentication response object
     * @return    boolean
     * @since 1.5
     */
    function onUserAuthenticate( $credentials, $options, &$response )
    {
        $app = JFactory::getApplication();
        $db = JFactory::getDbo();

        // Username
        $username = $this->params->get('username', null);
        $app->input->set('username', $username);
        $credentials['username'] = $username;

        $query  = $db->getQuery(true)
            ->select('id')
            ->from('#__users')
            ->where('username=' . $db->quote($username));

        $db->setQuery($query);
        $result = $db->loadResult();

        if (!$result) {
            $response->status = STATUS_FAILURE;
            $response->error_message = 'User does not exist';
        }

        // Request token
        $input = JFactory::getApplication()->input;
        $token = $input->getString('token', null);

        // System token
        $tokenParam = $this->params->get('token', null);

        if($result && !is_null($tokenParam) && ($tokenParam == $token))
        {
            $email = JUser::getInstance($result);
            $response->email = $email->email;
            $response->username = $username;
            $response->status = JAuthentication::STATUS_SUCCESS;
            $response->error_message = '';
        }
        else
        {
            $response->status = JAuthentication::STATUS_FAILURE;
            $response->error_message = 'Invalid username and password';
        }

    }


}
