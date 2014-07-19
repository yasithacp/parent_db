
<script type="text/javascript" src="<?php echo public_path('../../scripts/jquery/jquery.validate.js');?>"></script>

<div id="content">
	<div id="contentContainer">
		<?php if(count($listJobTitle) == 0){?>
			<div id="messageBalloon_notice" class="messageBalloon_notice">
                            <?php echo __("No Defined Job Titles")?> <a href="<?php echo url_for('admin/viewJobTitleList') ?>"><?php echo __("Define Now")?></a>
			</div>
		<?php }?>
		<?php echo isset($templateMessage) ? templateMessage($templateMessage) : ''; ?>
        <div class="outerbox">
            <div id="formHeading" class="mainHeading"><h2><?php echo __("Search Key Performance Indicators")?></h2></div>
			
			<form action="#" id="frmSearch" name="frmSearch" class="content_inner" method="post">
			<input type="hidden" name="mode" value="search" />
              	<div id="formWrapper">
                       <label for="txtLocationCode"><?php echo __('Job Title')?></label>
                     <select name="txtJobTitle" id="txtJobTitle" class="formSelect" tabindex="1" >
                     	<option value="all"><?php echo __('All')?></option>
	                     <?php foreach($listJobTitle as $jobTitle){?>
                        <option value="<?php echo $jobTitle->getId()?>" <?php if(isset($searchJobTitle) && $jobTitle->getId()== $searchJobTitle->getId()){ echo 'selected';}?>><?php echo htmlspecialchars_decode($jobTitle->getJobTitleName()); if(!$jobTitle->getIsDeleted() == JobTitle::ACTIVE) { echo ' ('.__('Deleted').')'; } ?></option>
	                     <?php }?>
                     </select>
                   <br class="clear"/>
                </div>
				<div id="buttonWrapper" class="formbuttons">
                    <input type="button" class="savebutton" id="searchBtn"
                        value="<?php echo __('Search')?>" tabindex="2" />
                </div>  
              
            </form>
        </div>
 	</div>
   <br class="clear"/>
   
   <div id="errorContainer" class="hide">
	 
   </div>
   
   	<div id="contentContainer">
	   <div  class="outerbox">
	   			<div id="formHeading" class="mainHeading"><h2><?php echo __("Key Performance Indicators for Job Title")?>: <?php if(isset($searchJobTitle) ){ echo htmlspecialchars_decode($searchJobTitle->getJobTitleName());}?></h2></div>
				<div class="navigationHearder">
	                   <?php if ($pager->haveToPaginate()) { ?>

							<div  class="pagingbar" >
								<?php include_partial('global/paging_links', array('pager' => $pager, 'url'=>'@kpi_list'));?>
							</div>
							
						
						<?php } ?> 
	                    <input type="button" class="savebutton" id="addKpiBut"
	                       
	                        value="<?php echo __('Add')?>" tabindex="2" />
	                        <?php if($hasKpi){?>
	                    <input type="button" class="clearbutton"  id="deleteKpiBut"
	                         value="<?php echo __('Delete')?>" tabindex="3" />
	                         
						<input type="button" class="clearbutton"  id="copyKpiBut"
	                         value="<?php echo __('Copy')?>" tabindex="4" />
	                        <?php }?>
	                        
	                      
	        	</div>
				
			<?php if($hasKpi){?>
	   		<form action="<?php echo url_for('performance/deleteDefineKpi') ?>" name="frmList" id="frmList" method="post">

            <?php echo $form['_csrf_token']; ?>
			
	   		 <div id="tableWrapper">
	   		
				 
				
				<table id="sortTable" cellpadding="0" cellspacing="0" class="data-table" align="center">
					<thead>
            		<tr>
            			<td width="50" class="tdcheckbox">
							<input type="checkbox"  name="allCheck" value="" id="allCheck" />
						</td>
            			<td scope="col" > 
						 <?php echo __('Key Performance Indicator')?>
						</td> 
						<td width="150" scope="col">
						 <?php echo __('Job Title')?>
						</td>
						
						<td scope="col" > 
						 <?php echo __('Min Rate')?>
						</td>
						<td scope="col"> 
						 <?php echo __('Max Rate')?>
						</td>
						<td scope="col"> 
						 <?php echo __('Is Default')?>
						</td>
            		</tr>
    			</thead>
            	<tbody>
            	<?php
						$row = 0;
						foreach ( $kpiList as $kpi ) {
							$cssClass = ($row % 2) ? 'even' : 'odd';
							$row = $row + 1;
						?>
            		<tr class="<?php echo $cssClass?>">
		       				<td class="tdcheckbox">
								<input type='checkbox' class='innercheckbox' name='chkKpiID[]' id="chkLoc" value='<?php echo  $kpi->getId()?>' />
							</td>
							<td class="">
				 				<a href="<?php echo url_for('performance/updateKpi?id='.$kpi->getId()) ?>"><?php echo  $kpi->getDesc()?></a>
				 			</td>
				 			
							<td class="">
				 				<?php echo  htmlspecialchars_decode($kpi->getJobTitle()->getJobTitleName())?>
				 			</td>
				 			
				 			<td class="">
				 				<?php echo  ($kpi->getRateMin()!='')?$kpi->getRateMin():'-'?>
				 			</td>
				 			<td class="">
				 				<?php echo  ($kpi->getRateMax() !='')?$kpi->getRateMax():'-'?>
				 			</td>
				 			<td class="">
				 			
				 				<?php echo  ($kpi->getDefault()==1)?__('Yes'):'-'?>
				 			</td>
				 	</tr>
				 	<?php }?>
				
            	 </tbody>
 			</table>
 			</form>
			</div>
			<?php }?>		
	   </div>
	 </div>
 </div>

<style type="text/css">
        form#frmSearch.content_inner div#formWrapper .formSelect{
            width: 200px;
        }
    </style>
<script type="text/javascript">

		$(document).ready(function() {

			

			//search Kpi 
			$('#searchBtn').click(function(){
				$('#frmSearch').submit();
			});
			
			//Add Kpi button
			$('#addKpiBut').click(function(){
				location.href = "<?php echo url_for('performance/saveKpi') ?>";
			});

			//Copy kpi button
			$('#copyKpiBut').click(function(){
				location.href = "<?php echo url_for('performance/copyKpi') ?>";
			});

			//delete KPI 
			$('#deleteKpiBut').click(function(){
				if($('.innercheckbox').is(':checked'))
				{
					$('#frmList').submit();
				}else
				{
					
					showError('messageBalloon_warning','<?php echo __(TopLevelMessages::SELECT_RECORDS); ?>' );
				}
			});

			//Validate search form 
			 $("#frmSearch").validate({
					
				 rules: {
				 	txtJobTitle: { required: true }
			 	 },
			 	 messages: {
			 		txtJobTitle: '<?php echo __(ValidationMessages::REQUIRED); ?>'
			 	 }
			 });

			

			// When Click Main Tick box
				$("#allCheck").click(function() {
					if ($('#allCheck').attr('checked')) {
						$('.innercheckbox').attr('checked', true);
					}else{
						$('.innercheckbox').attr('checked', false);
					}
					
				});

			 $(".innercheckbox").click(function() {
					if(!($(this).attr('checked')))
					{
						$('#allCheck').attr('checked', false);
					}
				});

			 
				
		 });

		function showError(errorType,message)
		{
			var html	=	"<div id='"+errorType+"' class='"+errorType+"' >"+message+"</div>";
			 $("#errorContainer").html(html);
			 $("#errorContainer").show();
		}
</script>


