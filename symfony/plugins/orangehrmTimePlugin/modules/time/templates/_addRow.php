<?php
/**
 * OrangeHRM Enterprise is a closed sourced comprehensive Human Resource Management (HRM)
 * System that captures all the essential functionalities required for any enterprise.
 * Copyright (C) 2006 OrangeHRM Inc., http://www.orangehrm.com
 *
 * OrangeHRM Inc is the owner of the patent, copyright, trade secrets, trademarks and any
 * other intellectual property rights which subsist in the Licensed Materials. OrangeHRM Inc
 * is the owner of the media / downloaded OrangeHRM Enterprise software files on which the
 * Licensed Materials are received. Title to the Licensed Materials and media shall remain
 * vested in OrangeHRM Inc. For the avoidance of doubt title and all intellectual property
 * rights to any design, new software, new protocol, new interface, enhancement, update,
 * derivative works, revised screen text or any other items that OrangeHRM Inc creates for
 * Customer shall remain vested in OrangeHRM Inc. Any rights not expressly granted herein are
 * reserved to OrangeHRM Inc.
 *
 * You should have received a copy of the OrangeHRM Enterprise  proprietary license file along
 * with this program; if not, write to the OrangeHRM Inc. 538 Teal Plaza, Secaucus , NJ 0709
 * to get the file.
 *
 */

?>
<?php echo javascript_include_tag('../orangehrmTimePlugin/js/editTimesheet'); ?>
<table  class = "data-table" cellpadding ="0" border="0" cellspacing="0">
	<tr>
	    <td><?php echo $form['initialRows'][$num]['toDelete'] ?></td>
                <?php echo $form['initialRows'][$num]['projectId'] ?><td>&nbsp;<?php echo $form['initialRows'][$num]['projectName']->renderError() ?><?php echo $form['initialRows'][$num]['projectName'] ?></td>
		<?php echo $form['initialRows'][$num]['projectActivityId'] ?><td>&nbsp;<?php echo $form['initialRows'][$num]['projectActivityName']->renderError() ?><?php echo $form['initialRows'][$num]['projectActivityName'] ?></td>
		<?php for ($j = 0; $j < $noOfDays; $j++) { ?>
			<?php echo $form['initialRows'][$num]['TimesheetItemId'.$j] ?><td style="text-align:center"><?php echo $form['initialRows'][$num][$j]->renderError() ?><div style="float: left; padding-left: 20px"><?php echo $form['initialRows'][$num][$j] ?></div><div id="img" style="float: left; padding-left: 2px"><?php echo image_tag('callout.png', 'id=commentBtn_'.$j.'_' . $num . " class=commentIcon") ?></div></td>
		<?php } ?>
	</tr>
</table>


