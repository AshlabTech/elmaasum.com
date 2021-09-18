<?php
session_start();
?>

<head>
	<title> Staff Portal </title>


	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="../../../css/basic.css" rel="stylesheet" />
	<link rel="stylesheet" type="text/css" href="../../css/boostrap4.css" />
	<!-- <link rel="stylesheet" href="../../../css/bootstrap.css"> -->
	<!-- <link rel="stylesheet" href="../../../css/bootstrap.min.css"> -->
	<link rel="stylesheet" href="../../../css/bootstrap-theme.css">
	<link rel="stylesheet" href="../../../css/bootstrap-theme.min.css">
	<link rel="stylesheet" href="../../../css/font-awesome-4.7.0/css/font-awesome.min.css">

	<script src="../../js/jquery3.js"></script>
	<script src="../../js/bootstrap4.js"></script>

	<script src="../../js/propper.js"></script>
	<link rel="stylesheet" type="text/css" href="../../js/datatable/datatables.min.css" />

	<script type="text/javascript" src="../../js/datatable/pdfmake.min.js"></script>
	<script type="text/javascript" src="../../js/datatable/datatables.min.js"></script>
	<script type="text/javascript" src="../../js/datatable/vfs_fonts.js"></script>
	<script src="../../js/vue.js"></script>

	<style>

	</style>
	<style>
		.trt {
			border-bottom: 1px solid #aaa;
			color: black;
			font-size: 0.9em;
			font-weight: bold;
			font-family: arial;
			margin: 1px !important;
		}

		.d-none {
			display: none;
		}

		.row {
			margin-left: 5px;

		}

		input[type='radio'] {
			margin: 3px;
			cursor: pointer;
		}

		.bd:hover {
			background: #555 !important;
			color: white;
			box-shadow: 2px 2px 3px #aaa;
		}

		.co {
			font-size: 0.9em;
			font-family: arial;
			padding: 2px;
		}

		.bd:nth-child(2n+2) {
			background: #eee;
		}

		.bd {
			margin-right: 5px;
		}

		.btn2 {
			padding: 6px;
			display: block;
			margin: 2px auto;
			width: 70%;
			text-align: center;
			text-decoration: none !important;
			color: #2064a0;
			border: 1px solid #2064a0;
			border-radius: 5px;
			background-color: white;
			font-size: 0.85em;
		}

		.ptt-2 {
			height: 100px;
		}

		.btn2:hover {
			background-color: #2064a0;
			color: white;
		}

		.modal-backdrop {
			background-color: transparent;
		}

		th {
			color: #777;
			font-size: 0.9em;
		}
		td{
			font-size: 0.9em;
		}
	</style>
</head>
<?php
if (isset($_SESSION['staff_info_id'])) {
	$type = $_SESSION['type'];
	if ($type == 1) {
		//	header('location:../staff');
	}
	$staff_info_id = $_SESSION['staff_info_id'];
} else {
?>
	<script>
		parent.location.reload();
	</script>

<?php

}


?>
<?php
include '../php/connection.php';
$get_session = mysqli_query($conn, "select * from session WHERE status = '1'");
$current_session = mysqli_fetch_array($get_session);
$current_session_id = $current_session['section_id'];


if (isset($_SESSION['staff_info_id'])) {
	$staff_info_id = $_SESSION['staff_info_id'];
} else {
	header('location:../? token=2');
}
include_once('../php/staff_data.php');
$class_id = '';
$term_id = '';
$stid = 0;
$classname="";
$termname="";
?>
<?php

//$staff_info_id = $_POST['token'];
include_once('../../php/staff_data.php');
?>

<h4><i class="menu-icon fa fa-desktop"></i> Form Teacher</h4>

<br>
<div class="row w-100 p-0 m-0" style="color:#067;;" id="access_options_wrap">
	<div class="col-lg-12 p-0">
		<form class="form-horizontal p-0 m-0" method="POST" action="">
			<div class="form-group row w-100 m-0 0-0">
				<div class="col-sm-3 pl-0">
					<input type="text" value="" id="classname" name="classname" style="display: none;">
					<input type="text" value="" id="termname" name="termname" style="display: none;">
					<select required="" class='form-control' name="class_id" onchange="(function(){document.getElementById('classname').value= document.getElementById('class_idd').options[document.getElementById('class_idd').selectedIndex].text; })()" style="margin:5px;color:#000" id="class_idd">
						<option value="">-- SELECT CLASS -- </option>
						<?php
						//check if the class is already assign but status = 1			
						$check_if_assigned = mysqli_query($conn, "select * from staff_classes where staff_info_id = '$staff_info_id'  and status = '1'") or die(mysqli_error($conn));
						$check_if_assigned_num_rows = mysqli_num_rows($check_if_assigned);
						if ($check_if_assigned_num_rows > 0) {
							$mm = 1;
							while ($class_rows = mysqli_fetch_array($check_if_assigned)) {
								$class_id = $class_rows['class_id'];


								$class_name = "select * from classes where class_id='$class_id'";
								$class_name_run =  mysqli_query($conn, $class_name) or die(mysqli_error($conn));
								$class_name_rows = mysqli_fetch_array($class_name_run);

								$class_n = $class_name_rows['class_name'];

								echo '<option value=' . $class_id . '>' . $class_n . '</option>';
							}
						}
						?>
					</select>
				</div>
				<div class="col-sm-3">
					<select required="" class='form-control' name="term" style="margin:5px;" id="term_id" onchange="(function(){document.getElementById('termname').value= document.getElementById('term_id').options[document.getElementById('term_id').selectedIndex].text; })()">
						<option value="">SELECT TERM</option>
						<option value="1">first term</option>
						<option value="2">second term</option>
						<option value="3">third term</option>
					</select>
				</div>
				<div class="col-sm-3">
					<input type="submit" name="enterT" value="load" class="btn btn-primary mt-1">
				</div>
				<div class="col-sm-3">
					<select class="float-right form-control" id="pageType">
						<option value="trait">Traits</option>
						<option value="comment">comments</option>
					</select>
				</div>
			</div>
		</form>
	
		<hr>
	</div>

	<div class="row w-100 m-0" id="traitPage">
	
		<div class="p-0 col-sm-6 col-md-6 col-lg-5" style="min-width: 400px;">
			<div id="">
				<div style="position: absolute;top:-21.3%; left: 0; width: 100%;  overflow-y: none;">
					<div class="loader" id="load1" style="margin-left: 0px; margin-right: 0px; display:none;"></div>
				</div>
				<div id="class-cont" style="color:#666;">
					<p style="" id="showClass"></p>

					<div style="">
						<?php
						if (isset($_POST['enterT'])) {
							$classname = $_POST['classname'];
							$termname = $_POST['termname'];
							echo '<h4 style="margin:0px;padding:0px;">' . $classname . ' ' . ucwords($termname) . '</h4>';
						}

						?>
						<div class="headerC mt-2" style="">

							<!--<button class="btn btn-info" onclick="saveResultBtn();">save</button>-->
						</div>
						<div class="bodyC" style="overflow: scroll;">
							<table class="table w-100 table-condensed table-striped table-hover" id="table">
								<thead style="background: #fff !important;">
									<th style="background: #fff !important;">Student ID</th>
									<th style="background: #fff !important;" width="60%">Name</th>
								</thead>
								<tbody>
									<?php
									//echo $bid;
									if (isset($_POST['enterT'])) {
										$class_id = $_POST['class_id'];
										$term_id = $_POST['term'];



										$get_student_in_class = mysqli_query($conn, "SELECT * FROM student_classes as s INNER JOIN student_info as i on i.student_info_id = s.student_info_id WHERE s.class_id = '$class_id' AND s.session_id ='$current_session_id'") or die(mysqli_error($conn));

										$num_rows_all_class = mysqli_num_rows($get_student_in_class);


										if (!$get_student_in_class) {
											echo "<script>alert(" . mysqli_error($conn) . ");</script>";
										}
										if ($num_rows_all_class > 0) {
											$n = 1;
											//$studentIds = array();
											while ($row = $get_student_in_class->fetch_assoc()) {
												$stid = $row['student_info_id'];
												//$studentIds[] = $stid;
									?>
												<tr rel="<?php echo $stid; ?>">
													<td><?php echo $row['adm_no']; ?></td>
													<td><?php echo $row['first_name'] . ' ' . $row['other_name'] . ' ' . $row['last_name']; ?></td>
												</tr>
											<?php
											}
											?>
											<script>
												all_student_id = <?php echo json_encode($studentIds); ?>;
												
											</script>
									<?php


											// mysqli_error($conn);
										} else {
											//echo "string";
										}
									}
									?>
								</tbody>
							</table>

						</div>

					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-1"></div>
		<div class="col-sm-6 col-xs-12 col-md-6 col-lg-6 p-0" style="padding: 4px; min-width: 345px;">
			<div id="traits" style="width: 100%;">
				<button class="btn btn-info ml-2"  onclick="saveResultBtn();">save</button>
				<div style="margin: 10px 0px 0px 10px; border:1px solid #eee; box-shadow: 0px 1px 1px #ccc;">
					<select style=""></select>
				</div>

			</div>

		</div>
	</div>
	<div class="row w-100 d-none" id="commentPage">
		<?php
		$list = [];
		$class_id = "";
		$term_id = "";
		$comments = [];
		$savedComment = [];
		if (isset($_POST['enterT'])) {
			$com = $conn->query("SELECT * FROM `comment_template`");
			if ($com->num_rows > 0) {
				while ($row = $com->fetch_assoc()) {
					$comments[] = $row;
				}
			}
			$class_id = $_POST['class_id'];
			$term_id = $_POST['term'];
			$sql = "SELECT c.student_info_id, SUM(c.total) as total, SUM(c.total3) as total3,
			COUNT(CASE c.grade WHEN 'A' THEN 1 ELSE NULL END) AS A,
			COUNT(CASE c.grade WHEN 'B' THEN 1 ELSE NULL END) AS B,
			COUNT(CASE c.grade WHEN 'C' THEN 1 ELSE NULL END) AS C,
			COUNT(CASE c.grade WHEN 'D' THEN 1 ELSE NULL END) AS D,
			COUNT(CASE c.grade WHEN 'E' THEN 1 ELSE NULL END) AS E,
			COUNT(CASE c.grade WHEN 'F' THEN 1 ELSE NULL END) AS F,
			CONCAT(s.first_name, ' ', s.other_name, ' ', s.last_name ) AS fullname,
			s.adm_no AS adm_no,
			COUNT(subject_id) AS total_subject,
			AVG(total) as total_avg,
			AVG(total3) as total_avg3, cm.comment1, cm.comment2 
			FROM `contineous_accessment` AS c INNER JOIN student_info as s ON s.student_info_id= c.student_info_id 
			LEFT JOIN comments AS cm ON (cm.student_info_id = s.student_info_id AND cm.session_id ='8' AND cm.class_id='4' AND cm.term_id='1')
			WHERE c.session_id ='$current_session_id' AND c.class_id='$class_id' AND c.term_id='$term_id' GROUP BY c.student_info_id;
			";			
			$run = $conn->query($sql) or die(mysqli_error($conn));
			if ($run->num_rows > 0) {
				while ($row = $run->fetch_assoc()) {
					$list[] = $row;
				}
			}
			
			$run = $conn->query("SELECT * FROM `comments` WHERE session_id ='$current_session_id' AND class_id='$class_id' AND term_id='$term_id'");
			//echo "SELECT * FROM `comments` session_id ='$current_session_id' AND class_id='$class_id' AND term_id='$term_id'";
			if ($run->num_rows > 0) {
				while ($row = $run->fetch_assoc()) {
					$savedComment[] = $row;
				}
			}
		?>
			<div class="col-sm-12 col-md-12 col-lg-12" style="min-width: 400px;">
				<div class="d-flex">
					<h5 class="pt-2 mr-3">{{classname}} - {{termname}}</h5>
					<div id="btncomments">
						<button @click="savecomment" class="btn btn-info mr-3">Save</button>
					</div>
				</div>
				<div class="bodyC bxml" style="overflow: scroll;">
					<table class="w-100 table table-condensed table-striped table-hover" id="table">
						<thead style="background: #fff !important;">
							<th class="">Student ID</th>
							<th class="">Name</th>
							<th  class="">A</th>
							<th  class="">B</th>
							<th  class="">C</th>
							<th  class="">D</th>
							<th  class="">E</th>
							<th  class="">F</th>
							<th  class="">Total Score</th>
							<th  class="">Average</th>
							<th  class="">Position</th>
							<th class="">Form Teacher's Comment</th>
							<th  class="">VP's Comment</th>
						</thead>
						<tbody id="commentdatavalue">
							<tr v-for="(list,index) in lists">
								<td class="ptt-2">{{list.adm_no}}</td>
								<td  class="ptt-2">{{list.fullname}}</td>
								<td class="ptt-2">{{list.A}}</td>
								<td class="ptt-2">{{list.B}}</td>
								<td class="ptt-2">{{list.C}}</td>
								<td class="ptt-2">{{list.D}}</td>
								<td class="ptt-2">{{list.E}}</td>
								<td class="ptt-2">{{list.F}}</td>
								<td class="ptt-2" v-if="term_id == 3">{{list.total3}}</td>
								<td class="ptt-2" v-else>{{list.total}}</td>

								<td class="ptt-2" v-if="term_id == 3">{{list.total_avg3}}</td>
								<td class="ptt-2" v-else>{{list.total_avg}}</td>

								<td class="ptt-2" v-if="term_id == 3">{{giveRank(list.total3, 'total3')}}</td>
								<td class="ptt-2" v-else>{{ giveRank(list.total, 'total')}}</td>
								<td class="ptt-2">
									<input type="text" :value="list.student_info_id" class="d-none">
									<textarea  cols="2" :value="list.comment1" :id="'com_1_'+index" class="comment1 form-control" maxlength="280" style="width: 210px;"  >{{ list.comment1??""}}</textarea>
									<a data-toggle="modal" data-target="#exampleModalCenter" @click="setContainerId('com_1_'+index)" class="btn2 ">Insert Template...</a>
								</td>
								<td>
									<textarea  cols="2" :value="list.comment2" :id="'com_2_'+index" class="comment2 form-control" maxlength="280" style="width: 210px;" >{{list.comment2??""}}</textarea>
									<a data-toggle="modal" data-target="#exampleModalCenter" @click="setContainerId('com_2_'+index)" class="btn2 my-1 d-inline-block">Insert Template...</a>
								</td>
							</tr>
						</tbody>
					</table>

				</div>
				<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
					<div class="modal-dialog modal-dialog-centered" role="document">
						<div class="modal-content" style="box-shadow:1px 2px 20px #ccc;">
							<div class="modal-header">
								<h5 class="modal-title" id="exampleModalLongTitle">Pick Comments</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								<table class="table w-100 table-hover">
									<thead>
										<tr>
											<th>s/n</th>
											<th>Comment</th>
											<th>Comment Type</th>
										</tr>
									</thead>
									<tbody>
										<tr v-for="(comment, index) in comments" @click="insertAtCaret(comment.comment)" >
											<td>{{index+1}}</td>
											<td>{{comment.comment}}</td>
											<td>{{comment.comment_type}}</td>
										</tr>
									</tbody>
								</table>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
								<button type="button" class="btn btn-primary">Save changes</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php } ?>
		<input type="text" :value="forIframeLoaded" id="forIframeLoaded" style="display: none;">

	</div>

</div>

<div class="row" style="color:#067;margin:0px;">
	<div class="col-lg-12" id="accessment_sheet">
	</div>
</div>
<?php
if(isset($_POST['enterT'])){
	?>
	<script>

		$(document).ready(function() {

		$('.table').DataTable({
			responsive: true,
			dom: 'Bfrtip',
			buttons: [
				/* 'copy', 'excel', */
				'pdf'
			],
			pageLength: 250,

		});
		
		setTimeout(() => {
                $('.bxml .buttons-pdf').detach().appendTo('#btncomments');
                //$('#pdfPrint').appendTo($('buttons-pdf').html());	                            
            }, 1000);
		})
	</script>
	<?php
}
?>
<script type="text/javascript">
	$("#table tbody tr").click(function() {
		$(this).addClass('selected').siblings().removeClass('selected');
		//var value=$(this).find('td:first').html();
		var value = $(this).attr('rel');
		$.post('traits.php', {
			stid: value,
			tid: '<?php echo $term_id; ?>',
			sid: '<?php echo $current_session_id; ?>',
			bid: '<?php echo $class_id; ?>',
		}, function(data) {
			$('#traits').html(data);
		});
	});


	var vm = new Vue({
		el: "#commentPage",
		data: {			
			forIframeLoaded:200,
			lists: <?php echo json_encode($list); ?>,
			term_id: '<?php echo $term_id; ?>',
			class_id: '<?php echo $class_id; ?>',
			session_id: '<?php echo $current_session_id; ?>',
			comments: <?php echo json_encode($comments); ?>,
			savedComment: <?php echo json_encode($savedComment); ?>,
			containerId: "",
			classname: '<?= $classname;?>',
			termname: '<?= $termname;?>',
			commentData:[]
		},
		created() {
			var $this = this;
			this.lists.map((item)=>{
				for(var x in $this.savedComment){					
					if(item.student_info_id === $this.savedComment[x].student_info_id){
						item.comment1 = $this.savedComment[x].comment1;
						item.comment2 = $this.savedComment[x].comment2;
						break;
					}
				}
			});
		},
		methods: {
			getComment(id, type, container_id){
				let comment = this.savedComment.filter((item)=>{					
					return item.student_info_id === id;
				})				
				if(type == 1 && comment.length > 0){
					//console.log(comment[0].comment1);
					$('#'+container_id).val(comment[0].comment1)
					console.log($('#'+container_id));
					return comment[0].comment1;

				}

				if(type == 2 && comment.length > 0){
					return comment[0].comment2;
				}
				//return "";

			},
			setContainerId(id) {
				this.containerId = id;
				$('#exampleModalCenter').modal('show')
			},
			giveRank(value, type) {
				// declaring and initilising variables
				let rank = 1;
				prev_rank = rank;
				position = 0;
				var arrn = [];
				this.lists.forEach((item, i) => {
					arrn[i] = item[type];
				});

				function onlyUnique(value, index, self) {
					return self.indexOf(value) === index;
				}
				var unique = arrn.filter(onlyUnique);

				unique.sort(function(a, b) {
					return b - a
				});
				position = unique.indexOf(value);

				function ordinal_suffix_of(i) {
					i+=1;
					var j = i % 10,
						k = i % 100;
					if (j == 1 && k != 11) {
						return i + "st";
					}
					if (j == 2 && k != 12) {
						return i + "nd";
					}
					if (j == 3 && k != 13) {
						return i + "rd";
					}
					return i + "th";
				}
				return ordinal_suffix_of(position);
			},
			insertAtCaret(text) {								
				$('#' + this.containerId).val(text);
				$('#exampleModalCenter').modal('hide')
			},
			savecomment(){
				var $this = this;
				$this.commentData = []
				$('#commentdatavalue tr').each(function(){
					if($(this).find('.comment1').val() != '' || $(this).find('.comment2').val() != ''){
						$this.commentData.push({
							'id': $(this).find('input').val(),
							'comment1': $(this).find('.comment1').val(),
							'comment2': $(this).find('.comment2').val()
						});
					}
				});								
				//console.log($this.commentData);
				if(this.commentData.length >0){
					$.post("../php/save_comment.php", {                                                    
						comments : JSON.stringify(this.commentData),
                        type:'save',           
						session_id: this.session_id,
						term_id: this.term_id,
						class_id:this.class_id
                    }, (response)=> {
                        /* $('#successPay').addClass('d-none'); */                        
                        if (response == 200){                                                                       
							alert('saved successfully');
                        }
                    })
				}
			}

		},
		mounted() {
			console.log(this.savedComment)
		}

	})
	$(document).ready(function() {
		$('#pageType').change(function() {
			$('.row').removeClass('d-none');
			if ($(this).val() == 'trait') {
				$('#commentPage').addClass('d-none');
				$('#traitPage').removeClass('d-none');
			}
			if ($(this).val() == 'comment') {
				$('#commentPage').removeClass('d-none');
				$('#traitPage').addClass('d-none');
			}

		})
	});
</script>