<?php 
include_once('connection.php');
$comments =(object)json_decode($_POST['comments']);
$session_id = $_POST['session_id'];
$term_id = $_POST['term_id'];
$class_id=$_POST['class_id'];
foreach ($comments as $key => $comment) {    
    $run = $conn->query("SELECT * FROM `comments` WHERE student_info_id='$comment->id' AND session_id= '$session_id' AND term_id='$term_id' AND class_id='$class_id'") or die (mysqli_error($conn));
    if($run->num_rows <1){        
        $run = $conn->query("INSERT INTO `comments`(`id`, `student_info_id`, `comment1`, `comment2`, `session_id`, `term_id`, `class_id`)
         VALUES (null,'$comment->id','$comment->comment1','$comment->comment2','$session_id','$term_id','$class_id')") or die (mysqli_error($conn));
    }else{
        $rw = $run->fetch_assoc();
        $id = $rw['id'];
        $run = $conn->query("UPDATE `comments` SET `comment1`='$comment->comment1',`comment2`='$comment->comment2' WHERE id='$id'") or die (mysqli_error($conn));
    }

}
echo 200;
?>
			