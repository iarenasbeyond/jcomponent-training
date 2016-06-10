<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Ponente
 * @author     Be Beyond Training <juan+a@notwebdesign.com>
 * @copyright  2016 Be Beyond Training
 * @license    Licencia Pública General GNU versión 2 o posterior. Consulte LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_ponente'))
{
	throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
}

// Include dependancies
jimport('joomla.application.component.controller');

JLoader::registerPrefix('Ponente', JPATH_COMPONENT_ADMINISTRATOR);

$controller = JControllerLegacy::getInstance('Ponente');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
