<h4><i class="menu-icon fa fa-desktop"></i> AdMIN Dashboard</h4>
<div class="breadcrumb ace-save-state" id="breadcrumbs" style="margin:0">
	<div class="" id="sub_nav">
		<i class="ace-icon fa fa-user home-icon"></i><a href="#"> <b>Dashboard</b></a>
		<span style="float:right;font-family:;"><span id="timing"></span><strong><?php echo @date('M-D-Y'); ?></strong></span>
	</div>
</div>
<?php
include_once('staff_summary.php');

?>
<style>
	.thisrow div,.thisrow div table {
		font-size:1.2em !important;
		position: relative;
	}
	.form-control {
    font-size: 0.8em;
}
</style>
<div class="thisrow row w-100" style="overflow-x:hidden;background-image:url('../images/world.png');overflow-y:auto;">
	<div class="col-md-12 p-3" style="position:absolute;background-image:url('../images/world.png');z-index:5;background:white;">
		<div class="panel panel-default summary_block" style="background-image:url('../images/summary_bg.png');">
			<div class="panel-body">
				<h1 style="text-align:right"><?php echo $total_number_of_staff; ?></h1>
				<h4 style="text-align:right; color:#458;text-shadow: white 0px 0px 4px;"><?php echo 'Total Staffs'; ?></h4>
			</div>
		</div>

		<div class="panel panel-default summary_block" style="background-image:url('../images/summary_bg.png');">
			<div class="panel-body text-success">
				<h1 style="text-align:right;text-shadow: white 0px 0px 4px;"><?php echo $total_students; ?></h1>
				<h4 style="text-align:right; color:#458;text-shadow: white 0px 0px 4px;"><?php echo 'Total Active Students'; ?></h4>
			</div>
		</div>
		<div class="panel panel-default summary_block text-danger" style="background-image:url('../images/summary_bg.png');">
			<div class="panel-body">
				<h1 style="text-align:right"><?php echo $total_inactive; ?></h1>
				<h4 style="text-align:right; color:#458;text-shadow: white 0px 0px 4px;"><?php echo 'Total Inactive Students'; ?></h4>
			</div>
		</div>

		<div class="panel panel-default summary_block" style="background-image:url('../images/summary_bg.png');">
			<div class="panel-body">
				<h1 style="text-align:right"><?php echo $total_number_of_sec; ?></h1>
				<h4 style="text-align:right; color:#458;text-shadow: white 0px 0px 4px;"><?php echo 'Sec. Staffs'; ?></h4>
			</div>
		</div>
		<div class="panel panel-default summary_block" style="background-image:url('../images/summary_bg.png');">
			<div class="panel-body">
				<h1 style="text-align:right"><?php echo $total_number_of_pry; ?></h1>
				<h4 style="text-align:right; color:#458;text-shadow: white 0px 0px 4px;"><?php echo 'Total Pry Staffs'; ?></h4>
			</div>
		</div>
	</div>

	<div class="col-md-12" style="min-height: 130px;"></div>
	<div class="col-md-12">
<?php
$staff_id = $_SESSION['staff_info_id'];
$sql = "SELECT * FROM staff_access where staff_info_id='$staff_id' AND nav_id=5";
$run = $conn->query($sql);
if($run->num_rows >0){

?>
		<table class="table tablexxxx table-hover table-condensed">
			<thead>
				<tr>

					<th>S/N</th>
					<th>Adm no.</th>
					<th>Name</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
				<?php
				foreach ($all_students as $key => $student) {
				?>
					<tr>
						<td><?= $key + 1; ?></td>
						<td><?= $student['adm_no']; ?></td>
						<td><?= $student['first_name'] . ' ' . $student['other_name'] . ' ' . $student['last_name']; ?></td>
						<?php
						if ($student['status'] == 0) {
						?>
							<td><button onclick="activeStudent(<?= $student['student_info_id']; ?>)" class="btn btn-warning text-white" > Activate</button></td>
						<?php
						} else {
						?>
							<td><span class="badge bagde-success bg-success text-white">In Class</span></td>
						<?php
						}
						?>
					</tr>
				<?php
				}
				?>

			</tbody>
		</table>
<?php
}
?>
	</div>
</div>

<script>
	function activeStudent(id) {
		/* alert(id);		 */
			/* getId('display_content').innerHTML = '<img src="../../images/ajax-loader.gif">'; */
			$.post("activate_student.php",{id:id},function(response,error){
				if(response==200){
					alert('activated')
					location.reload();
				}
			});
	}

	$(document).ready(function() {

		$('.tablexxxx').DataTable({
			responsive: true,
			dom: 'Bfrtip',
			buttons: [
				 'excel', 
				'pdf'
			],
			pageLength: 6,

		});
})
</script>