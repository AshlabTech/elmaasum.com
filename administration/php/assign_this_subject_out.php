<?php 

	include_once('connection.php');
	$staff_info_id = $_POST['token'];
	$subject_id = $_POST['sub'];
	$session_id = $_POST['sessionID'];
	$term_id = $_POST['termID'];
	$class_id = $_POST['class_id'];

	$sub_section = "select * from subject where id = '$subject_id' and status ='1' LIMIT 1";
		$sub_section_run =  mysqli_query($conn,$sub_section) or die(mysqli_error($conn));
		$num_rows = mysqli_num_rows($sub_section_run);
			if($num_rows > 0){
					$subject_array = mysqli_fetch_array($sub_section_run);
						$subject_id = $subject_array['id'];
						$subject = $subject_array['subject'];
						$subject_code = $subject_array['subject_code'];
						$section_id = $subject_array['school_section'];
					
			}
			
		//check if the staff is already assigned before
		
		/*$sub_check = "select * from staff_subjects where subject_id = ".$subject_id." and staff_info_id = ".$staff_info_id." and session_id = ".$session_id;*/
		$sub_check_run =  mysqli_query($conn,"SELECT * FROM staff_subjects WHERE subject_id='$subject_id' AND staff_info_id = '$staff_info_id' AND session_id = '$session_id' AND class_id = '$class_id' AND term_id='$term_id'") or die(mysqli_error($conn));
		$num_rows_sub_check= mysqli_num_rows($sub_check_run);
		/*
			$sub_check2 = "select * from staff_subjects where subject_id = '$subject_id'  and status ='1'";
			$sub_check_run2 =  mysqli_query($conn,$sub_check2) or die(mysqli_error($conn));
			$num_rows_sub_check2= mysqli_num_rows($sub_check_run2);*/
			
			if($num_rows_sub_check > 0){
				$sub_check_run_row = mysqli_fetch_array($sub_check_run);
					$status = $sub_check_run_row['status'];
					$id = $sub_check_run_row['id'];
						if($status == '0'){
							$sql_update = "UPDATE staff_subjects SET status = '1' where id = '$id' and status = '0'";
							$query_update = mysqli_query($conn,$sql_update) or die(mysqli_error($conn));
									echo 'Assigned Successfully...';
						}else{
							echo 'Already assigned to this staff before';
						}
					
			}
			else if($num_rows_sub_check2 > 0){
					echo 'Already assigned to another staff before';
			}
			else{
				$assign_query = mysqli_query($conn,"insert into staff_subjects (staff_info_id,subject_id,section_id,session_id,term_id,class_id) values ('$staff_info_id','$subject_id','$section_id', '$session_id', '$term_id','$class_id')") or die(mysqli_error($conn));				
					if($assign_query){
						echo 'Assigned Successfully...';
					}else{
						echo 'The Operation Failed..';
					}
			}
?>