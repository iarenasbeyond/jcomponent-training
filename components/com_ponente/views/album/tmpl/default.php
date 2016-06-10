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

$canEdit = JFactory::getUser()->authorise('core.edit', 'com_ponente.' . $this->item->id);
if (!$canEdit && JFactory::getUser()->authorise('core.edit.own', 'com_ponente' . $this->item->id)) {
	$canEdit = JFactory::getUser()->id == $this->item->created_by;
}
?>
<?php if ($this->item) : ?>

	<div class="item_fields">
		<table class="table">
			<tr>
			<th><?php echo JText::_('COM_PONENTE_FORM_LBL_ALBUM_STATE'); ?></th>
			<td>
			<i class="icon-<?php echo ($this->item->state == 1) ? 'publish' : 'unpublish'; ?>"></i></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_PONENTE_FORM_LBL_ALBUM_CREATED_BY'); ?></th>
			<td><?php echo $this->item->created_by_name; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_PONENTE_FORM_LBL_ALBUM_MODIFIED_BY'); ?></th>
			<td><?php echo $this->item->modified_by_name; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_PONENTE_FORM_LBL_ALBUM_GRUPO'); ?></th>
			<td><?php echo $this->item->grupo; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_PONENTE_FORM_LBL_ALBUM_NOMBRE'); ?></th>
			<td><?php echo $this->item->nombre; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_PONENTE_FORM_LBL_ALBUM_FECHA'); ?></th>
			<td><?php echo $this->item->fecha; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_PONENTE_FORM_LBL_ALBUM_DESCRIPCION'); ?></th>
			<td><?php echo $this->item->descripcion; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_PONENTE_FORM_LBL_ALBUM_IMAGEN'); ?></th>
			<td><?php echo $this->item->imagen; ?></td>
</tr>

		</table>
	</div>
	<?php if($canEdit && $this->item->checked_out == 0): ?>
		<a class="btn" href="<?php echo JRoute::_('index.php?option=com_ponente&task=album.edit&id='.$this->item->id); ?>"><?php echo JText::_("COM_PONENTE_EDIT_ITEM"); ?></a>
	<?php endif; ?>
								<?php if(JFactory::getUser()->authorise('core.delete','com_ponente.album.'.$this->item->id)):?>
									<a class="btn" href="<?php echo JRoute::_('index.php?option=com_ponente&task=album.remove&id=' . $this->item->id, false, 2); ?>"><?php echo JText::_("COM_PONENTE_DELETE_ITEM"); ?></a>
								<?php endif; ?>
	<?php
else:
	echo JText::_('COM_PONENTE_ITEM_NOT_LOADED');
endif;
