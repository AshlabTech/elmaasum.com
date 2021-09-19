<?php
include '../php/connection.php';
$stid = $_POST['stid'];
$sid = $_POST['sid'];
$bid = $_POST['bid'];
$tid = $_POST['tid'];

?>

<div id="load11" class="loading">
	<div style="position: absolute;z-index: 12; top: 45%; left: 20%;">
		<img src="../../../images/ajax-loader.gif"><br> please, wait...
	</div>
</div>
<button class="btn w-100 btn-light text-dark mb-2" onclick="saveResultBtn();">save</button>
<div style="">
	<select style="" class="form-control" id="selectTraits">
		<option value="affective_domain">affective Domain</option>
		<option value="psycomotor">Psycomotor</option>
	</select>

	<div id="affective" style="padding: 0px;">
		<table class="table table-striped">
			<thead>
				<tr>
					<th width="30%">Traits</th>
					<th width="70%">Rating</th>
				</tr>
			</thead>
			<tbody>



				<?php
				$fetch_a = $conn->query("SELECT * FROM affective_domain");
				if ($fetch_a->num_rows > 0) {
					$mm = 1;
					while ($f_a = $fetch_a->fetch_assoc()) {
						$is = 0;
						$aid = $f_a['id'];
						$f_a['a_name'];
						$chk_traits1 = $conn->query("SELECT * FROM student_a_traits as s  WHERE s.affective_domain_id= '$aid' AND s.student_id='$stid' AND s.term_id='$tid' AND s.session_id='$sid'");
						//echo mysqli_error($conn);
						if (mysqli_num_rows($chk_traits1) > 0) {
							$r1 = $chk_traits1->fetch_assoc();
							$is = $r1['rate'];
				?>
							<tr class="bd">
								<td width="30%" class="co">
									<?php echo $f_a['a_name'] ?>
								</td>
								<td>
									<input type="radio" id="a<?php echo $aid; ?>" class='rad' name="<?php echo $f_a['a_name'] ?>" value="<?php echo $aid . ';1'; ?>">1
									<input type="radio" id="b<?php echo $aid; ?>" class='rad' name="<?php echo $f_a['a_name'] ?>" value="<?php echo $aid . ';2'; ?>">2
									<input type="radio" id="c<?php echo $aid; ?>" class='rad' name="<?php echo $f_a['a_name'] ?>" value="<?php echo $aid . ';3'; ?>">3
									<input type="radio" id="d<?php echo $aid; ?>" class='rad' name="<?php echo $f_a['a_name'] ?>" value="<?php echo $aid . ';4'; ?>">4
									<input type="radio" id="e<?php echo $aid; ?>" class='rad' name="<?php echo $f_a['a_name'] ?>" value="<?php echo $aid . ';5'; ?>">5
								</td>
							</tr>
							<script>
								$(document).ready(function() {
									var chk_r = <?php echo $is; ?>;
									//alert(chk_r);
									var a = $('#a<?php echo $aid; ?>');
									var b = $('#b<?php echo $aid; ?>');
									var c = $('#c<?php echo $aid; ?>');
									var d = $('#d<?php echo $aid; ?>');
									var e = $('#e<?php echo $aid; ?>');

									if (chk_r == 1) {
										a.prop('checked', true);
									} else if (chk_r == 2) {
										b.prop('checked', true);
									} else if (chk_r == 3) {

										c.prop('checked', true);
									} else if (chk_r == 4) {
										d.prop('checked', true);
									} else if (chk_r == 5) {
										e.prop('checked', true);
									}

								});
							</script>

						<?php
						} else {
						?>
							<!-- <div class="row bd">
						<div class="col-sm-4 col-xs-4 co"></div>
						<div class="col-sm-6 col-xs-6 " style="padding: 8px; width: 170px;">
						</div> -->

							<tr>
								<td>
									<?php echo $f_a['a_name'] ?>
								</td>
								<td>
									<input type="radio" id="a<?php echo $aid; ?>" class='rad' name="<?php echo $f_a['a_name'] ?>" value="<?php echo $aid . ';1'; ?>">1
									<input type="radio" id="b<?php echo $aid; ?>" class='rad' name="<?php echo $f_a['a_name'] ?>" value="<?php echo $aid . ';2'; ?>">2
									<input type="radio" id="c<?php echo $aid; ?>" class='rad' name="<?php echo $f_a['a_name'] ?>" value="<?php echo $aid . ';3'; ?>">3
									<input type="radio" id="d<?php echo $aid; ?>" class='rad' name="<?php echo $f_a['a_name'] ?>" value="<?php echo $aid . ';4'; ?>">4
									<input type="radio" id="e<?php echo $aid; ?>" class='rad' name="<?php echo $f_a['a_name'] ?>" value="<?php echo $aid . ';5'; ?>">5
								</td>
							</tr>

					<?php
						}
						$mm++;
					}
					?>
			</tbody>
		</table>
	<?php
				}
	?>
	<!-- </div> -->
	<div><?php
			$desc = $conn->query("SELECT * FROM traits_description");
			$rf = $desc->fetch_assoc();
			?>
		<hr>
		<p style="margin: 10px; text-align: center;"><strong>Rating Description</strong></p>
		<p><span>1. </span> <?php echo $rf['1'] ?></p>
		<p><span>2. </span> <?php echo $rf['2'] ?></p>
		<p><span>3. </span> <?php echo $rf['3'] ?></p>
		<p><span>4. </span> <?php echo $rf['4'] ?></p>
		<p><span>5. </span> <?php echo $rf['5'] ?></p>
		<?php
		?>
	</div>
	</div>
	<!--Psycomotor section-->

	<div id="psycomotor" style="padding: 10px; display: none;">
		<table class="table table-striped">
			<thead>
				<tr>
					<th width="30%" class="trt">Traits</th>
					<th width="70%" class="trt">Rating</th>
				</tr>
			</thead>
			<tbody>

				<?php
				$fetch_a = $conn->query("SELECT * FROM psycomotor");
				if ($fetch_a->num_rows > 0) {
					while ($f_a = $fetch_a->fetch_assoc()) {
						$is = 0;
						$aid = $f_a['id'];
						$f_a['p_name'];
						$chk_traits1 = $conn->query("SELECT * FROM student_p_traits as s  WHERE s.psycomotor_id= '$aid' AND s.student_id='$stid' AND s.term_id='$tid' AND s.session_id='$sid'");
						//echo mysqli_error($conn);
						if ($chk_traits1->num_rows > 0) {
							$r1 = $chk_traits1->fetch_assoc();
							$is = $r1['rate'];
				?>
							<tr class="bd">
								<td class="co"><?php echo $f_a['p_name'] ?></td>
								<td class="col-sm-7 col-xs-7 " style="padding: 8px; width: 170px;">

									<input type="radio" id="f<?php echo $aid; ?>" class='rad1' name="<?php echo $f_a['p_name'] ?>" value="<?php echo $aid . ';1'; ?>">1
									<input type="radio" id="g<?php echo $aid; ?>" class='rad1' name="<?php echo $f_a['p_name'] ?>" value="<?php echo $aid . ';2'; ?>">2
									<input type="radio" id="h<?php echo $aid; ?>" class='rad1' name="<?php echo $f_a['p_name'] ?>" value="<?php echo $aid . ';3'; ?>">3
									<input type="radio" id="i<?php echo $aid; ?>" class='rad1' name="<?php echo $f_a['p_name'] ?>" value="<?php echo $aid . ';4'; ?>">4
									<input type="radio" id="j<?php echo $aid; ?>" class='rad1' name="<?php echo $f_a['p_name'] ?>" value="<?php echo $aid . ';5'; ?>">5
								</td>
								<script>
									$(document).ready(function() {
										var chk_r = <?php echo $is; ?>;
										//alert(chk_r);
										var a = $('#f<?php echo $aid; ?>');
										var b = $('#g<?php echo $aid; ?>');
										var c = $('#h<?php echo $aid; ?>');
										var d = $('#i<?php echo $aid; ?>');
										var e = $('#j<?php echo $aid; ?>');

										if (chk_r == 1) {
											a.prop('checked', true);
										} else if (chk_r == 2) {
											b.prop('checked', true);
										} else if (chk_r == 3) {

											c.prop('checked', true);
										} else if (chk_r == 4) {
											d.prop('checked', true);
										} else if (chk_r == 5) {
											e.prop('checked', true);
										}

									});
								</script>
							</tr>
						<?php
						} else {
						?>
							<tr class=" bd">
								<td class="col-sm-4 col-xs-4 co"><?php echo $f_a['p_name'] ?></td>
								<td class="col-sm-6 col-xs-6 " style="padding: 10px; width: 180px;">
									<input type="radio" id="a<?php echo $aid; ?>" class='rad1' name="<?php echo $f_a['p_name'] ?>" value="<?php echo $aid . ';1'; ?>">1
									<input type="radio" id="b<?php echo $aid; ?>" class='rad1' name="<?php echo $f_a['p_name'] ?>" value="<?php echo $aid . ';2'; ?>">2
									<input type="radio" id="c<?php echo $aid; ?>" class='rad1' name="<?php echo $f_a['p_name'] ?>" value="<?php echo $aid . ';3'; ?>">3
									<input type="radio" id="d<?php echo $aid; ?>" class='rad1' name="<?php echo $f_a['p_name'] ?>" value="<?php echo $aid . ';4'; ?>">4
									<input type="radio" id="e<?php echo $aid; ?>" class='rad1' name="<?php echo $f_a['p_name'] ?>" value="<?php echo $aid . ';5'; ?>">5
								</td>
							</tr>
				<?php
						}
					}
				}
				?>
			</tbody>
		</table>
		<div><?php
				$desc = $conn->query("SELECT * FROM traits_description");
				$rf = $desc->fetch_assoc();
				?>
			<hr>
			<p style="margin: 10px; text-align: center;"><strong>Rating Description</strong></p>
			<p><span>1. </span> <?php echo $rf['1'] ?></p>
			<p><span>2. </span> <?php echo $rf['2'] ?></p>
			<p><span>3. </span> <?php echo $rf['3'] ?></p>
			<p><span>4. </span> <?php echo $rf['4'] ?></p>
			<p><span>5. </span> <?php echo $rf['5'] ?></p>
			<?php
			?>
		</div>
	</div>
	<script type="text/javascript" src="jquery-1.10.2.js"></script>
	<script>
		$('#selectTraits').change(function() {

			var pick = $(this).val();
			if (pick == 'affective_domain') {
				$('#psycomotor').hide(100);
				$('#affective').show(100);
			} else {
				$('#affective').hide(100);
				$('#psycomotor').show(100);
			}
		});

		function saveResultBtn() {
			var type = $('#selectTraits').val();
			if (type == 'affective_domain') {
				var AllTraits = document.getElementsByClassName('rad');
				var selVal = '';
				//alert(AllTraits.length);
				for (var i = 0; i < AllTraits.length; i++) { //*-*
					AllTraits[i].value;
					if (AllTraits[i].checked == true) {
						selVal += AllTraits[i].value;
					}
					if (i % 5 == 4) {
						selVal += ',';
					}
				}
				$('#load11').show();
				$.post('save-traits.php', {
					type: 'affective',
					selVal: selVal,
					stid: <?php echo $stid; ?>,
					sid: <?php echo $sid ?>,
					tid: <?php echo $tid ?>,
					bid: <?php echo $bid ?>
				}, function(data) {
					$('#load11').hide();
					if (data == 1) {
						$('#errMsg').show();
						$('#errMsg').html('<p class="alert alert-success" style="padding:3px; width:100%;"> Saved </p>');
						setTimeout(function() {
							$('#errMsg').hide();
						}, 4000);
					} else {
						$('#errMsg').show();
						$('#errMsg').html('<p class="alert alert-danger style="padding:3px;"> Error connecting. Try again </p>');
						setTimeout(function() {
							$('#errMsg').hide();
						}, 4000);
					}
				});
				$('#<?php echo $school_abbr; ?>').show();
			} else {
				var AllTraits = document.getElementsByClassName('rad1');
				var selVal = '';
				for (var i = 0; i < AllTraits.length; i++) { //*-*
					AllTraits[i].value;
					if (AllTraits[i].checked == true) {
						selVal += AllTraits[i].value;
					}
					if (i % 5 == 4) {
						selVal += ',';
					}
				}
				$.post('save-traits.php', {
					type: 'psycomotor',
					selVal: selVal,
					stid: <?php echo $stid ?>,
					sid: <?php echo $sid; ?>,
					tid: <?php echo $tid; ?>,
					bid: <?php echo $bid; ?>
				}, function(data) {
					$('#load11').hide();
					if (data == 1) {
						$('#errMsg').show();
						$('#errMsg').html('<p class="alert alert-success" style="padding:3px; width:100%;"> Saved </p>');
						setTimeout(function() {
							$('#errMsg').hide();
						}, 4000);
					} else {
						$('#errMsg').show();
						$('#errMsg').html('<p class="alert alert-danger style="padding:3px;"> Error connecting. Try again </p>');
						setTimeout(function() {
							$('#errMsg').hide();
						}, 4000);
					}
				});
				$('#load11').show();
			}
			//alert(selVal);
		}
	</script>