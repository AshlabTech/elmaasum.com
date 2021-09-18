<?php
session_start();
include_once('../php/connection.php');
include_once('../../php/objects.php');



$errormsg = '';
$action = "add";

if (empty($user_obj->auth())) {
?>
	<script>
		window.top.location.reload();
	</script>
<?php
}

$user_id = $user_obj->auth()->staff_info_id;
$query_string = isset($_GET['openC']) ? $_GET['openC'] : '' ;

$branch = '';
$amount = '';
$detail = '';
$id = '';
$session = "";
$term = "";
$session_id = '';
	$session = $conn->query("SELECT * FROM session WHERE status = '1'") or die(mysqli_error($conn));
		$term = $conn->query("SELECT * FROM term WHERE status = '1'") or die(mysqli_error($conn));
		if ($session->num_rows>0) {
			if ($term->num_rows>0) {
				$ss1 = $session->fetch_assoc();
				$tt1 = $term->fetch_assoc();
	 			$session_id = $ss1['section_id'];
	 			$term_id = $tt1['id'];				 
				$session =  $ss1['section'];
				$term = $tt1['description'];				
			
			}else{
				echo "term not set";
				exit();
			}	
		}else{
			echo "session not set";
			exit();
		}


//echo $session_id;
if (isset($_GET['openC'])) {
	$tid = null;
	$sid = null;
	$arr = explode('-', $_GET['openC']);
	 $tid = $arr[1];
	$sid = $arr[2];


 
	

	$session_ =  $session_obj->find($sid);
	$term_ =  $term_obj->find($tid);

	
/* 	if (!empty($session_)) {
		if (!empty($term_)) {
			$session_id = $session_->section_id;
			$section = $session_->section;
			$term = $term_->description;
			$term_id = $term_->id;
		} else {
			echo "term not set";
			exit();
		}
	} else {
		echo "session not set";
		exit();
	}*/
} 
$action = "add";
if (isset($_GET['action']) && $_GET['action'] == "edit") {
	$id = isset($_GET['id']) ? mysqli_real_escape_string($conn, $_GET['id']) : '';



	$sqlEdit = $conn->query("SELECT * FROM branch WHERE id='" . $id . "'");
	if ($sqlEdit->num_rows) {
		$rowsEdit = $sqlEdit->fetch_assoc();
		extract($rowsEdit);
		$action = "update";
	} else {
		$_GET['action'] = "";
	}
}


if (isset($_REQUEST['act']) && @$_REQUEST['act'] == "1") {
	$errormsg = "<div class='alert alert-success'><strong>Success!</strong> Class Add successfully</div>";
} else if (isset($_REQUEST['act']) && @$_REQUEST['act'] == "2") {
	$errormsg = "<div class='alert alert-success'><strong>Success!</strong> Class Edit successfully</div>";
} else if (isset($_REQUEST['act']) && @$_REQUEST['act'] == "3") {
	$errormsg = "<div class='alert alert-success'><strong>Success!</strong> Class Delete successfully</div>";
}

?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />


	<!-- BOOTSTRAP STYLES-->
	<link href="../../css/boostrap4.css" rel="stylesheet" />
	<link href="../css/bootstrap.css" rel="stylesheet" />
	<!-- FONTAWESOME STYLES-->
	<link href="../css/font-awesome.css" rel="stylesheet" />
	<!--CUSTOM BASIC STYLES-->
	<link href="../css/basic.css" rel="stylesheet" />
	<!--CUSTOM MAIN STYLES-->
	<link href="../css/custom.css" rel="stylesheet" />
	<link href="../js/listjs/PagingStyle.css" rel="stylesheet" />
	<script src="../../js/jquery3.js"></script>
    <script src="../../js/bootstrap4.js"></script>
    <script src="../../js/propper.js"></script>
    <link rel="stylesheet" type="text/css" href="../../js/datatable/datatables.min.css" />

    <script type="text/javascript" src="../../js/datatable/pdfmake.min.js"></script>
    <script type="text/javascript" src="../../js/datatable/datatables.min.js"></script>
    <script type="text/javascript" src="../../js/datatable/datatables.min.js"></script>
	<link rel="stylesheet" href="../../css/font-awesome-4.7.0/css/font-awesome.min.css">
	<script type="text/javascript" src="../../js/datatable/vfs_fonts.js"></script>

	<!-- GOOGLE FONTS-->

	
	<script src="../js/sweetalert.js"></script>
	<script src="../js/listjs/paging.js"></script>

	<style>
		#mcl {
			margin: 0px;
			padding: 14px;
			border: 1px solid #ddd;
			height: 500px;
		}
		body{
			user-select: none;
		}

		#mcl li {
			margin: 0px;
			padding: 5px;
			min-width: 190px;
			border-bottom: 1px solid #ddd;
			list-style: none;
			cursor: pointer;
			display: flex;
			justify-content: space-between;
		}
		#mcl li .s3{
			width: 10%;
		}
		#mcl li span:last-child{
			width: 90%;
		}
		.no-b{
			white-space: nowrap;
		}

		#mcl li:hover {
			background: #222;
			color: #fff;
		}

		#mcl li:focus {
			background: blue !important;
		}

		

		.s3 {
			display: block;
			font-size: 2em;
			margin-top: 15px;
		}

		#si1 {
			font-size: 0.96em;
		}

		br {
			margin: 0px !important;
			padding: 0px;
		}

		.headerC {
			/* box-shadow: #ccc 0px 1px 4px; */
			padding: 8px;
			display: flex;
		}

		.headerC button {
			margin: 3px;
			font-size: 0.94em;
			font-family: arial;
		}

		.bodyC {
			/* box-shadow: #ccc 0px 1px 4px; */
			padding: 8px;
		}

		.loader {
			border: 10px solid #bfb;
			/* Light grey */
			border-top: 10px solid #444;
			/* Blue */
			border-radius: 50%;
			width: 80px;
			height: 80px;
			animation: spin 2s linear infinite;
			position: relative;
			left: 50%;
			top: 190px;
		}

		@keyframes spin {
			0% {
				transform: rotate(0deg);
			}

			100% {
				transform: rotate(360deg);
			}
		}

		.timerC div{
    padding:5px;
}
.timerC div div:first-child{
    /* background:rgba(160,20,32,.2);
    color:rgb(160,20,32);
    border-radius:7px;
    box-shadow:1px 2px 3px rgb(160,20,32);
	
	*/
    padding:10px;
    height:50px;
}
.timerC div div span{        
    left:35px;
    /*  background:rgb(160,20,32);
     border-radius:5px; */
     padding:2px 5px;
     color:#fff;
}
.timerC div div div{
    margin:0;
    padding:0;    
    color:rgb(160,20,32);
    
}
	</style>
	</head>

<body class="w-100">
	<script type="text/javascript">
		function renderCountdown(dateEnd, display) {

			// Logs 
			// Sat Dec 19 2015 11:42:04 GMT-0600 (CST) 
			// Mon Jan 18 2016 11:42:04 GMT-0600 (CST)
			//var dateStart = new Date('<?php// echo date('Y-m-d h:m:s'); ?>');
			/*   var h17 = new Date(date.getFullYear(), date.getMonth(), date.getDate(), 17);
			   if(date.getHours() >= 17) {
			       h17.setDate(h17.getDate()+1);
			   }
			   h17 = h17.getTime();*/
			// console.log(dateStart, dateEnd); 
			let currentDate = "";
			let targetDate = dateEnd.getTime(); // set the countdown date
			let days, hours, minutes, seconds; // variables for time units
			let countdown = document.getElementById("tiles"); // get tag element
			let count = 0;
			let result = '';
			var getCountdown = function(tag) {

				let secondsLeft = (((targetDate - Date.now()) / 1000) | 0);
				days = pad(Math.floor(secondsLeft / 86400));
				secondsLeft %= 86400;
				hours = pad(Math.floor(secondsLeft / 3600));
				secondsLeft %= 3600;
				minutes = pad(Math.floor(secondsLeft / 60));
				seconds = pad(Math.floor(secondsLeft % 60));

				/* currentDate = dateStart.getTime();
        // find the amount of "seconds" between now and target
        let secondsLeft = ((targetDate - currentDate) / 1000);
        days = pad( Math.floor( secondsLeft / 86400 ) );
        secondsLeft %= 86400;
        hours = pad( Math.floor( secondsLeft / 3600 ) );
        secondsLeft %= 3600;
        minutes = pad( Math.floor( secondsLeft / 60 ) );
        seconds = pad( Math.floor( secondsLeft % 60 ) );*/
				// format countdown string + set tag value
				result = days + "days, " + hours + ":" + minutes + ":" + seconds;
				// console.log(result);
				display.textContent = result;
				//document.getElementById(tag).innerHTML = result;

			}

			function pad(n) {
				return (n < 10 ? '0' : '') + n;
			}
			getCountdown();
			setInterval(getCountdown(), 1000);
		}
	</script>
	<?php
	/*include("../php/headerF.php");*/
	?>
	<div id="ploader" style="position: fixed; top:140px; left:45%;">
		<!-- <div class="loader"></div> -->
		<div class="spinner-grow text-info" role="status" key="loader">
            <span class="sr-only">Loading...</span>
        </div>		
	</div>

	<div id="page-wrapper" style="background: #fff !important;margin:0px;padding:10px 0px;display: none; width:100%;" onload="(function(){})()" >
		<div id="page-inner">
			<div class="row w-100 pl-3">
				<div class="container-fluid col-sm-12 col-md-4 col-lg-4 text-center" style=" font-size: 2em;font-weight: bolder; font-family: arial;">Input Score For All CA</div>
				<div class="timerC col-sm-12 col-md-8 col-lg-8 mx-auto row mt-2 w-100 border rounded" >
					<div class="col-sm-6 col-md-3">
						<div>
							<span class="text-dark   d-inline">CA 1: </span>
							<div id="time1" class="alert alert-light d-inline text-danger" style="font-size:1em;font-family:arial;letter-spacing:2px; font-weight:600;">Closed</div>
						</div>
					</div>
					<div class="col-sm-6 col-md-3">
						<div>
							<span class="text-dark   d-inline">CA 2: </span>
							<div id="time2" class="alert alert-light d-inline text-danger" style="font-size:1em;font-family:arial;letter-spacing:2px; font-weight:600;">Closed</div>
						</div>
					</div>
					<div class="col-sm-6 col-md-3">
						<div>
							<span class="text-dark   d-inline">CA 3: </span>
							<div id="time3" class="alert alert-light d-inline text-danger" style="font-size:1em;font-family:arial;letter-spacing:2px; font-weight:600;">Closed</div>
						</div>
					</div>
					<div class="col-sm-6 col-md-3">
						<div>
							<span class="text-dark   d-inline">Exam: </span>
							<div id="time4" class="alert alert-light t d-inline text-danger" style="font-size:1em;font-family:arial;letter-spacing:2px; font-weight:600;">Closed</div>
						</div>
					</div>
				</div>
			</div>
			<hr >
			<div class="row w-100" style="padding:10px 0px 0px 0px; margin: 0px; display: flex;">
				<div class=" col-sm-12 col-md-3 col-lg-2" style="padding: 0px; min-width: 220px;">
				<table class="table table-hover mt-5 side-table w-100" style="border: 1px solid #ccc;border-radius; user-select:none;">
                        <thead class="d-none">
                            <th class="no-visible">Class</th>
                        </thead>
                        <tbody>
						<?php
						//$c_teacher_subject = $conn->query("SELECT * FROM staff_subjects as ss INNER JOIN classes as c ");
						//$sql = "SELECT *, sr.class_id as bid, sr.id as srid, t.id as tid, s.section_id as sid,sb.id as sbid FROM staff_subjects as sr INNER JOIN classes AS b ON sr.class_id=b.class_id INNER JOIN subject AS sb ON sr.subject_id=sb.id INNER JOIN term AS t ON t.id=sr.term_id INNER JOIN session AS s ON s.section_id='$session_id' WHERE sr.staff_info_id='$user_id'";
						$c_teacher_subject = $conn->query("SELECT *, sr.class_id as bid, sr.id as srid, t.id as tid, s.section_id as sid,sb.id as sbid FROM staff_subjects as sr INNER JOIN classes AS b ON sr.class_id=b.class_id INNER JOIN subject AS sb ON sr.subject_id=sb.id INNER JOIN term AS t ON t.id=sr.term_id INNER JOIN session AS s ON s.section_id='$session_id' WHERE sr.staff_info_id='$user_id'");
						
						echo mysqli_error($conn);
						if ($c_teacher_subject->num_rows > 0) {
							$num = 0;
							while ($row = $c_teacher_subject->fetch_assoc()) {
								$num++;
								$srid = $row['srid'];
								/*	$session =$row['section'];
									$term =$row['description'];*/
						?>
						
						<tr id="<?php echo 'list' . $srid; ?>" tabindex="1">
                                <td class="py-2  px-4 booxinn d-flex" style="display: flex;align-items:center;">
								<span class="fa fa-book s3  d-inline" style="width: 20%;"></span>
								<span class=" d-inline" id="si1" style="width: 80%;">
									<span class="no-b mr-3"><?php echo ucwords($row['subject']); ?><br><?php echo ucwords($row['class_name']); ?></span>
									<span class="text-center" ><span class="no-b"> <?php echo ucwords($row['description']); ?> </span>, <?php echo ucwords($row['section']); ?> session </span> 
								</span>
                                </td>
                            </tr>								
								<script>
									
									$(document).ready(function() {
										$('#list<?php echo $srid ?>').click(function() {
											document.getElementById('ploader').style.display = 'block';
											//alert();
											var user_id = <?php echo $row['bid']; ?>;
											var b = "<?php echo $row['class_name']; ?>";
											var tid = <?php echo $row['tid']; ?>;
											var sid = <?php echo $row['sid']; ?>;
											var sbid = <?php echo $row['sbid']; ?>;
											document.cookie = "openC=" + user_id + '-' + tid + '-' + sid + '-' + sbid;
											localStorage.setItem("thxxxm", "<?= $row['description'].', '.$row['section'];?>");

											window.location = 'input-scoreA.php?openC=' + user_id + '-' + tid + '-' + sid + '-' + sbid;

											/*
										$.get('misc/subject_role.php',{id:lid,type:'subjectRole',session:sid,term:tid, b:b}, function(data){
											//alert(data);
											$('#class-cont').html(data);
										});*/
										});
									});
								</script>
						<?php
							}
						}
						?>

                        </tbody>
                    </table>				
				</div>
				<script>
					//alert();
					$(function() {
						$('.side-table').DataTable({
							responsive: true,
							dom: 'Bfrtip',
							buttons: [
								/* 'copy', 'excel', */
								'pdf'
							],
							pageLength: 7,
							bInfo: false,
						});
					});
					
				</script>
				<div class=" col-md-8 col-lg-10" style="overflow-x:scroll">
					<div id="" style="min-width: 350px;">											
						<div style="position: absolute;top:-21.3%; left: 0; width: 100%;  overflow-y: none;">
							<div class="loader" id="load1" style="margin-left: 0px; margin-right: 0px; display:none;"></div>
						</div>
						<div id="class-cont">
							<div style="border-bottom: 1pt solid #aaa;" class="shadow-sm p-2">
								<p style="font-size: 1.3em; font-family: arial; font-weight: bold; color: #288;">
								<span id="selectedTK"></span></p>							
								<p class="p-0 m-0 w-25"  >								
									<span style="width: 100px; display:inline-block;">Class:</span>
									<span id="showClass"></span>
								</p>
								<span class="d-flex">
									<p class="p-0 m-0" style="width: 100px;">Subject: </p><p class="p-0 m-0 w-25" id="showSubject"></p>
								</span>
							</div>
							<center>
								
							</center>
							<div style="border: 1px solid #eee; padding: 8px;">
								<?php
								if (isset($_REQUEST['openC'])) {
									$opPHP = "'" . $_REQUEST['openC'] . "'";


								?>
									<script>
										
										//use cookie for some checking (security) so that whe alternate data passed to url page reload;
										function getCookie(name) {
											var dc = document.cookie;
											var prefix = name + "=";
											var begin = dc.indexOf("; " + prefix);
											if (begin == -1) {
												begin = dc.indexOf(prefix);
												if (begin != 0) return null;
												//window.location='input-scoreA.php';

											} else {
												begin += 2;
												var end = document.cookie.indexOf(";", begin);
												if (end == -1) {
													end = dc.length;
												}
											}
											// because unescape has been deprecated, replaced with decodeURI
											//return unescape(dc.substring(begin + prefix.length, end));
											return decodeURI(dc.substring(begin + prefix.length, end));
										}


										var myCookie = getCookie("openC");
										
										document.getElementById('selectedTK').innerHTML = localStorage.getItem("thxxxm");
										if (myCookie == null) {
											// do cookie doesn't exist stuff;
											//alert(2);
										} else {
											//if url changed or cookie destroy
											var script_op = myCookie.split(';');
											if (script_op[0] != <?php echo $opPHP; ?>) {
												window.location = 'input-scoreA.php';
											}
										}
										
									</script>

									<?php
									

									$op = explode('-', $_REQUEST['openC']);
									$bid = $op[0];
									$tid = $op[1];
									$sid = $op[2];
									$sbid = $op[3];

									$sql = $conn->query("SELECT * FROM score_time_frame as st INNER JOIN classes as b ON b.class_id='$bid' WHERE st.term_id='$tid' AND st.section_id =b.school_section_id");
									//echo mysqli_error($conn);
									//echo "SELECT * FROM score_time_frame as st INNER JOIN classes as b ON b.class_id='$bid' WHERE st.term_id='$tid' AND st.section_id =b.school_section_id";
									$caOpen = array();
									$cA1O = $cA2O = $cA3O = $examO = '';
									$cA1C = $cA2C = $cA3C = $examC = 0;
									$ca1Close = array();
									//= $ca2Close = $ca3Close =$examClose ='';
									$timing = 0;
									if ($sql->num_rows > 0) {										
										$timing = $sql->num_rows;
										while ($timeframe = $sql->fetch_assoc()) {
											$caOpen[$timeframe['ca_id']] = $timeframe['start_date'];
											$caClose[$timeframe['ca_id']] = $timeframe['end_date'];
										}
										foreach ($caOpen as $key => $value) {
											if ($key == 1) {
												$cA1O = $value;
											} elseif ($key == 2) {
												$cA2O = $value;
											} elseif ($key == 3) {
												$cA3O = $value;
											} elseif ($key == 4) {
												$examO = $value;
											}
										}
										foreach ($caClose as $key => $value) {
											if ($key == 1) {
												$cA1C = $value;
											} elseif ($key == 2) {
												$cA2C = $value;
											} elseif ($key == 3) {
												$cA3C = $value;
											} elseif ($key == 4) {
												$examC = $value;
											}
										}
										//if(sizeof($ca)==1){}
										//echo sizeof($caOpen);
										//echo var_dump($caOpen);
									}

									?>
									<style>
									</style>
									<script>
										
										(function(){
											var timing = <?php echo $timing; ?>;
																						
											if (timing != 0) {											
										setInterval(function(){ 
											
											$.post('misc/timeframe_lock.php',{cdate:'<?php echo $cA1C; ?>'}, function(data){												
												
												if(data!=0){
												    //var timeLeft1 = data *3600,
												    $(document).ready(function(){
												    	var display1 = document.querySelector('#time1');
												    	renderCountdown( new Date('<?php echo $cA1C; ?>'), display1);											    	
												    })

												    //startTimer(display1);
												}else if(data==0){
													//	alert(1);
													$(document).ready(function(){

													$('.ca1').prop('disabled',true);
													});
													
												}
											});
											
											$.post('misc/timeframe_lock.php',{cdate:<?php echo $cA2C; ?>}, function(data){
												if(data !=0){
													 $(document).ready(function(){
												    	var display2 = document.querySelector('#time2');
												    	renderCountdown( new Date('<?php echo $cA2C; ?>'), display2);											    	
												    })
												}else if(data==0){													
													$(document).ready(function(){
														
													$('.ca2').prop('disabled',true);
													});
													
												}
											});
											$.post('misc/timeframe_lock.php',{cdate:<?php echo $cA3C; ?>}, function(data){
												if(data !=0){
													 $(document).ready(function(){
												    	var display3 = document.querySelector('#time3');
												    	renderCountdown( new Date('<?php echo $cA3C; ?>'), display3);											    	
												    })
												}else if(data==0){
													$(document).ready(function(){
														
													$('.ca3').prop('disabled',true);
													});
													
												}
											});
											$.post('misc/timeframe_lock.php',{cdate:<?php echo $examC; ?>}, function(data){
												if(data !=0 ){
													 $(document).ready(function(){
												    	var display4 = document.querySelector('#time4');
												    	renderCountdown( new Date('<?php echo $examC; ?>'), display4);											    	
												    })
												}else if(data==0){
													$('.cae').prop('disabled',true);
													
												}
											});

										 }, 100);
											}else{
												//if not timing not created disable all													
												$(document).ready(function(){
													$("#saveBtn").prop("onclick", null).off("click");
													$('.ca1').prop('disabled',true);
													$('.ca2').prop('disabled',true);
													$('.ca3').prop('disabled',true);
													$('.cae').prop('disabled',true);
												})
											}
										})();
									</script>
									<?php
									//echo checkDate1($cA1C);

									$get_class = $conn->query("SELECT * FROM classes WHERE class_id='$bid'");
									$get_classq = $get_class->fetch_assoc();
									$selected_class = $get_classq['class_name'];
									$get_subject = $conn->query("SELECT * FROM subject WHERE id='$sbid'");
									$get_subjectq = $get_subject->fetch_assoc();
									$subject_section = $get_subjectq['school_section'];
									$selected_subject = ucwords($get_subjectq['subject']);
									echo "<script>
											var bid = " . $bid . ";
											var tid = " . $tid . ";
											var sid = " . $sid . ";
											var sbid = " . $sbid . ";
											document.getElementById('showClass').innerHTML=' ";
									echo $selected_class;
									echo "';
										document.getElementById('showSubject').innerHTML='";
									echo $selected_subject;
									echo "';
									</script>";
									?>
									<script>
										var cm = 0;
										selected1 = '';
										selected = '';
										var all_student_info_id = '';
									</script>
									
									<div class="bodyC">
									<form action="submit-score.php?string=<?= $query_string;?>" method="post">
										<p><input type="hidden" name="session_id" value="<?= $sid; ?>"></p>
										<p><input type="hidden" name="term_id" value="<?= $tid; ?>"></p>
										<p><input type="hidden" name="class_id" value="<?= $bid; ?>"></p>
										<p><input type="hidden" name="subject_id" value="<?= $sbid; ?>"></p>
										
									<div class="headerC" style="">
										<!-- <button>Download template</button>
										<button>Upload From template</button> -->
										<!--<form class="p-0 m-0 d-inline-block" method="post" action="subject_ca_pdf.php">-->
											<input type="text" name="data" id="dataValue" style="display: none;">
											<input type="text" name="subject" style="display: none;" value="<?= $selected_subject; ?>">
											<input type="text" name="class" style="display: none;" value="<?= $selected_class; ?>">
											<input type="text" name="term" style="display: none;" value="<?php echo ucwords($term); ?>">
											<input type="text" name="section" style="display: none;" value="<?php echo ucwords($subject_section); ?>">
											<input type="text" name="session" style="display: none;" value="<?= $session; ?>">
											<button class="btn btn-info" type="submit">Print</button>
										<!--</form>-->
										<button class="btn btn-info" id="saveBtn" onclick="saveResultBtn();">save</button>
										<span id="dttest_length"></span>
									</div>
										<table class="table table-condensed table-striped table-hover datatb">
											<thead>
												<tr>
												<?php
												$get_set_score = $conn->query("SELECT * FROM score WHERE section_id = '$subject_section' AND activate='1'");
												$scorep = $get_set_score->fetch_assoc();
												$ca1Data = $scorep['ca1'];
												$ca2Data = $scorep['ca2'];
												$ca3Data = $scorep['ca3'];
												$examData = $scorep['exam'];
												?>
												<th width="3%" style="cursor: pointer;"><input type="checkbox" id="chkA" onclick="(function(){cm+=1;selected =chkAll(cm,'chkclass');})()"></th>
												<th width="5%">s/n</th>
												<th width="10%">Adm No.</th>
												<th width="20%">Name</th>
												<th width="6%">CA1 <br> 10</th>
												<th width="6%">CA2 <br> 10</th>
												<th width="6%">CA3 <br> 10</th>
												<th width="6%">P-Q <br> 10</th>
												<th width="6%">ASS <br> 10</th>
												<th width="6%">Exams <br> 50</th>
												<th width="10%">Total <br>100%</th>
												</tr>
											</thead>

											<tbody>
												<?php
												//echo $bid;
												$dataValueForPDF = '';
											$form_index = 0;
												$get_student_in_class = $student_obj->getAllStudentByClass($bid, $sid);
												if (count($get_student_in_class) > 0) {
												//	echo "<script>alert(" . mysqli_error($conn) . ");</script>";
												}
												if (count($get_student_in_class) > 0) {
													$n = 1;
													$studentIds = array();

													foreach ($get_student_in_class as $key => $student_) {
														$form_index++;
														$stid = $student_->student_info_id;

														$ca1 = null;
														$ca2 = null;
														$ca3 = null;
														$ca4 = null;
														$ca5 = null;
														$exam = null;
														$total = null;

														$data['student_info_id'] = $stid;
														$data['session_id'] = $sid;
														$data['class_id'] = $bid;
														$data['term_id'] = $tid;
														$data['subject_id'] = $sbid;

														if(!empty($mark_obj->exist($data))){
               
															$mark = $mark_obj->exist($data);
															$ca1 = $mark->ca1;
															$ca2 = $mark->ca2;
															$ca3 = $mark->ca3;
															$ca4 = $mark->ca4;
															$ca5 = $mark->ca5;
															$exam = $mark->exam;
															$total = $mark->total;
														}
													
												?>
													
														<tr>
															<td><input type="checkbox" name="chk" value="<?php echo $stid; ?>" id="<?php echo 'ck' . $stid; ?>" class="chkclass" onclick="(function(){ selected = selChk($('<?php echo "#ck" . $stid; ?>'),selected);})()"></td>

															<td><?php echo $n . '.'; ?></td>
															<td><?=$student_->adm_no; ?></td>
															<td ><?= $student_obj->formatName($student_); ?></td>
															


															<td><input onkeypress="return isNumber(event)" class="form-control ca1" type="text" value="<?= $ca1; ?>" max="10" min="0" name="ca1[<?= $stid; ?>]" id="ca1_<?php echo $stid; ?>" style="width: 60px;"></td>
															<td><input onkeypress="return isNumber(event)" class="form-control ca2" type="text" value="<?= $ca2; ?>" max="10" min="0" name="ca2[<?= $stid; ?>]" id="ca2_<?php echo $stid; ?>" style="width: 60px;"></td>
															<td><input onkeypress="return isNumber(event)" class="form-control ca3" type="text" value="<?= $ca3; ?>" max="10" min="0" name="ca3[<?= $stid; ?>]" id="ca3_<?php echo $stid; ?>" style="width: 60px;"></td>
															<td><input onkeypress="return isNumber(event)" class="form-control" type="text" value="<?= $ca4; ?>" max="10" min="0" name="ca4[<?= $stid; ?>]" id="ca4_<?php echo $stid; ?>" style="width: 60px;"></td>
															<td><input onkeypress="return isNumber(event)" class="form-control" type="text" value="<?= $ca5; ?>" max="10" min="0" name="ca5[<?= $stid; ?>]" id="ca5_<?php echo $stid; ?>" style="width: 60px;"></td>
															<td><input onkeypress="return isNumber(event)" class="form-control cae" type="text" value="<?= $exam; ?>" max="50" min="0" name="exam[<?= $stid; ?>]" id="ca6_<?php echo $stid; ?>" style="width: 60px;"></td>
															<td><input onkeypress="return isNumber(event)" class="form-control" type="text" value="<?= $total; ?>" max="100" min="0" name="" readonly style="width: 60px;">
															<input type="hidden" name="student_id[]" value="<?= $stid; ?>"></td>
															
														</tr>
														<script>
														
													</script>
													<?php
														$n++;
													}
													

													// mysqli_error($conn);
												} else {
													//echo "string";
												}

												?>
											</tbody>
										</table>
									</form>

									</div>


								<?php
								} else {
								?>
									<div class="headerC" style="">
										<button>Assign Subject</button>
										<button>Remove Subject</button>
										<button>Generate Report</button>
									</div>
									<div class="bodyC">
										<table>
											<thead>
												<th>Teacher</th>
											</thead>
											<tbody>
												<td></td>
											</tbody>
										</table>

									</div>
								<?php
								}
								?>
							</div>
						</div>
					</div>
				</div>


			</div>
<!-- 
			<script src="../js/dataTable/jquery.dataTables.min.js"></script>
			<script>
				
				$("#tSortable22").dataTable();
			</script> -->

			<!-- Modal -->
			<div class="modal fade" id="myModal" role="dialog">
				<div class="modal-dialog modal-lg" style="width: 400px;">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h4 class="modal-title">Select Teacher-Subject</h4>
						</div>
						<div id="assinS" style="padding: 40px;">

							<div class="modal-body" id="formcontent">

							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
						</div>
					</div>
				</div>
			</div>


		</div>
		<!-- /. PAGE INNER  -->
	</div>
	<!-- /. PAGE WRAPPER  -->
	</div>
	<!-- /. WRAPPER  -->

	<div id="footer-sec">
		SCHOOL SYSTEM
	</div>


	<!-- BOOTSTRAP SCRIPTS -->
	<!-- <script src="../js/bootstrap.js"></script> -->
	<!-- METISMENU SCRIPTS -->
	<!-- <script src="../js/jquery.metisMenu.js"></script> -->
	<!-- CUSTOM SCRIPTS -->
	<!-- <script src="../js/custom1.js"></script> -->
	<script>
		
		//start checkbox
		function chkAll(source,name){
			selArray = '';
			chkname = document.getElementsByClassName(name);
			for (var i = 0; i < chkname.length; i++) {
			//alert(source.checked);
				if(source % 2==1){
					chkname[i].checked = true;
					selArray+= chkname[i].value +',';
				}else{
					chkname[i].checked = false;
					selArray='';
				}

		}
				return selArray;
	}
    	function selChk(s,p){
		var chkAll = document.getElementById('chkA').checked;
		var id = s.val();
		//alert();
		var selct = '';
		if(chkAll==true){
			if(s.prop('checked')==true){
				p += id+',';
				//alert(p);
				return p;
			}else if(s.prop('checked')==false){
				if(p.length != ''){
					var selV = p.split(",");
				}else{
					var selV;
				}
				for (var i = 0; i < selV.length-1; i++) {
					var len = id.length;
					var index = selV.indexOf(id);
					if(id == selV[i]){
						selV.splice(index,1);
					}
				}
					p ='';
				for (var i = 0; i < selV.length-1; i++) {
					p += selV[i]+',';
				}
				//alert(p);
				return p;
			}
		}else if(chkAll==false){
			if(s.prop('checked')==true){
				p += id+',';
				//alert(p);
				return p;
			}else if(s.prop('checked')==false){
				if(p.length != ''){
					var selV = p.split(",");
				}else{
					var selV;
				}
				for (var i = 0; i < selV.length-1; i++) {
					var len = id.length;
					var index = selV.indexOf(id);
					if(id == selV[i]){
						selV.splice(index,1);
					}
				}
					p ='';
				for (var i = 0; i < selV.length-1; i++) {
					p += selV[i]+',';
				}
				//alert(p);
				return p;
			}
		}
	}
	function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
	}
	function isNumberCA(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
     
        return false;
    }
    return true;
	}
	function disableALL(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode >0) {
        return false;
    }
    return true;
	}
	function saveResultBtn(){
		if(all_student_info_id == ''){
			Swal.fire('No Student in class');
		}else{
			var data = [];
			//alert(all_student_info_id.length);
			for (var i = 0; i < all_student_info_id.length; i++) {
				var r = all_student_info_id[i];
				var s = $('#ca1_'+all_student_info_id[i]).val();
				var p = $('#ca2_'+all_student_info_id[i]).val();
				var d = $('#ca3_'+all_student_info_id[i]).val();
				var f = $('#exam_'+all_student_info_id[i]).val();
				var t = $('#total_'+all_student_info_id[i]).val();
				dat = {id:r,ca1:s,ca2:p,ca3:d,exam:f,total:t};
				data.push(dat);

			}
			$.post('misc/save_input_score.php',{data:data,bid:bid,tid:tid,sid:sid,sbid:sbid},function(data){
				$('#load1').hide();
				//alert(data);
				if(data==1){
					Swal.fire({
						type: 'success',
						title: 'save successfully',
						showConfirmButton: true
					});
				}else{
					let dataArr = data.split('');
					let dataSum = dataArr.reduce((a, b) => a + b) / dataArr.length;
					if(dataSum==1){
						Swal.fire({
							type: 'success',
							title: 'save successfully',
							showConfirmButton: true
						});
					}else{
						alert(data);
					}
				}
			});
			$('#load1').show();
		}
	}



		function saveResultBtn() {
			if (all_student_info_id == '') {
				Swal.fire('No Student in class');
			} else {
				var data = [];
				//alert(all_student_info_id.length);
				for (var i = 0; i < all_student_info_id.length; i++) {
					var r = all_student_info_id[i];
					var s = $('#ca1_' + all_student_info_id[i]).val();
					var p = $('#ca2_' + all_student_info_id[i]).val();
					var d = $('#ca3_' + all_student_info_id[i]).val();
					var f = $('#exam_' + all_student_info_id[i]).val();
					var t = $('#total_' + all_student_info_id[i]).val();
					dat = {
						id: r,
						ca1: s,
						ca2: p,
						ca3: d,
						exam: f,
						total: t
					};
					data.push(dat);

				}
				$.post('misc/save_input_score.php', {
					data: data,
					bid: bid,
					tid: tid,
					sid: sid,
					sbid: sbid
				}, function(data) {
					$('#load1').hide();
					//alert(data);
					if (data == 1) {
						Swal.fire({
							type: 'success',
							title: 'save successfully',
							showConfirmButton: true
						});
					} else {
						let dataArr = data.split('');
						let dataSum = dataArr.reduce((a, b) => a + b) / dataArr.length;
						if (dataSum == 1) {
							Swal.fire({
								type: 'success',
								title: 'save successfully',
								showConfirmButton: true
							});
						} else {
							alert(data);
						}
					}
				});
				$('#load1').show();
			}
		}


		$(document).ready(function() {			
			document.getElementById('ploader').style.display = 'none';
			$('#page-wrapper').show();
			setTimeout(() => {
			  var table = $('.datatb').DataTable({
						responsive: true,						
						buttons: [									
							'pdf'
						],
						pageLength: 200,
						columnDefs: [{
							orderable: false,
							targets: [6,7,8,9,10]
						}]
		
					});
					table.buttons().container()
					.insertAfter('#dttest_length');					
			}, 1000);
		});

		/*$('.table').DataTable({saveState:true,});*/
	</script>


</body>

</html>