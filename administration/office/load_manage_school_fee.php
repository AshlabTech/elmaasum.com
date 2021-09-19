<?php session_start(); ?>
<?php
	if(isset($_SESSION['staff_info_id'])){
		
	}
	else{
		header('location:../');
	}
	include_once('../php/connection.php');
	
		
?>
<!DOCTYPE html>
<html>
	<head>
		<title> <?php echo $school_abbr; ?> </title>
			
		<link rel="shortcut icon" href="../../images/e_portal.png">
	   <meta name="viewport" content="width=device-width, initial-scale=1" >
	   
		<link rel="stylesheet" href="../../css/bootstrap.css">
		<link rel="stylesheet" href="../js/bootstrap.css">
		<link rel="stylesheet" href="../../css/bootstrap.min.css">
		<link rel="stylesheet" href="../../css/bootstrap-theme.css">
		<link rel="stylesheet" href="../../css/bootstrap-theme.min.css">
		<link rel="stylesheet" href="../../css/font-awesome-4.7.0/css/font-awesome.min.css">
		<link rel="stylesheet" href="../../css/styles.css">
		<script src="../Login_v1/vendor/jquery/jquery-3.2.1.min.js"></script>
	<script src="../js/vue.js"></script>
				
			
		
		<style>
		body{
			color:;
			margin:0;
			padding:0;
			font-size : 11.5px;
			width: 100%;
		}
		</style>

	</head>
<body style="background:#fff" cl>

<div class="container-fluid" style="margin:0;padding:0" id="summary_payment_id100">
				<div class="row w-100" style="margin-bottom:10px">
					<div class="col-md-12 " style="padding:">
						
							<div class="form-inline"  style="margin:5px 20px">
								  
								  <div class="form-group">
									<label class="" for="">Session</label>
									<select class="form-control" style="display:inline-block" id="current_session"  onchange="display_classes_payment()">
										
										<?php 
											$query2 = mysqli_query($conn,"select * from session order by status DESC ") or die(mysqli_error($conn));
											while($class_array2 = mysqli_fetch_array($query2)){
												$session_id = $class_array2['section_id'];
												$session = $class_array2['section'];
												if($class_array2['status'] == '1'){
												    $first_yr = substr($session,0,4);
											    	$second_yr = substr($session,-4);
												}
												
												echo '<option value="'.$session_id.'">'.$session.'</option>';
												
												$_SESSION['session_id'] = $session_id;
										
											}
									?>
									</select>
								  </div>
								  
								  <div class="form-group">
									<label class="" for="">Term</label>
									<select class="form-control" style="display:inline-block" id="current_term"  onchange="display_classes_payment()">
										
										<?php 
											// Get the current term
                                    			$term = mysqli_query($conn,"select * from term  where status = '1'") or die(mysqli_error($conn));
                                    			$term_array = mysqli_fetch_array($term);
                                    			$term = $term_array['term'];
                                    			$term_id = $term_array['id'];
                                    			$term_full = $term_array['description'];
                                    			
                                    			echo '
                                    			<option value="1" '.($term_id == 1 ? 'selected': '').'>First Term</option>
                                    			<option value="2" '.($term_id == 2 ? 'selected': '').'>Second Term</option>
                                    			<option value="3" '.($term_id == 3 ? 'selected': '').'>Third Term</option>
                                    			';
									?>
									</select>
								  </div>
								  <div class="form-group">
									<label class="" for="">Year</label>
									<select class="form-control"  style="display:inline-block" id="select_yr">
									<?php 
											$year = mysqli_query($conn,"select * from year where status = '1'") or die(mysqli_error($conn));
											while($year_array = mysqli_fetch_array($year)){
												$current_year = $year_array['year'];
												if($current_year == $first_yr)
													echo '<option value="'.$current_year.'" selected>'.$current_year.'</option>';
												else
													echo '<option value="'.$second_yr.'" >'.$second_yr.'</option>';
													
												$_SESSION['current_year']= $current_year;
										
											}
												//Export attendance to excel 
												//include_once('export_attendance_toExcel.php');
									?>
										
					
									</select>
								  </div>
								  
								
							</div>
					</div>
				</div>
						<div class="breadcrumb ace-save-state" id="" style="margin:0;background-color:#337ab7; border-color: #2e6da4;color:#fff;border-radius:0">
							<span style="float:right;font-family:;"><span id="timing"></span><strong><?php echo @date('M-D-Y');?></strong></b></span>
								<i class="ace-icon fa fa-user home-icon"></i><a href="#" style="color:#fff">   <b> SMS</a>
								
							
						</div>
						
						
						<nav class="navbar navbar-default" style="margin:0;border-color:#CCC">
						  <div class="container-fluid">
							<div class="navbar-header">
							  <a class="navbar-brand" class="btn"  href="#">
								
								<span style="font-size:20px;font-family : 'Arial Black';padding-left:20px;float:right"><i class="menu-icon fa fa-desktop"></i> <?php echo $school_abbr; ?> SCHOOL FEES SECTION</span>
							  </a>
							</div>
							
							   <!-- Collect the nav links, forms, and other content for toggling -->
								<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
								  
								 
									<div class="nav navbar-nav navbar-right">
												 <form class="navbar-form navbar-left" role="search">
													<div class="form-group">
													  <input type="text" class="form-control" placeholder="Search by payment No.">
													</div>
													<button type="submit" class="btn btn-default">	Search</button>
												  </form>
								 
									</div>
								</div><!-- /.navbar-collapse -->
	
						  </div>
						</nav>
						
				
			
			<div class="row w-100" style="margin:0;;min-height:400px;">
				<div class="col-md-10" id="make_payement_student_search_wrap" style="padding:0 0 0 4px;">
					<div id="image_slider_wrap" >
						<div id="outerbox" >
						<div id="slide_box">
							<img src="../images/student.jpg">
							<img src="../images/mtn.jpg">
							<img src="../images/uba.jpg">
							
						</div>
					</div>
					</div>
				</div>	
				<div class="col-md-2" style="border:1px solid #ccc;height:100%;padding-top:20px">
					<h4>Select Class</h4>
					<p><select id="student_class" name = "department" class="form-control" style="width:80%;max-width:300px;" onchange="make_payment_search(this.value)">
								<option value="">Search by class</option>
									<?php 
											$query = mysqli_query($conn,"select * from classes") or die(mysqli_error($conn));
											while($class_array = mysqli_fetch_array($query)){
												$class_id = $class_array['class_id'];
												$class = $class_array['class_name'];
												echo '<option value="'.$class_id.'">'.$class.'</option>';
											}
									?>
							</select>
					</p>
					
					<div class="list-group" >
						  
						  <a href="#" class="list-group-item btn btn-default" style="font-size: 1.1em;text-align:right; background-color:#f0f0f0" onclick="display_classes_payment()"> <i class="fa fa-keys"></i> Classes Summary</a>
						  <a href="#" class="list-group-item btn btn-default" style="font-size: 1.1em;text-align:right; background-color:#f0f0f0" onclick="display_deptors_list_by_classes()"> <i class="fa fa-keys"></i> Deptors In Classes </a>
						  <a href="#" class="list-group-item btn btn-default" style="font-size: 1.1em;text-align:right; background-color:#f0f0f0" onclick="display_other_payments()"> <i class="fa fa-keys"></i>Other Payments</a>
						  <a href="#" class="list-group-item btn btn-default" style="font-size: 1.1em;text-align:right; background-color:#f0f0f0"  onclick="display_term_payment_summary()"> <i class="fa fa-keys"></i>Print Term Payment Summary</a>
						  <a href="#" class="list-group-item btn btn-default" style="font-size: 1.1em;text-align:right; background-color:#f0f0f0" onclick="display_session_payment_summary()"> <i class="fa fa-keys"></i>Print Session Payment Summary</a>
						  <a href="#" onclick="window.location.assign('../php/logout.php')" class="list-group-item btn btn-default" style="background-color:#f0f0f0;color:red">Logout</a>
						</div>
				</div>					
			</div>			
			
						

</div>
						
				<!-- inlcude all javascript files -->
				<script type="text/javascript" src="../../js/jquery-1.10.2.js"></script>
				<script type="text/javascript" src="../js/admin_script.js"></script>
				<script type="text/javascript" src="../js/admin_reg_script.js"></script>
				<script type="text/javascript" src="../js/timing.js"></script>

					<script>
  $(function () {
    $('#myTab li').on('click',function(){
		var name = $(this).attr("name");
	
		  $('#myTab li').removeAttr('class') ;
		  $(this).addClass('active');
		  
		  $(".tab-content div").removeAttr('class');
		  $(".tab-content div").addClass('tab-pane');
		   
		   $("#"+name).removeAttr('class');
		  $("#"+name).addClass('tab-pane active');
	
	});
  })
  

</script>

	</body>
</html>