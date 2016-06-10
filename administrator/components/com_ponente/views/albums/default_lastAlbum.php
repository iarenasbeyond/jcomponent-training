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

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/');
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');

// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet(JUri::root() . 'administrator/components/com_ponente/assets/css/ponente.css');
$document->addStyleSheet(JUri::root() . 'media/com_ponente/css/list.css');

$grupo = $this->item;

?>
<script type="text/javascript">
	Joomla.orderTable = function () {
		table = document.getElementById("sortTable");
		direction = document.getElementById("directionTable");
		order = table.options[table.selectedIndex].value;
		if (order != '<?php echo $listOrder; ?>') {
			dirn = 'asc';
		} else {
			dirn = direction.options[direction.selectedIndex].value;
		}
		Joomla.tableOrdering(order, dirn, '');
	};

	jQuery(document).ready(function () {
		jQuery('#clear-search-button').on('click', function () {
			jQuery('#filter_search').val('');
			jQuery('#adminForm').submit();
		});
	});

	window.toggleField = function (id, task, field) {

		var f = document.adminForm,
			i = 0, cbx,
			cb = f[ id ];

		if (!cb) return false;

		while (true) {
			cbx = f[ 'cb' + i ];

			if (!cbx) break;

			cbx.checked = false;
			i++;
		}

		var inputField   = document.createElement('input');
		inputField.type  = 'hidden';
		inputField.name  = 'field';
		inputField.value = field;
		f.appendChild(inputField);

		cb.checked = true;
		f.boxchecked.value = 1;
		window.submitform(task);

		return false;
	};

</script>

<?php

// Joomla Component Creator code to allow adding non select list filters
if (!empty($this->extra_sidebar))
{
	$this->sidebar .= $this->extra_sidebar;
}
var_dump($this->item);
?>




<form action="<?php echo JRoute::_('index.php?option=com_ponente&view=albums'); ?>" method="post"
	  name="adminForm" id="adminForm">
	<?php if (!empty($this->sidebar)): ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
		<?php else : ?>
		<div id="j-main-container">
			<?php endif; ?>

			<div id="filter-bar" class="btn-toolbar">
				<div class="btn-group pull-left">
					<button class="btn hasTooltip" type="submit"
							title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>">
						<i class="icon-search"></i></button>
					<button class="btn hasTooltip" id="clear-search-button" type="button"
							title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>">
						<i class="icon-remove"></i></button>
				</div>
				<div class="btn-group pull-right hidden-phone">
					<label for="limit"
						   class="element-invisible">
						<?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC'); ?>
					</label>
				</div>
				<div class="btn-group pull-right hidden-phone">
					<label for="directionTable"
						   class="element-invisible">
						<?php echo JText::_('JFIELD_ORDERING_DESC'); ?>
					</label>
					<select name="directionTable" id="directionTable" class="input-medium"
							onchange="Joomla.orderTable()">
						<option value=""><?php echo JText::_('JFIELD_ORDERING_DESC'); ?></option>
						<option value="asc" <?php echo $listDirn == 'asc' ? 'selected="selected"' : ''; ?>>
							<?php echo JText::_('JGLOBAL_ORDER_ASCENDING'); ?>
						</option>
						<option value="desc" <?php echo $listDirn == 'desc' ? 'selected="selected"' : ''; ?>>
							<?php echo JText::_('JGLOBAL_ORDER_DESCENDING'); ?>
						</option>
					</select>
				</div>
				<div class="btn-group pull-right">
					<label for="sortTable" class="element-invisible"><?php echo JText::_('JGLOBAL_SORT_BY'); ?></label>
					<select name="sortTable" id="sortTable" class="input-medium" onchange="Joomla.orderTable()">
						<option value=""><?php echo JText::_('JGLOBAL_SORT_BY'); ?></option>
						<?php echo JHtml::_('select.options', $sortFields, 'value', 'text', $listOrder); ?>
					</select>
				</div>
			</div>
			<div class="clearfix"></div>
			<table class="table table-striped" id="albumList">
				<thead>
				<tr>
					<th class='left'>
				<?php echo JHtml::_('grid.sort',  'COM_PONENTE_ALBUMS_ID', 'a.`id`', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo JHtml::_('grid.sort',  'COM_PONENTE_ALBUMS_NOMBRE', 'a.`nombre`', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo JHtml::_('grid.sort',  'COM_PONENTE_ALBUMS_FECHA', 'a.`fecha`', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo JHtml::_('grid.sort',  'COM_PONENTE_ALBUMS_DESCRIPCION', 'a.`descripcion`', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo JHtml::_('grid.sort',  'COM_PONENTE_ALBUMS_IMAGEN', 'a.`imagen`', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo JText::_('COM_PONENTE_ULTIMO_ALBUM'); ?>	
				</th>		
				</tr>
				</thead>
				<tfoot>
				</tfoot>
				<tbody>
				<?php foreach ($this->item as $album) :?>
					<tr class="row<?php echo $i % 2; ?>">
						<td>

					<?php echo $album->id; ?>
				</td>
						<td>
							<a href="<?php echo JRoute::_('index.php?option=com_ponente&task=album.edit&id='.(int) $album->id); ?>"><?php echo $album->nombre; ?></a>

				</td>				<td>

					<?php echo $album->fecha; ?>
				</td>				<td>

					<?php echo $album->descripcion; ?>
				</td>				<td>

					<?php echo $album->imagen; ?>
				</td>
				<td>
					<?php echo $album->fecha; ?>
				</td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>

			<input type="hidden" name="task" value=""/>
			<?php echo JHtml::_('form.token'); ?>
		</div>
</form>
