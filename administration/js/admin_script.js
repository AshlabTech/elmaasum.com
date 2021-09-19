var DataTablestate = false;
$(document).ready(function () {
  /*
        $('.table').DataTable({
            saveState:true,
        });*/
  $("#login_btn").on("click", function () {
    login();
  });

  $(".panel-heading").on("click", function () {
    //	alert();
  });

  $("#add_new_staff_btn").on("click", function () {
    load_add_new_staff_form();
  });

  $("#add_new_student_btn").on("click", function () {
    load_add_new_student_form();
  });
  $("#upload_new_student_btn").on("click", function () {
    load_upload_new_student_form();
  });

  $(".myOp_staff").on("click", function () {
    var tab_name = $(this).attr("name");
    window.location.assign("index.php?staff=" + tab_name);
  });

  $(".myOp_students").on("click", function () {
    var tab_name = $(this).attr("name");
    window.location.assign("index.php?students=" + tab_name);
  });

  $(".page_nav_btnn").on("click", function () {
    var page_nav = $(this).attr("name");
    window.location.assign("index.php?" + page_nav + "=info");
  });

  $(".option_btn").on("click", function () {
    var name = $(this).attr("name");

    $(".option_btn").removeClass("active");
    $(this).addClass("active");
  });
});

function empty(el) {
  if (el == "") return true;
}

function getId(el) {
  return document.getElementById(el);
}

function login() {
  var admin_login_id = getId("admin_id_input").value;
  var admin_password = getId("admin_pass_input").value;

  if (empty(admin_login_id)) {
    getId("login_status").innerHTML =
      '<span style="color:red"> Provide your login ID </span>';
  } else if (empty(admin_password)) {
    getId("login_status").innerHTML =
      '<span style="color:red"> Provide your login password </span>';
  } else {
    getId("login_status").innerHTML =
      '<center><p style="color:blue"><img src="../images/ajax-loader.gif"></center><p>';
    $.post(
      "php/admin_login.php",
      { admin_login_id: admin_login_id, admin_password: admin_password },
      function (response, error) {
        if (response == 1) {
          setTimeout(function () {
            window.location.assign("office/");
          }, 3000);
        } else {
          getId("login_status").innerHTML = response;
        }
      }
    );
  }
}

function load_add_new_staff_form() {
  $.post("load_add_new_staff_form.php", function (response, error) {
    getId("display_content").innerHTML = response;
  });
}

function load_staff_dashboard() {
  getId("display_content").innerHTML =
    '<img src="../../images/ajax-loader.gif">';
  $.post("load_staff_dashboard.php", function (response, error) {
    getId("display_content").innerHTML = response;
  });
}
function load_staff_result_code() {
  getId("display_content").innerHTML =
    '<img src="../../images/ajax-loader.gif">';
  $.post("load_staff_result_code.php", function (response, error) {
    getId("display_content").innerHTML = response;
  });
}

function attendance() {
  getId("display_content").innerHTML =
    '<img src="../../images/ajax-loader.gif">';
  $.post("attendance.php", function (response, error) {
    getId("display_content").innerHTML = response;
  });
}

function load_add_new_student_form() {
  getId("display_content").innerHTML =
    '<img src="../../images/ajax-loader.gif">';
  $.post("load_add_new_student_form.php", function (response, error) {
    getId("display_content").innerHTML = response;
  });
}

function load_upload_new_student_form() {
  getId("display_content").innerHTML =
    '<img src="../../images/ajax-loader.gif">';
  $.post("load_upload_new_student_form.php", function (response, error) {
    getId("display_content").innerHTML = response;
  });
}

function load_all_staff(pn, sn) {
  $.post("all_staff.php", { pn: pn, sn: sn }, function (response, error) {
    getId("display_content").innerHTML = response;
  });
}
function load_system_users(pn, sn) {
  getId("system_users").innerHTML =
    '<h4 style="color:red">Loading,please wait...</h4>';
  $.post("system_users.php", { pn: pn, sn: sn }, function (response, error) {
    getId("system_users").innerHTML = response;
  });
}

function load_lga(state) {
  $.post("../php/load_lga.php", { state: state }, function (response) {
    document.getElementById("lga").innerHTML = "";
    var lga_array = "";
    lga_array = response.split("||");
    var sn = 0;
    for (var option in lga_array) {
      if (sn < lga_array.length - 2) {
        var pair = lga_array[option].split("|");
        var newoption = document.createElement("option");
        newoption.value = pair[0];
        newoption.innerHTML = pair[1];
        document.getElementById("lga").options.add(newoption);
      }

      sn++;
    }
  });
}

function move_student_passport_to_folder() {
  var file = document.getElementById("student_passport").files[0];
  var formdata = new FormData();
  formdata.append("student_passport", file);
  var ajax = new XMLHttpRequest();
  ajax.open("POST", "../php/move_student_passport_to_folder.php");
  ajax.addEventListener("load", complete_move_passport, false);
  ajax.send(formdata);
  alert();
}
function complete_move_passport(event) {
  alert(event.target.responseText);
}

function load_all_student_inclass(token, pn) {
  selected = "";
  DataTablestate = false;
  getId("display_content").innerHTML =
    '<div class="text-center" style="height:300px;line-height:300px"><img src="../../images/ajax-loader.gif"></div>';

  $.post(
    "../office/load_all_student_inclass.php",
    { token: token, pn: pn },
    function (response, error) {
      getId("display_content").innerHTML = response;
    }
  );
}

function load_promote_student_by_session_class() {
  selected = "";
  DataTablestate = false;

  var p_session = getId("p_session").value;
  var class_id = getId("p_class").value;

  alert(class_id);
  getId("promotion_wrap").innerHTML =
    '<div class="text-center" style="height:300px;line-height:300px"><img src="../../images/ajax-loader.gif"></div>';

  $.post(
    "../office/load_promote_student_by_session_class.php",
    { p_session: p_session, p_class: class_id },
    function (response, error) {
      getId("promotion_wrap").innerHTML = response;
    }
  );
}

function delete_student(token, token2, pn) {
  var con = confirm("are you sure you want to delete this student??");

  if (con == true) {
    getId("display_content").innerHTML =
      '<img src="../../images/ajax-loader.gif">';
    $.post(
      "../php/delete_student.php",
      { token: token },
      function (response, error) {
        alert(response);
        setTimeout(function () {
          load_all_student_inclass(token2, pn);
        }, 1000);
      }
    );
  } else {
    setTimeout(function () {
      load_all_student_inclass(token2, pn);
    }, 1000);
  }
}

function load_all_student() {
  getId("display_content").innerHTML =
    '<div class="text-center" style="height:300px;line-height:300px"><img src="../../images/ajax-loader.gif"></div>';

  $.post("../php/all_students.php", function (response, error) {
    getId("display_content").innerHTML = response;
  });
}

function load_student_info_data(token) {
  getId("display_content").innerHTML =
    '<div class="text-center" style="height:300px;line-height:300px"><img src="../../images/ajax-loader.gif"></div>';

  $.post(
    "../office/load_student_info_data.php",
    { token: token },
    function (response, error) {
      setTimeout(function () {
        getId("display_content").innerHTML = response;
      }, 1000);
    }
  );
}

function laod_manage_payment_details() {
  getId("display_content").innerHTML =
    '<div class="text-center" style="height:300px;line-height:300px"><img src="../../images/ajax-loader.gif"></div>';

  $.post(
    "../office/laod_manage_payment_details.php",
    function (response, error) {
      getId("display_content").innerHTML = response;
    }
  );
}

function load_set_staff_access() {
  getId("display_content").innerHTML =
    '<div class="text-center" style="height:300px;line-height:300px"><img src="../../images/ajax-loader.gif"></div>';

  $.post("load_set_staff_access.php", function (response, error) {
    getId("display_content").innerHTML = response;
  });
}

function add_payment_details() {
  var section = getId("section").value;
  var payment_description = getId("payment_description").value;
  var payment_amount = getId("payment_amount").value;
  var gender = getId("gender").value;
  var category = getId("category").value;

  if (empty(getId("payment_description").value)) {
    $("#payment_description").focus();
  } else if (empty(getId("payment_amount").value)) {
    $("#payment_amount").focus();
  } else if (empty(getId("gender").value)) {
    $("#gender").focus();
  } else {
    getId("add_payment_output").innerHTML =
      '<div class="text-center" style="height:300px;line-height:300px"><img src="../../images/ajax-loader.gif"></div>';

    $.post(
      "../php/add_payment_details.php",
      {
        section: section,
        payment_description: payment_description,
        category: category,
        payment_amount: payment_amount,
        gender: gender,
      },
      function (response, error) {
        if (response == 1) {
          getId("add_payment_output").innerHTML =
            '<label for="" style="color:green"> Operation is successful</label><br>	<button type="submit" class="btn btn-info" onclick="add_payment_details()"> Add Payment	</button>';
          getId("payment_description").value = "";
          getId("payment_amount").value = "";
          getId("gender").value = "";
          load_section_payment(section);
        } else {
          getId("add_payment_output").innerHTML = response;
        }
      }
    );
  }
}

function load_update_payment_details(token) {
  getId("payment_details_wrap").innerHTML =
    '<div class="text-center" style="height:300px;line-height:300px"><img src="../../images/ajax-loader.gif"></div>';

  $.post(
    "../office/load_update_payment_details.php",
    { token: token },
    function (response, error) {
      getId("payment_details_wrap").innerHTML = response;
      window.scrollTo(0, 0);
    }
  );
}

function update_payment_details(token, token2) {
  var section = getId("section").value;
  var payment_description = getId("payment_description").value;
  var payment_amount = getId("payment_amount").value;
  var gender = getId("gender").value;
  var category = getId("category").value;

  if (empty(getId("payment_description").value)) {
    $("#payment_description").focus();
  } else if (empty(getId("payment_amount").value)) {
    $("#payment_amount").focus();
  } else if (empty(getId("category").value)) {
    $("#category").focus();
  } else {
    getId("add_payment_output").innerHTML =
      '<div class="text-center" style="height:300px;line-height:300px"><img src="../../images/ajax-loader.gif"></div>';

    $.post(
      "../php/update_payment_details.php",
      {
        token: token,
        token2: token2,
        section: section,
        payment_description: payment_description,
        payment_amount: payment_amount,
        gender: gender,
        category: category,
      },
      function (response, error) {
        getId("payment_description").value = "";
        getId("payment_amount").value = "";
        getId("gender").value = "";
        window.scrollTo(0, 0);
        load_section_payment(token2);

        getId("add_payment_output").innerHTML = response;
      }
    );
  }
}

function delete_payment_details(token, token2) {
  var comfirm_action = confirm("are you sure you want to delete ?");

  if (comfirm_action == true) {
    getId("payment_details_display").innerHTML =
      '<div class="text-center" style="height:300px;line-height:300px"><img src="../../images/ajax-loader.gif"></div>';

    $.post(
      "../php/delete_payment_details.php",
      { token: token },
      function (response, error) {
        alert(response);
        laod_manage_payment_details();
        load_section_payment(token2);
      }
    );
  }
}

function load_section_payment(token) {
  getId("payment_details_display").innerHTML =
    '<div class="text-center" style="height:300px;line-height:300px"><img src="../../images/ajax-loader.gif"></div>';

  $.post(
    "../php/load_section_payment.php",
    { token: token },
    function (response, error) {
      getId("payment_details_display").innerHTML = response;
    }
  );
}

function load_view_staff_details(token) {
  getId("display_content").innerHTML =
    '<div class="text-center" style="height:300px;line-height:300px"><img src="../../images/ajax-loader.gif"></div>';

  $.post(
    "../office/load_view_staff_details.php",
    { token: token },
    function (response, error) {
      getId("display_content").innerHTML = response;
    }
  );
}

function load_change_student_pics(token) {
  $.post(
    "../office/load_change_student_pics.php",
    { token: token },
    function (response, error) {
      pageOVerlay();
      getId("alertBoxContent").innerHTML = response;
    }
  );
}

function load_all_subject(pn, sn) {
  getId("all_sub").innerHTML =
    '<h4 style="color:red">loading,please wait...</h4>';
  $.post(
    "../office/all_subjects.php",
    { pn: pn, sn: sn },
    function (response, error) {
      getId("all_sub").innerHTML = response;
    }
  );
}

function load_sub_to_assign(pn, sn) {
  getId("load_subjects_to_assign").innerHTML =
    '<h4 style="color:red">loading,please wait...</h4>';
  $.post(
    "../office/load_subjects_to_assign.php",
    { pn: pn, sn: sn },
    function (response, error) {
      window.scrollTo(0, 1000);
      getId("load_subjects_to_assign").innerHTML = response;
    }
  );
}

var std_token;
function change_student_pics(token) {
  std_token = token;
  var file = document.getElementById("studentpassport").files[0];
  var formdata = new FormData();
  formdata.append("studentpassport", file);
  var ajax = new XMLHttpRequest();
  ajax.open("POST", "../php/change_student_pics.php");
  ajax.addEventListener("load", change_student_pics_complete, false);
  ajax.send(formdata);
}
function change_student_pics_complete(event) {
  $("#current_user_pic_change").removeAttr("src");
  $("#current_user_pic_change").attr(
    "src",
    "../students_image_upload/student_passport" + std_token + ".png"
  );
  alert(event.target.responseText);
}

function close_change_student_pics(token) {
  myAlert.close();
  load_student_info_data(token);
}

function load_change_staff_pics(token) {
  $.post(
    "../office/load_change_staff_pics.php",
    { token: token },
    function (response, error) {
      pageOVerlay();
      getId("alertBoxContent").innerHTML = response;
    }
  );
}

var staff_token;
function change_staff_pics(token) {
  staff_token = token;
  var file = document.getElementById("staffpassport").files[0];
  var formdata = new FormData();
  formdata.append("staffpassport", file);
  var ajax = new XMLHttpRequest();
  ajax.open("POST", "../php/change_staff_pics.php");
  ajax.addEventListener("load", change_staff_pics_complete, false);
  ajax.send(formdata);
}
function change_staff_pics_complete(event) {
  if (event.target.responseText != "2") {
    $("#current_user_pic_change").removeAttr("src");
    $("#current_user_pic_change").attr(
      "src",
      "../staff_image_uploads/default.jpg"
    );
    alert(event.target.responseText);
  } else {
    $("#current_user_pic_change").removeAttr("src");
    $("#current_user_pic_change").attr("src", event.target.responseText);
  }
}

function close_change_staff_pics(token) {
  myAlert.close();

  setTimeout(function () {
    load_view_staff_details(staff_token);
  }, 1000);
}

function pageOVerlay() {
  var overlay = document.getElementById("pageOverlay");
  var winH = window.innerHeight;
  overlay.style.display = "block";
  overlay.style.height = winH + "px";
  myAlert.show();
}

function myAlertBox() {
  this.show = function () {
    document.getElementById("alertBox").style.display = "block";
  };

  this.close = function () {
    document.getElementById("pageOverlay").style.display = "none";
    document.getElementById("alertBox").style.display = "none";
  };
}

var myAlert = new myAlertBox();

function load_edit_student_form(token) {
  getId("display_content").innerHTML =
    '<img src="../../images/ajax-loader.gif">';
  $.post(
    "load_edit_student_form.php",
    { token: token },
    function (response, error) {
      getId("display_content").innerHTML = response;
    }
  );
}

function mark_attendance(token, month_id, day_in_month) {
  var class_id = getId("select_class").value;
  var session_id = getId("current_session").value;
  var year = getId("select_yr").value;
  $.post(
    "../php/mark_attendance.php",
    {
      token: token,
      year: year,
      class_id: class_id,
      session_id: session_id,
      month_id: month_id,
      day_in_month: day_in_month,
    },
    function (response, error) {
      //alert(response);
    }
  );
}

function load_attendance_sheet(class_id) {
  window.location.assign("attendance.php?token=" + class_id + "");
  /*getId('attendance_sheet_wrap').innerHTML = '<img src="../../images/ajax-loader.png">';
			$.post("attendance_sheet.php",{class_id:class_id},function(response,error){
				getId('attendance_sheet_wrap').innerHTML = response;
			});*/
}

function abdul_android_pageOverlay() {
  var overlay = document.getElementById("abdul_android_pageOverlay");
  var winH = window.innerHeight;
  overlay.style.display = "block";
  overlay.style.height = winH + "px";
  setTimeout(function () {
    myandroidAlert.show();
  }, 50);
}

function myandroidAlertBox() {
  this.show = function () {
    $("#abdul_android_alertBox").show(1000);

    //document.getElementById("abdul_android_boxContent").innerHTML = 'loading,please  wait.....';
  };

  this.close = function () {
    $("#abdul_android_pageOverlay").hide(2000);
    $("#abdul_android_alertBox").hide(2000);
  };
}

var myandroidAlert = new myandroidAlertBox();

function attendance_toExcel(cl, m) {
  window.location = "?cl=" + cl + "&excel_m=" + m;
}

function make_payment_search(class_id) {
  getId("make_payement_student_search_wrap").innerHTML =
    "<h4 style='text-align:center'><img src='../../images/ajax-loader.gif'></h4>";
  $.post(
    "make_payement_student_search.php",
    { class_id: class_id },
    function (response) {
      getId("make_payement_student_search_wrap").innerHTML = response;
    }
  );
}

function edit_payment_btn(cl, e) {
  $(e.target).hide();
  $(e.target).next().show();
  //var elt = $('.'+cl);
  $(e.target)
    .parent()
    .parent()
    .parent()
    .find("." + cl)
    .hide();
  $(e.target).parent().parent().find("td input").removeClass("d-none");
}
function amount_ch(e, total) {
  var elt = $(e.target);
  var amt = elt.val();
  elt.css("border", "1px solid #ccc");
  if (amt > total) {
    alert("value cannot be greater that total amount");
    e.target.value = 0;
    elt.css("border", "1px solid red");
    elt.parent().next().find("input").val(total);
  } else {
    var bal = total - amt;
    elt.parent().next().find("input").val(bal);
  }
}
function load_student_payment_details(token) {
  getId("make_payement_student_search_wrap").innerHTML =
    "<h4 style='text-align:center'><img src='../../images/ajax-loader.gif'></h4>";
  $.post(
    "load_student_payment_details.php",
    { token: token },
    function (response) {
      getId("make_payement_student_search_wrap").innerHTML = response;
    }
  );
}

function load_payment_form(token) {
  getId("make_payement_student_search_wrap").innerHTML =
    "<h4 style='text-align:center'><img src='../../images/ajax-loader.gif'></h4>";
  $.post("payment_form.php", { token: token }, function (response) {
    getId("make_payement_student_search_wrap").innerHTML = response;
  });
}

function payment_gender(token) {
  if (token == 1) {
    $("#payment_gn").fadeOut("slow");
  } else {
    $("#payment_gn").fadeIn("slow");
  }
}

function display_classes_payment() {
  var session_id = $("#current_session").val();
  var term_id = $("#current_term").val();

  getId("make_payement_student_search_wrap").innerHTML =
    "<h4 style='text-align:center'><img src='../../images/ajax-loader.gif'></h4>";
  $.post(
    "display_classes_payment.php",
    { session_id: session_id, term_id: term_id },
    function (response) {
      getId("make_payement_student_search_wrap").innerHTML = response;
    }
  );
}
function display_other_payments() {
  var session_id = $("#current_session").val();
  var term_id = $("#current_term").val();

  getId("make_payement_student_search_wrap").innerHTML =
    "<h4 style='text-align:center'><img src='../../images/ajax-loader.gif'></h4>";
  $.post(
    "display_other_payments.php",
    { session_id: session_id, term_id: term_id },
    function (response) {
      getId("make_payement_student_search_wrap").innerHTML = response;
      setTimeout(() => {
        var interval = setInterval(() => {						
          if($('#iframeotherx').contents().find('#forIframeLoaded').val() == 200){
            $('#iframeloaderx').hide();
            clearInterval(interval);
          }
          
        }, 50);
        }, 1000);
    }
  );
}
function reset_student_password() {
  Swal.fire({
    title: `Enter Student No. without '/' or type 'reset all'`,
    html: `<input id="swal-input1" class="swal2-input" placeholder="EXAMPLE021002">`,
    showCancelButton: true,
    confirmButtonText: "Reset",
    showLoaderOnConfirm: true,
    preConfirm: (login) => {
      var myRegEx = /[^a-zA-Z\s\d]/i;
      let val = document.getElementById("swal-input1").value;
      var isValid = !myRegEx.test(val);

      if (val == "") {
        Swal.showValidationMessage(`Please enter a valid input`);
        return 0;
      }
      if (!isValid) {
        Swal.showValidationMessage(`Please enter a valid input`);
        return 0;
      }
	  var res ="";
      $.post(`reset_password_.php?`, { data: val }, function (response) {
        if(response!=200){
			Swal.showValidationMessage(`Please enter a valid input`);
			return 0;
		}else{
			Swal.showValidationMessage(`changed successfully`);

		}		
	}).catch((error) => {
		Swal.showValidationMessage(`Request failed: ${error}`);
	});
	return res;
    },
    allowOutsideClick: () => !Swal.isLoading(),
  }).then((result) => {
      Swal.fire({
        icon: "success",
        title: `Proccessed`,
      });
  });
}

function updateStudentPayment(id, token, e) {
  var con = confirm("Are you sure you want to Update the payment?");
  var elt = $(e.target);
  var amt = elt.parent().parent().find(".amtc").val();
  //var bal = elt.parent().next().find('input').val();
  var bal = elt.parent().parent().find(".balc").val();
  var paidby = elt.parent().parent().find(".payer").val();
  if (con) {
    getId("make_payement_student_search_wrap").innerHTML =
      "<h4 style='text-align:center'><img src='../../images/ajax-loader.gif'></h4>";
    $.post(
      "updateStudentPayment.php",
      { id: id, amt: amt, bal: bal, paidby: paidby },
      function (response) {
        alert(response);
        load_student_payment_details(token);
      }
    );
  }
}

function deleteStudentPayment(id, token) {
  var con = confirm("Are you sure you want to delete the payment?");

  if (con) {
    getId("make_payement_student_search_wrap").innerHTML =
      "<h4 style='text-align:center'><img src='../../images/ajax-loader.gif'></h4>";
    $.post("deleteStudentPayment.php", { id: id }, function (response) {
      alert(response);
      load_student_payment_details(token);
    });
  }
}
function display_deptors_list_by_classes() {
  var session_id = $("#current_session").val();
  var term_id = $("#current_term").val();

  getId("make_payement_student_search_wrap").innerHTML =
    "<h4 style='text-align:center'><img src='../../images/ajax-loader.gif'></h4>";
  $.post(
    "display_deptors_list_by_classes.php",
    { session_id: session_id, term_id: term_id },
    function (response) {
      getId("make_payement_student_search_wrap").innerHTML = response;
    }
  );
}

function ClickheretoprintDiv(div_id) {
  var disp_setting = "toolbar=yes,location=no,directories=yes,menubar=yes,";
  disp_setting += "scrollbars=yes,width=800, height=400, left=100, top=25";
  var content_vlue = document.getElementById(div_id).innerHTML;

  var docprint = window.open("", "", disp_setting);
  docprint.document.write(
    '<html><head><title>.::ELMAASUM</title> <link rel="stylesheet" href="http://elmaasum.com/css/bootstrap.css">'
  );
  docprint.document.write(
    '</head><body onLoad="self.print()" style="width: 900px; height="auto" font-size:16px; font-family:arial;">'
  );
  docprint.document.write(content_vlue);
  docprint.document.write("</body></html>");
  docprint.document.close();
  docprint.focus();
}

function display_term_payment_summary() {
  var session_id = $("#current_session").val();
  var term_id = $("#current_term").val();

  getId("make_payement_student_search_wrap").innerHTML =
    "<h4 style='text-align:center'><img src='../../images/ajax-loader.gif'></h4>";
  $.post(
    "display_term_payment_summary.php",
    { session_id: session_id, term_id: term_id },
    function (response) {
      getId("make_payement_student_search_wrap").innerHTML = response;
    }
  );
}

function display_session_payment_summary() {
  var session_id = $("#current_session").val();
  var term_id = $("#current_term").val();

  getId("make_payement_student_search_wrap").innerHTML =
    "<h4 style='text-align:center'><img src='../../images/ajax-loader.gif'></h4>";
  $.post(
    "display_session_payment_summary.php",
    { session_id: session_id, term_id: term_id },
    function (response) {
      getId("make_payement_student_search_wrap").innerHTML = response;
    }
  );
}
function view_all_paid_inclass(token, session_id, term_id) {
  getId("make_payement_student_search_wrap").innerHTML =
    "<h4 style='text-align:center'><img src='../../images/ajax-loader.gif'></h4>";
  $.post(
    "view_all_paid_inclass.php",
    { token: token, session_id: session_id, term_id: term_id },
    function (response) {
      getId("make_payement_student_search_wrap").innerHTML = response;
    }
  );
}

function score_entry_frame() {
  document.getElementById("display_content").innerHTML =
    '<center><img src="../../images/ajax-loader.gif"><br> please, wait...</center>';
  $.post(
    "score_time_frame_container.php",
    { token: "token" },
    function (response, error) {
      getId("display_content").innerHTML = response;
    }
  );
}
function laod_manage_form_teacher() {
  
  document.getElementById("display_content").innerHTML =
    '<center><img src="../../images/ajax-loader.gif"><br> please, wait...</center>';
  $.post(
    "../staff/pages/laod_manage_form_teacher.php",
    { token: "token" },
    function (response, error) {
      getId("display_content").innerHTML = response;
      setTimeout(() => {
        var interval = setInterval(() => {						
          if($('#form_teacher_iframe').contents().find('#forIframeLoaded').val() == 200){
            $('#iframeLoader').hide();
            clearInterval(interval);
          }
          
        }, 50);
        }, 1000);
    }
  );
}
function load_config() {
  getId("display_content").innerHTML =
    '<div class="text-center" style="height:300px;line-height:300px"><img src="../../images/ajax-loader.gif"></div>';
  $.post("load_config.php", function (response) {
    getId("display_content").innerHTML = response;
  });
}
var cm1 = 0;
selected1 = "";
selected = "";
var cm = 0;
var selected = "";
$("#tSortable22").dataTable();
$(".tSortable221").dataTable();
//start checkbox
function chkAll(source, name) {
  selArray = "";
  chkname = document.getElementsByClassName(name);
  for (var i = 0; i < chkname.length; i++) {
    //alert(source.checked);
    if (source % 2 == 1) {
      chkname[i].checked = true;
      selArray += chkname[i].value + ",";
    } else {
      chkname[i].checked = false;
      selArray = "";
    }
  }
  return selArray;
}
var Selected = [];
var subSelected = [];
function arrayRemove(arr, value) {
  return arr.filter(function (ele) {
    return ele != value;
  });
}
var classE, buttons, i;
function selchecked(id, idchk, cl, clchk) {
  /*$(clchk).not(this).prop('checked', this.checked);   */

  buttons = document.getElementsByClassName(cl);

  if ($(idchk).is(":checked")) {
    //$('.'+cl).attr("checked", "true");
    for (i = 0; i < buttons.length; i++) {
      if (buttons[i].checked == false) {
        buttons[i].checked = true;
        //buttons[i].click();
      }
    }
    Selected = $("." + cl)
      .map((_, el) => el.value)
      .get();
    $(".ActionBtn").attr("disabled", false);
  } else {
    for (i = 0; i < buttons.length; i++) {
      if (buttons[i].checked == true) {
        //buttons[i].click();
        buttons[i].checked = false;
      }
    }
    Selected = [];
    $(".ActionBtn").attr("disabled", true);
  }
  console.log(Selected);
}
function checkUpdate(id) {
  if ($(id + ":checkbox").is(":checked")) {
    Selected.push($(id).val());
  } else {
    Selected.splice(Selected.indexOf($(id).val()), 1);
  }
  console.log(Selected);
  if (Selected.length != 0) {
    $(".ActionBtn").attr("disabled", false);
  } else {
    $(".ActionBtn").attr("disabled", true);
  }
}

function selChk(s, p) {
  var chkAll = document.getElementById("chkA1").checked;
  var id = s.val();
  console.log(p);
  var selct = "";
  if (chkAll == true) {
    if (s.prop("checked") == true) {
      p += id + ",";
      //alert(p);
      return p;
    } else if (s.prop("checked") == false) {
      if (p.length != "") {
        var selV = p.split(",");
      } else {
        var selV;
      }
      for (var i = 0; i < selV.length - 1; i++) {
        var len = id.length;
        var index = selV.indexOf(id);
        if (id == selV[i]) {
          selV.splice(index, 1);
        }
      }
      p = "";
      for (var i = 0; i < selV.length - 1; i++) {
        p += selV[i] + ",";
      }
      alert(p);
      return p;
    }
  } else if (chkAll == false) {
    if (s.prop("checked") == true) {
      p += id + ",";
      //alert(p);
      return p;
    } else if (s.prop("checked") == false) {
      if (p.length != "") {
        var selV = p.split(",");
      } else {
        var selV;
      }
      for (var i = 0; i < selV.length - 1; i++) {
        var len = id.length;
        var index = selV.indexOf(id);
        if (id == selV[i]) {
          selV.splice(index, 1);
        }
      }
      p = "";
      for (var i = 0; i < selV.length - 1; i++) {
        p += selV[i] + ",";
      }
      //alert(p);
      return p;
    }
  }
}
function selChk1(s, p) {
  var chkAll = document.getElementById("chkA").checked;
  var id = s.val();
  console.log();
  //alert();
  if (chkAll == true) {
    if (s.prop("checked") == true) {
      p += id + ",";
      //alert(p);
      return p;
    } else if (s.prop("checked") == false) {
      if (p.length != "") {
        var selV = p.split(",");
      } else {
        var selV;
      }
      for (var i = 0; i < selV.length - 1; i++) {
        var len = id.length;
        var index = selV.indexOf(id);
        if (id == selV[i]) {
          selV.splice(index, 1);
        }
      }
      p = "";
      for (var i = 0; i < selV.length - 1; i++) {
        p += selV[i] + ",";
      }
      //alert(p);
      console.log(p);
      return p;
    }
  } else if (chkAll == false) {
    if (s.prop("checked") == true) {
      p += id + ",";
      //alert(p);
      return p;
    } else if (s.prop("checked") == false) {
      if (p.length != "") {
        var selV = p.split(",");
      } else {
        var selV;
      }
      for (var i = 0; i < selV.length - 1; i++) {
        var len = id.length;
        var index = selV.indexOf(id);
        if (id == selV[i]) {
          selV.splice(index, 1);
        }
      }
      p = "";
      for (var i = 0; i < selV.length - 1; i++) {
        p += selV[i] + ",";
      }
      //alert(p);
      return p;
    }
  }
}
//end checkbox
//------------------Delete selected student from class
$("#rmvM").click(function () {
  if (selected != "") {
    //alert(selected);
    Swal.fire({
      title: "Do you want to continue to remove student",
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Yes",
    }).then((result) => {
      if (result.value) {
        //------send data to accept result-------------------------
        $.get(
          "inajx.php",
          { selected: selected, type: "remveM" },
          function (data) {
            $("#load1").hide();
          }
        );
        $("#load1").show();
      }
    });
  } else {
    Swal.fire("no student is selected");
  }
});
//------------------End Delete selected student from class
function transM(class_sel, selected1, termId, classId, sessionId) {
  //console.log(sessionId);
  //$('#transM').click(function(){
  if (selected1 != "") {
    $("#iframe").attr(
      "src",
      "ques/transferbox.php?branch=" +
        class_sel +
        "&sel=" +
        selected1 +
        "&t=" +
        termId +
        "&b=" +
        classId +
        "&s=" +
        sessionId
    );
    $("#iframeCont").fadeToggle(600);
  } else {
    Swal.fire("no student is selected");
  }
  //});
}
//-------the import function
function importD(sel, b, t, s) {
  //console.log(b+'-t'+t+'-s'+s);
  Selected = [];
  if (sel != null) {
    $.get(
      "ques/imports.php",
      { type: "imports", sel: sel, b: b, t: t, s: s },
      function (data) {
        $("#load1").hide();
        console.log(data);
        if (data == "successfully added") {
          $("#ImOut12").html(data);
          $("#ImOut12").show();
          setTimeout(function () {
            $("#ImOut12").hide();
            $("#addModal").modal("hide");
            load_all_student_inclass(b, t);
          }, 4000);
        } else {
          Swal.fire({
            title: data,
            text: "You can remove them from class and add",
          }); /*
					$('#ImOut2').html(data);
					$('#ImOut2').show();
					setTimeout(function(){
						$('#addModal').modal('hide');
						$('#ImOut2').hide();
						load_all_student_inclass(b,t);
				},6000);*/
        }
      }
    );
    $("#load1").show();
  } else {
    Swal.fire("no student selected");
  }
}
function addM() {
  Selected = [];
  if (DataTablestate == false) {
    $(".table").DataTable({ saveState: true });
  }
  DataTablestate = true;
}
