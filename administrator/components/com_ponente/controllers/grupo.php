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

jimport('joomla.application.component.controllerform');

/**
 * Grupo controller class.
 *
 * @since  1.6
 */
class PonenteControllerGrupo extends JControllerForm
{
	/**
	 * Constructor
	 *
	 * @throws Exception
	 */
	public function __construct()
	{
		$this->view_list = 'grupos';
		parent::__construct();
	}

	public function listAlbums()
	{
		$app = JFactory::getApplication();
		$input = $app->input;

		$id = $input->get('id');

		$model = $this->getModel('grupo');
		$grupo = $model->getItem($id);

		$modelAlbums = JModelList::getInstance('albums', 'PonenteModel');

		$grupo->albumList = $modelAlbums->getAlbumsByGroup($id);


		$view = $this->getView('albums', 'html');

		$view->item = $grupo;

		$view->display('bygroup');
	}
}
