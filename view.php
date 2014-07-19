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


require_once ROOT_PATH . '/lib/confs/sysConf.php';

        /** Clean Get variables that are used in page */
        $varsToClean = array('isAdmin');

        foreach ($varsToClean as $var) {
            if (isset($_GET[$var])) {
                $_GET[$var] = CommonFunctions::cleanAlphaNumericIdField($_GET[$var]);
            }
        }
        
	$sysConst = new sysConf();
	$locRights = $_SESSION['localRights'];

	$currentPage = $this->popArr['currentPage'];

	$message = $this->popArr['message'];

	if (!isset($this->getArr['sortField']) || ($this->getArr['sortField'] == '')) {
		$this->getArr['sortField'] = 0;
		$this->getArr['sortOrder0'] = 'ASC';
	}

	$readOnlyView = (isset($this->popArr['readOnlyView'])) && ($this->popArr['readOnlyView'] === true);
    $esp = isset($_GET['isAdmin'])? ('&isAdmin='.$_GET['isAdmin']) : '';

	function getNextSortOrder($curSortOrder) {
		switch ($curSortOrder) {
			case 'null' :
				return 'ASC';
				break;
			case 'ASC' :
				return 'DESC';
				break;
			case 'DESC'	:
				return 'ASC';
				break;
		}
	}

    $GLOBALS['lang_Common_SortAscending'] = $lang_Common_SortAscending;
    $GLOBALS['lang_Common_SortDescending'] = $lang_Common_SortDescending;

	function nextSortOrderInWords($sortOrder) {
        return $sortOrder == 'ASC' ? $GLOBALS['lang_Common_SortDescending'] : $GLOBALS['lang_Common_SortAscending'];
	}
$token = $this->popArr['token'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link href="../../themes/<?php echo $styleSheet; ?>/css/style.css" rel="stylesheet" type="text/css"/>
<!--[if lte IE 6]>
<link href="../../themes/<?php echo $styleSheet; ?>/css/IE6_style.css" rel="stylesheet" type="text/css"/>
<![endif]-->
<!--[if IE]>
<link href="../../themes/<?php echo $styleSheet; ?>/css/IE_style.css" rel="stylesheet" type="text/css"/>
<![endif]-->
<script type="text/javascript" src="../../themes/<?php echo $styleSheet;?>/scripts/style.js"></script>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<script type="text/javascript" src="../../scripts/octopus.js"></script>

<?php $customFieldsView = ($this->getArr['uniqcode'] == 'CTM'); ?>

<script type="text/javascript">
//<![CDATA[
	function nextPage() {
		var i=eval(document.standardView.pageNO.value);
		document.standardView.pageNO.value=i+1;
		document.standardView.submit();
	}

	function prevPage() {
		var i=eval(document.standardView.pageNO.value);
		document.standardView.pageNO.value=i-1;
		document.standardView.submit();
	}

	function chgPage(pNO) {
		document.standardView.pageNO.value=pNO;
		document.standardView.submit();
	}

	function sortAndSearch(sortField, sortOrder) {
        var uri = "<?php echo $_SERVER['PHP_SELF']?>?uniqcode=<?php echo $this->getArr['uniqcode']?>&VIEW=MAIN&sortField=" + sortField + "&sortOrder" + sortField + "=" + sortOrder + "<?php echo $esp;?>";
        document.standardView.action = uri;
        document.standardView.submit();
	}

	function returnAdd() {
	<?php

		switch($headingInfo[2]) {
			case 1 :
				echo "location.href = './CentralController.php?uniqcode=".$this->getArr['uniqcode']."&capturemode=addmode".$esp."'";
				break;
			case 2 :
				echo "var popup=window.open('../../genpop.php?uniqcode=".$this->getArr['uniqcode']."','Employees','modal=yes,height=450,width=600');";
				echo "if(!popup.opener) popup.opener=self;";
				 break;
			}
	?>
	}

	function returnDelete() {
		$check = 0;
		with (document.standardView) {
			for (var i=0; i < elements.length; i++) {
				if ((elements[i].type == 'checkbox') && (elements[i].checked == true) && (elements[i].name == 'chkLocID[]')){
					$check = 1;
				}
			}
		}

		if ($check == 1){

			var res = confirm("<?php echo "{$headingInfo[4]}. {$lang_Common_ConfirmDelete}"; ?>");

			if(!res) return;

			document.standardView.delState.value = 'DeleteMode';
			document.standardView.pageNO.value=1;
			document.standardView.submit();
		}else{
			alert("<?php echo $lang_Common_SelectDelete; ?>");
		}
	}

	function returnSearch() {

		if (document.standardView.loc_code.value == -1) {
			alert("<?php echo $lang_Common_SelectField; ?>");
			document.standardView.loc_code.Focus();
			return;
		};
		document.standardView.captureState.value = 'SearchMode';
		document.standardView.pageNO.value=1;
		document.standardView.submit();

	}

	function doHandleAll() {
		with (document.standardView) {
			if(elements['allCheck'].checked == false){
				doUnCheckAll();
			}
			else if(elements['allCheck'].checked == true){
				doCheckAll();
			}
		}
	}

	function doCheckAll() {
		with (document.standardView) {
			for (var i=0; i < elements.length; i++) {
				if (elements[i].type == 'checkbox') {
					elements[i].checked = true;
				}
			}
		}
	}

	function doUnCheckAll() {
		with (document.standardView) {
			for (var i=0; i < elements.length; i++) {
				if (elements[i].type == 'checkbox') {
					elements[i].checked = false;
				}
			}
		}
	}

	function clear_form() {
		document.standardView.loc_code.options[0].selected=true;
		document.standardView.loc_name.value='';
	}
//]]>
</script>
<?php if ($customFieldsView) { ?>
<link href="../../themes/<?php echo $styleSheet; ?>/css/message.css" rel="stylesheet" type="text/css"/>
<link href="../../themes/<?php echo $styleSheet; ?>/css/ui-lightness/jquery-ui-1.7.2.custom.css" rel="stylesheet" type="text/css"/>

<style type="text/css">
div.dialogButtons {
    margin-top: 20px;
    text-align: center;
    vertical-align: bottom;
}
</style>    
<script type="text/javascript" src="../../scripts/jquery/jquery.js"></script>
<script type="text/javascript" src="../../scripts/jquery/jquery.validate.js"></script>
<script type="text/javascript" src="../../scripts/jquery/jquery.form.js"></script>

<script type="text/javascript" src="../../scripts/jquery/jquery.tablesorter.js"></script>
<script type="text/javascript" src="../../scripts/jquery/ui/ui.core.js"></script>
<script type="text/javascript" src="../../scripts/jquery/ui/ui.dialog.js"></script>

<script type="text/javascript" src="../../symfony/web/js/orangehrm.validate.js"></script>

<?php }?>
</head>
<body>
<?php if ($customFieldsView) { 
        $cssClass = '';
        $messageText = '';
        
        if (isset($this->getArr['message'])) {
            $expString  = $this->getArr['message'];
            $messageType = CommonFunctions::getCssClassForMessage($expString, 'failure');
            $cssClass = 'messageBalloon_' . $messageType;
            $messageText = $$expString;
        }
        ?>
        <div id="messagebar" class="<?php echo $cssClass;?>">
            <?php echo $messageText; ?>
        </div>    
<?php   
  } ?>
    
<div class="outerbox">
<form name="standardView" id="standardViewForm" method="post" action="<?php echo $_SERVER['PHP_SELF']?>?uniqcode=<?php echo $this->getArr['uniqcode']?>&amp;VIEW=MAIN&amp;sortField=<?php echo $this->getArr['sortField']?>&amp;sortOrder<?php echo $this->getArr['sortField']?>=<?php echo $this->getArr['sortOrder'.$this->getArr['sortField']].$esp?>">
	<div class="mainHeading"><h2><?php echo $headingInfo[3]; ?></h2></div>
   <input type="hidden" value="<?php echo $token;?>" name="token" />
    <input type="hidden" name="captureState" value="<?php echo isset($this->postArr['captureState'])?$this->postArr['captureState']:''?>" />
    <input type="hidden" name="delState" value="" />
    <input type="hidden" name="pageNO" value="<?php echo isset($this->postArr['pageNO'])?$this->postArr['pageNO']:'1'?>" />

    <?php if (!$customFieldsView) { 
        if (isset($this->getArr['message'])) {
            $expString  = $this->getArr['message'];
            $messageType = CommonFunctions::getCssClassForMessage($expString, 'failure');
        ?>
    
        <?php
            if ($this->popArr['projectsHaveTimeItems']) {
                $messageType = 'failure';
                $expString = 'lang_projectsHaveTimeItems';
            }
        ?>
    
     <?php
            if ($this->popArr['customersHaveTimeItems']) {
                $messageType = 'failure';
                $expString = 'lang_customersHaveTimeItems';
            }
        ?>
    
        <?php
            if ($this->popArr['activitiesHaveTimeItems']) {
                $messageType = 'failure';
                $expString = 'lang_activitiesHaveTimeItems';
            }
        ?>
    
        <div class="messagebar">
            <span class="<?php echo $messageType; ?>"><?php echo $$expString; ?></span>
        </div>
        <?php
        }
    }
    ?>

    <?php

    if ($this->getArr['uniqcode'] != 'CTM') {
    ?>
    <div class="searchbox">
        <label for="loc_code"><?php echo $searchby?></label>
        <select name="loc_code" id="loc_code">
            <?php
            $optionCount = count($srchlist);
            for ($c = -1; $optionCount - 1 > $c; $c++) {
                $selected = "";
                if(isset($this->postArr['loc_code']) && $this->postArr['loc_code'] == $c) {
                    $selected = 'selected="selected"';
                }
                echo "<option $selected value='" . $c ."'>".$srchlist[$c+1] ."</option>";
            }
            ?>
        </select>

        <label for="loc_name"><?php echo $description?></label>
        <input type="text" size="20" name="loc_name" id="loc_name" value="<?php echo isset($this->postArr['loc_name'])? stripslashes($this->postArr['loc_name']):''?>" />
        <input type="button" class="plainbtn" onclick="returnSearch();"
            onmouseover="this.className='plainbtn plainbtnhov'" onmouseout="this.className='plainbtn'"
            value="<?php echo $lang_Common_Search;?>" />
        <input type="button" class="plainbtn" onclick="clear_form();"
            onmouseover="this.className='plainbtn plainbtnhov'" onmouseout="this.className='plainbtn'"
             value="<?php echo $lang_Common_Reset;?>" />
        <br class="clear"/>
    </div>
    <?php
     }
    ?>
<?php $deleteFunction = $customFieldsView ? '' : 'onclick="returnDelete();"'; ?>
    
    <div class="actionbar">
        <div class="actionbuttons">
        <?php if (!$readOnlyView) { ?>
            <input type="button" class="plainbtn"
            <?php echo ($locRights['add']) ? 'onclick="returnAdd();"' : 'disabled'; ?>
                onmouseover="this.className='plainbtn plainbtnhov'" onmouseout="this.className='plainbtn'"
                value="<?php echo $lang_Common_Add;?>" />

            <?php if($headingInfo[2]==1) { ?>
                <input type="button" class="plainbtn" id="delButton"
                <?php echo ($locRights['delete']) ? $deleteFunction : 'disabled'; ?>
                    onmouseover="this.className='plainbtn plainbtnhov'" onmouseout="this.className='plainbtn'"
                    value="<?php echo $lang_Common_Delete;?>" />
        <?php     }
            }
        ?>
        </div>
        <div class="noresultsbar"><?php echo (empty($message)) ? $norecorddisplay : '';?></div>
        <div class="pagingbar">
        <?php
            $temp = $this->popArr['temp'];
            $commonFunc = new CommonFunctions();
            $pageStr = $commonFunc->printPageLinks($temp, $currentPage);
            $pageStr = preg_replace(array('/#first/', '/#previous/', '/#next/', '/#last/'), array($lang_empview_first, $lang_empview_previous, $lang_empview_next, $lang_empview_last), $pageStr);

            echo $pageStr;

            for ($j = 0; $j < 11; $j++) {
                if (!isset($this->getArr['sortOrder'.$j])) {
                    $this->getArr['sortOrder'.$j] = 'null';
                }
            }
        ?>
        </div>
    <br class="clear" />
    </div>

    <br class="clear" />
    	<table cellpadding="0" cellspacing="0" class="data-table">
			<thead>
            <tr>
				<td width="50">
				<?php	if (($headingInfo[2]==1) && (!$readOnlyView)) { ?>
					<input type="checkbox" class="checkbox" name="allCheck" value="" onclick="doHandleAll();" />
				<?php	}	?>
				</td>
				<?php
					for ($j=0; $j < count($headings); $j++) {
						if (!isset($this->getArr['sortOrder'.$j])) {
							$this->getArr['sortOrder'.$j] = 'null';
						}
                        $sortOrder = $this->getArr['sortOrder'.$j];
				?>
					<td scope="col">
						<a href="#" onclick="sortAndSearch(<?php echo $j; ?>, '<?php echo getNextSortOrder($sortOrder);?>');"
                            title="<?php echo nextSortOrderInWords($sortOrder);?>"
                            class="<?php echo $sortOrder;?>"><?php echo $headings[$j]?>
                        </a>
					</td>
				<?php } ?>
            </tr>
    		</thead>

            <tbody>
    		<?php
				if ((isset($message)) && ($message !='')) {
					for ($j = 0; $j < count($message); $j++) {

                        $cssClass = ($j%2) ? 'even' : 'odd';
                        $detailsUrl = "./CentralController.php?id=" . $message[$j][0] . "&amp;uniqcode=" . $this->getArr['uniqcode'] . "&amp;capturemode=updatemode" . $esp;
	 		?>
				<tr>
       				<td class="<?php echo $cssClass?>">
					<?php if($headingInfo[2] == 1) { ?>
						<?php if ((!$readOnlyView) && (CommonFunctions::extractNumericId($message[$j][0]) > 0)) { ?>
							<input type='checkbox' class='checkbox' name='chkLocID[]' value='<?php echo $message[$j][0]?>' />
						<?php }
                                            else
                                            {
                                        $terminatedStatusFlag=1;
                                        }?>
					<?php 	} else { ?>
						&nbsp;
					<?php 	}  ?>
					</td>
		 			<td class="<?php echo $cssClass?>">
		 				<?php if(isset($terminatedStatusFlag) && $terminatedStatusFlag == 1){
                                                        echo $message[$j][0];
                                                    }else{?>
                                                        <a href="<?php echo $detailsUrl;?>"><?php echo $message[$j][0]?></a>
                                                <?php }?>
		 			</td>
					<?php
		 				for ($k = 1; $k < count($headings); $k++) {

							$descField = $message[$j][$k];

		  	 				if($sysConst->viewDescLen <= strlen($descField)) {
			 	   				$descField = substr($descField,0,$sysConst->viewDescLen);
			 	   				$descField .= "....";
			 				}
		 			?>
		 			<td class="<?php echo $cssClass?>">
                        <?php if ($k == 1) {
                                  if(isset($terminatedStatusFlag) && $terminatedStatusFlag == 1)
                                    {
                                       echo $descField;
                                        $terminatedStatusFlag=0;
                                    }
                                    else
                                    {
                                        echo "<a href='{$detailsUrl}'>{$descField}</a>";
                                    }
                              } else {
                                  echo $descField;
                              } ?>
                    </td>
				<?php } ?>
		 	</tr>
		 	<?php
				}
		 	}
			?>
            </tbody>
 		</table>
</form>
</div>
<script type="text/javascript">
    <!--
        if (document.getElementById && document.createElement) {
            roundBorder('outerbox');
        }
    -->
</script>
<?php if ($customFieldsView) { ?>
<!-- confirmation box -->
<div id="deleteConfirmation" title="OrangeHRM - Confirmation Required" style="display: none;">
    "Are you sure you want to delete selected custom field(s)?
    <div class="dialogButtons">
        <input type="button" id="dialogDeleteBtn" class="savebutton" value="<?php echo $lang_Common_Delete;?>" />
        <input type="button" id="dialogCancelBtn" class="savebutton" value="<?php echo $lang_Common_Cancel;?>" />
    </div>
</div>

    
<script type="text/javascript">
    //<![CDATA[    
    $('#delButton').click(function(event) {
        event.preventDefault();
        
        var checked = $('#standardViewForm input.checkbox:checked').length;
        
        if ( checked == 0) {
            $('#messagebar').text("Please Select At Least One Custom Field To Delete").attr('class', 'messageBalloon_notice');
        } else {
            $('#messagebar').text('').attr('class', '');            
            $('#deleteConfirmation').dialog('open');
            return false;
        }
    });

    $("#deleteConfirmation").dialog({
        autoOpen: false,
        modal: true,
        width: 325,
        height: 50,
        position: 'middle',
        open: function() {
          $('#dialogCancelBtn').focus();
        }
    });

    $('#dialogDeleteBtn').click(function() {
        document.standardView.delState.value = 'DeleteMode';
        document.standardView.pageNO.value=1;
        document.standardView.submit();  
    });
    $('#dialogCancelBtn').click(function() {
        $("#deleteConfirmation").dialog("close");
    });
        
    //]]>    
</script>
    
<?php }  ?>
    
</body>
</html>
