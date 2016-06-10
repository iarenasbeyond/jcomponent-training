<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Ponente
 * @author     Be Beyond Training <juan+a@notwebdesign.com>
 * @copyright  2016 Be Beyond Training
 * @license    Licencia Pública General GNU versión 2 o posterior. Consulte LICENSE.txt
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Methods supporting a list of Ponente records.
 *
 * @since  1.6
 */
class PonenteModelAlbums extends JModelList
{
/**
	* Constructor.
	*
	* @param   array  $config  An optional associative array of configuration settings.
	*
	* @see        JController
	* @since      1.6
	*/
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'id', 'a.`id`',
				'ordering', 'a.`ordering`',
				'state', 'a.`state`',
				'created_by', 'a.`created_by`',
				'modified_by', 'a.`modified_by`',
				'grupo', 'a.`grupo`',
				'nombre', 'a.`nombre`',
				'fecha', 'a.`fecha`',
				'descripcion', 'a.`descripcion`',
				'imagen', 'a.`imagen`',
			);
		}

		parent::__construct($config);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @param   string  $ordering   Elements order
	 * @param   string  $direction  Order direction
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$app = JFactory::getApplication('administrator');

		// Load the filter state.
		$search = $app->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$published = $app->getUserStateFromRequest($this->context . '.filter.state', 'filter_published', '', 'string');
		$this->setState('filter.state', $published);
		// Filtering grupo
		$this->setState('filter.grupo', $app->getUserStateFromRequest($this->context.'.filter.grupo', 'filter_grupo', '', 'string'));

		// Filtering fecha
		$this->setState('filter.fecha.from', $app->getUserStateFromRequest($this->context.'.filter.fecha.from', 'filter_from_fecha', '', 'string'));
		$this->setState('filter.fecha.to', $app->getUserStateFromRequest($this->context.'.filter.fecha.to', 'filter_to_fecha', '', 'string'));


		// Load the parameters.
		$params = JComponentHelper::getParams('com_ponente');
		$this->setState('params', $params);

		// List state information.
		parent::populateState('a.grupo', 'asc');
	}


	public function getAlbumsByGroup($pk)
	{



		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query
			->select(
				$db->quoteName(array('id', 'fecha', 'nombre', 'descripcion', 'imagen'))
			)
			->from($db->quoteName('#__ponente_album', 'a'))
			->where($db->quoteName('grupo') . ' = ' . (int) $pk);

		$db->setQuery($query);

		return $db->loadObjectList();

	}

	public function getLastAlbum($pk)
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query
			->select(
				$db->quoteName(array('id', 'fecha', 'nombre', 'descripcion', 'imagen'))
			)
			->from($db->quoteName('#__ponente_album', 'a'))
			->where($db->quoteName('grupo') . ' = ' . (int) $pk)
			->order ('a.fecha DESC')
			->setlimit ('1');

		$db->setQuery($query);

		return $db->loadObjectList();
	}

	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param   string  $id  A prefix for the store id.
	 *
	 * @return   string A store id.
	 *
	 * @since    1.6
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id .= ':' . $this->getState('filter.search');
		$id .= ':' . $this->getState('filter.state');

		return parent::getStoreId($id);
	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return   JDatabaseQuery
	 *
	 * @since    1.6
	 */
	protected function getListQuery()
	{
		// Create a new query object.
		$db    = $this->getDbo();
		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select', 'DISTINCT a.*'
			)
		);
		$query->from('`#__ponente_album` AS a');

		// Join over the users for the checked out user
		$query->select("uc.name AS editor");
		$query->join("LEFT", "#__users AS uc ON uc.id=a.checked_out");

		// Join over the user field 'created_by'
		$query->select('`created_by`.name AS `created_by`');
		$query->join('LEFT', '#__users AS `created_by` ON `created_by`.id = a.`created_by`');

		// Join over the user field 'modified_by'
		$query->select('`modified_by`.name AS `modified_by`');
		$query->join('LEFT', '#__users AS `modified_by` ON `modified_by`.id = a.`modified_by`');
		// Join over the foreign key 'grupo'
		$query->select('`#__ponente_grupo_2388112`.`nombre` AS grupos_fk_value_2388112');
		$query->join('LEFT', '#__ponente_grupo AS #__ponente_grupo_2388112 ON #__ponente_grupo_2388112.`id` = a.`grupo`');

		// Filter by published state
		$published = $this->getState('filter.state');

		if (is_numeric($published))
		{
			$query->where('a.state = ' . (int) $published);
		}
		elseif ($published === '')
		{
			$query->where('(a.state IN (0, 1))');
		}

		// Filter by search in title
		$search = $this->getState('filter.search');

		if (!empty($search))
		{
			if (stripos($search, 'id:') === 0)
			{
				$query->where('a.id = ' . (int) substr($search, 3));
			}
			else
			{
				$search = $db->Quote('%' . $db->escape($search, true) . '%');
				$query->where('(#__ponente_grupo_2388112.nombre LIKE ' . $search . '  OR  a.nombre LIKE ' . $search . '  OR  a.fecha LIKE ' . $search . ' )');
			}
		}


		//Filtering grupo
		$filter_grupo = $this->state->get("filter.grupo");
		if ($filter_grupo)
		{
			$query->where("a.`grupo` = '".$db->escape($filter_grupo)."'");
		}

		//Filtering fecha
		$filter_fecha_from = $this->state->get("filter.fecha.from");
		if ($filter_fecha_from) {
			$query->where("a.`fecha` >= '".$db->escape($filter_fecha_from)."'");
		}
		$filter_fecha_to = $this->state->get("filter.fecha.to");
		if ($filter_fecha_to) {
			$query->where("a.`fecha` <= '".$db->escape($filter_fecha_to)."'");
		}
		// Add the list ordering clause.
		$orderCol  = $this->state->get('list.ordering');
		$orderDirn = $this->state->get('list.direction');

		if ($orderCol && $orderDirn)
		{
			$query->order($db->escape($orderCol . ' ' . $orderDirn));
		}

		return $query;
	}

	/**
	 * Get an array of data items
	 *
	 * @return mixed Array of data items on success, false on failure.
	 */
	public function getItems()
	{
		$items = parent::getItems();

		foreach ($items as $oneItem) {

			if (isset($oneItem->grupo))
			{
				$values = explode(',', $oneItem->grupo);

				$textValue = array();
				foreach ($values as $value){
					$db = JFactory::getDbo();
					$query = $db->getQuery(true);
					$query
							->select('`nombre`')
							->from('`#__ponente_grupo`')
							->where($db->quoteName('id') . ' = '. $db->quote($db->escape($value)));
					$db->setQuery($query);
					$results = $db->loadObject();
					if ($results) {
						$textValue[] = $results->nombre;
					}
				}

			$oneItem->grupo = !empty($textValue) ? implode(', ', $textValue) : $oneItem->grupo;

			}
		}
		return $items;
	}
}
