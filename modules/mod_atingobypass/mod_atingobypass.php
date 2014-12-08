<?php
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.module.helper');

$module = JModuleHelper::getModule( 'mod_atingobypass' );
$params = new JRegistry();
$params->loadString($module->params);

require( JModuleHelper::getLayoutPath('mod_atingobypass') );