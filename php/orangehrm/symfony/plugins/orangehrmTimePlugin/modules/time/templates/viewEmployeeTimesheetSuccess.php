<?php echo stylesheet_tag('../orangehrmTimePlugin/css/viewEmployeeTimesheetSuccess'); ?>
<?php echo javascript_include_tag('viewEmployeeTimesheet'); ?>

<?php
use_stylesheet('../../../themes/orange/css/jquery/jquery.autocomplete.css');
use_stylesheet('../../../themes/orange/css/ui-lightness/jquery-ui-1.7.2.custom.css');

use_javascript('../../../scripts/jquery/ui/ui.core.js');
use_javascript('../../../scripts/jquery/ui/ui.dialog.js');
use_javascript('../../../scripts/jquery/jquery.autocomplete.js');
?>
<div id="validationMsg" style="width: 465px; margin-left: 16px;"></div>
<div class="outerbox"  style="width: 500px">
	<div class="maincontent">
		<div class="mainHeading">
			<h2><?php echo __("Select Employee"); ?></h2>
		</div>
		<br class="clear">
		<form action="<?php echo url_for("time/viewEmployeeTimesheet"); ?>" id="reportForm" method="post">

			<table  border="0" cellpadding="5" cellspacing="0" class="employeeTable">
				<tr>
					<td><?php echo __('Employee Name') ?></td>
					<td><?php echo $form['employeeName']->renderError() ?><?php echo $form['employeeName']->render(); ?></td>
					<td><input type="submit" class="viewbutton1" value="<?php echo __('View') ?>"  onmouseover="moverButton(this);" onmouseout="moutButton(this);"/></td>
					<?php echo $form->renderHiddenFields(); ?>
				</tr>
			</table>
		</form>

	</div>
</div>

<?php if (!($pendingApprovelTimesheets == null)): ?>
						<div class="outerbox" style="width:500px">
							<div class="maincontent">
								<div class="mainHeading">
									<h2><?php echo __("Timesheets Pending Action"); ?></h2>
								</div>
								<form action="<?php echo url_for("time/viewPendingApprovelTimesheet"); ?>" id="viewTimesheetForm" method="post" >

									<table  border="0" cellpadding="5" cellspacing="0" class="employeeTable">
										<thead>
											<tr>
						                                                <td id="tablehead"><?php echo __('Employee name'); ?></td>
						                                                <td id="tablehead"><?php echo __('Timesheet Period'); ?></td>
						                                                <td></td>
										</thead>
				<?php foreach ($sf_data->getRaw('pendingApprovelTimesheets') as $pendingApprovelTimesheet): ?>
							<tr>
								<td><?php echo $pendingApprovelTimesheet['employeeFirstName'] . " " . $pendingApprovelTimesheet['employeeLastName']; ?></td>
								<td><?php echo$pendingApprovelTimesheet['timesheetStartday'] . " to " . $pendingApprovelTimesheet['timesheetEndDate'] ?></td>
								<td><input type="hidden" name="timesheetId" value="<?php echo $pendingApprovelTimesheet['timesheetId']; ?>" /></td>
								<td><input type="hidden" name="employeeId" value="<?php echo $pendingApprovelTimesheet['employeeId']; ?>" /></td>
								<td><input type="hidden" name="startDate" value="<?php echo $pendingApprovelTimesheet['timesheetStartday']; ?>" /></td>
								<td class="<?php echo $pendingApprovelTimesheet['timesheetId'] . "##" . $pendingApprovelTimesheet['employeeId'] . "##" . $pendingApprovelTimesheet['timesheetStartday'] ?>"><input type="button" class="viewbutton" value="<?php echo __('View') ?>"  onmouseover="moverButton(this);" onmouseout="moutButton(this);"/></td>
							</tr>
				<?php endforeach; ?>
			                        </table>

					</form>
				</div>
			</div>
<?php endif; ?>


							<script type="text/javascript">

								var employees = <?php echo str_replace('&quot;', "'", $employeeListAsJson) ?> ;
	var employeesArray = eval(employees);
	var errorMsge;
       
	$(document).ready(function() {

		$("#employee").autocomplete(employees, {

			formatItem: function(item) {

				return item.name;
			}
			,matchContains:true
		}).result(function(event, item) {
		}
	);

		$('#employeeSelectForm').submit(function(){
            
			$('#validationMsg').removeAttr('class');
			$('#validationMsg').html("");
			var projectFlag = validateInput();
			if(!projectFlag) {
				$('#btnSave').attr('disabled', 'disabled');
				$('#validationMsg').attr('class', "messageBalloon_failure");
				$('#validationMsg').html(errorMsge);
				return false;
			}
            
       
		});

         $('.viewbutton').click(function() {

         var data = $(this).parent().attr("class").split("##");
         // var ids = ($(this).attr("id")).split("_");
         var url = 'viewPendingApprovelTimesheet?timesheetId='+data[0]+'&employeeId='+data[1]+'&timesheetStartday='+data[2];
         $(location).attr('href',url);
     });


	});

	function validateInput(){
	    
		var errorStyle = "background-color:#FFDFDF;";
		var empDateCount = employeesArray.length;
		var temp = false;
		var i;
        
         if(empDateCount==0){
            
            errorMsge = "No Employees Available in System";
            return false;
        }
		for (i=0; i < empDateCount; i++) {
			empName = $.trim($('#employee').val()).toLowerCase();
			arrayName = employeesArray[i].name.toLowerCase();

			if (empName == arrayName) {
				$('#time_employeeId').val(employeesArray[i].id);
				temp = true
				break;
			}
		}
		if(temp){
			return true;
		}else if(empName == "" || empName == $.trim("Type for hints...").toLowerCase()){
			errorMsge = "Please Select an Employee";
			return false;
		}else{
			errorMsge = "Invalid Employee Name";
			return false;
		}
	}


    

//	function getEmployeeData(e){
//		var data = $(e.target).parent().attr("class").split("##");
//		var url = 'viewPendingApprovelTimesheet?timesheetId='+data[0]+'&employeeId='+data[1]+'&timesheetStartday='+data[2];
//		$(location).attr('href',url);
//	}
</script>
