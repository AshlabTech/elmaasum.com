<?php

	$servername = "localhost";
	/* $username = "elmaasu1_user";
	$password = "_DU=yAUqdfuV";
	 */
	
	$username = "root";
	$password = "";

	$db = "elmaasu1_db";

	//create login connection and login
	$conn =  mysqli_connect($servername,$username,$password,$db) or die(mysqli_error($conn));

	$Fschool = mysqli_query($conn,"SELECT * FROM school LIMIT 1") or die(mysqli_error($conn));
	$school = mysqli_fetch_array($Fschool);
	$school_name = $school['name'];
	$school_address = $school['address'];
	$school_abbr = $school['abbr'] ;
	
?>
