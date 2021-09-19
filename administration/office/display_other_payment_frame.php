<?php session_start(); ?>
<?php
if (isset($_SESSION['staff_info_id'])) {
} else {
    header('location:../');
}
include_once('../php/connection.php');


$session_id = $_GET['session_id'];

$sql = "SELECT s.class_id, s.student_info_id, c.class_name, CONCAT(i.first_name,' ', i.other_name,' ', i.last_name) as fullname,i.adm_no, i.gender FROM student_classes as s INNER JOIN student_info as i ON i.student_info_id = s.student_info_id INNER JOIN classes as c ON c.class_id=s.class_id WHERE s.session_id = '$session_id' AND (s.status ='1' or s.status ='2')";
$run =  mysqli_query($conn, $sql) or die(mysqli_error($conn));
$students = [];
if ($run->num_rows > 0) {
    while ($row = $run->fetch_assoc()) {
        $students[] = $row;
    }
}
$sql = "SELECT DISTINCT year FROM misc_payments";
$run1 =  mysqli_query($conn, $sql) or die(mysqli_error($conn));
$years = [];
if ($run1->num_rows > 0) {
    while ($row = $run1->fetch_assoc()) {
        $years[] = $row;
    }
}

$sql = "SELECT *, SUBSTRING(payment_date, 1,4) as theyear FROM misc_payment_history WHERE SUBSTRING(payment_date, 1,4)='$year'";
$run1 =  mysqli_query($conn, $sql) or die(mysqli_error($conn));
$payment_history_detailed = [];
$track = -1;
if ($run1->num_rows > 0) {
    while ($row = $run1->fetch_assoc()) {
        if ($i == 0) {
            $payment_history_detailed[$row['misc_payments_id']] = $row;
        }
    }
}

//current payment
$sql1 = "SELECT * FROM `misc_payment_details`";
$run1 =  mysqli_query($conn, $sql1) or die(mysqli_error($conn));
$paymentTypes = [];
if ($run1->num_rows > 0) {
    while ($row = $run1->fetch_assoc()) {
        $paymentTypes[] = $row;
    }
}
$payment_history = [];
$payment_historyx = [];
?>
<!DOCTYPE html>
<html>

<head>
    <title> <?php echo $school_abbr; ?> </title>

    <link rel="shortcut icon" href="../../images/e_portal.png">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="../css/boostrap4.css">
    <link rel="stylesheet" href="../css/datatable.js">

    <link rel="stylesheet" href="../../css/bootstrap-theme.css">
    <link rel="stylesheet" href="../../css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="../../css/font-awesome-4.7.0/css/font-awesome.min.css">
    <script src="../js/jquery3.js"></script>
    <script src="../js/bootstrap4.js"></script>
    <script src="../js/propper.js"></script>
    <link rel="stylesheet" type="text/css" href="../js/datatable/datatables.min.css" />

    <script type="text/javascript" src="../js/datatable/pdfmake.min.js"></script>
    <script type="text/javascript" src="../js/datatable/datatables.min.js"></script>
    <script type="text/javascript" src="../js/datatable/vfs_fonts.js"></script>
    <script src="../js/vue.js"></script>



    <style>
        body {
            margin: 0;
            padding: 0;
            font-size: 1em;
            width: 100%;



        }

        td {
            cursor: pointer;
        }

        .no-visible {
            visibility: hidden;
        }

        .d-none {
            display: none;
        }

        .modal-backdrop {
            background-color: transparent;
        }
        /* xs */
.w-xs {
    width: 100%;
    height: auto;
}
.onMobile{
    min-height: 60px;
}
/* sm */
@media (min-width: 768px) {
    .w-xs {
        width: 100%;
    }
}
/* md */
@media (min-width: 992px) {
    .w-xs {
        width: 80%;
        margin:10px auto;
    }
}
/* lg */
@media (min-width: 1200px) {
    .w-xs {
        width: 75%;
    }
}
    </style>

</head>

<body style="background:#fff">
    <div id="app" class="no-visible">

        <center v-show="isloading == false">
            <button class="mt-2 active btn btn-sm btn-primary" @click="switchbtn($event, 1)">Make Payment</button>
            <button class=" mt-2 btn btn-sm btn-primary" @click="switchbtn($event, 2); openHistory()">view histroy</button>
        </center>
        <transition-group name="fade" mode="out-in">

            <div v-if="isloading==true" key="lox33" style="display:flex; justify-content:center; flex-wrap:wrap;align-items:center;width:100%;height:90%; position:absolute; left:0;z-index:132000; ">
                <div class="spinner-grow text-info" style="font-size: 7em;" role="status" key="loader">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
            <div class="row w-100 mt-2" v-if="mode==1" key="paymentmode" v-show="isloading==false">
                <div class="col-lg-12 col-md-12">
                    <table class="table table-bordered w-100" id="table01">
                        <thead>
                            <tr>
                                <th>sn.</th>
                                <th>Adm No.</th>
                                <th>Name</th>
                                <th>class</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(student,index) in students" :key="'stkl_'+index">
                                <td>{{index +1}}</td>
                                <td>{{student.adm_no}}</td>
                                <td>{{student.fullname}}</td>
                                <td>{{student.class_name}}</td>
                                <td><button class="m-0 btn btn-sm btn-warning text-white" @click="forModal(student)" data-toggle="modal" data-target="#exampleModalCenter">make payment</button></td>
                            </tr>
                        </tbody>
                    </table>

                </div>
            </div>
            <div class="row w-100 mt-2" v-if="mode==2" key="paymenthistorymode">

                <div class="col-lg-12 col-md-12" style="position: relative;">
                    <div class="w-xs" style="position: absolute;" v-show="isloading==false">
                        <button class="btn btn-light btn-sm w-25 py-2" style="float: right;">Search</button>
                        <select class="form-control mx-2" style="width: 25%; float:right" id="searchType">
                            <option value="">Select Type</option>
                            <option v-for="payment in paymentTypes" :value="payment.id">{{payment.abbr}} ({{payment.amount}})</option>
                        </select>
                        <select class="form-control" style="width: 25%;float:right" id="searchYear">
                            <option value="">Select Year</option>
                            <option v-for="year in years" :value="year">{{year.year}}</option>
                        </select>
                    </div>
                    <div class="onMobile"></div>
                    <table class="table table-bordered table-hover w-100 table2" id="table02" v-show="isloading==false">
                        <thead>
                            <tr>
                                <th width="10%">sn.</th>
                                <th>Adm No.</th>
                                <th>Payment Number.</th>
                                <th>Name</th>
                                <th>class</th>
                                <th>Type</th>
                                <th>Paid</th>
                                <th>Balance.</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody v-if="data_fetched">
                            <tr v-for="(student,index) in payment_history" :key="'stkl_'+index" @mouseleave="outTooltip">
                                <td width="6%">{{index +1}}</td>
                                <td @mouseenter="tooltip($event,student.pid, student.description)" width="6%">{{student.adm_no}}</td>
                                <td @mouseenter="tooltip($event,student.pid, student.description)" width="12%">{{student.payment_number}}</td>
                                <td @mouseenter="tooltip($event,student.pid, student.description)" :title="student.fullname">{{student.fullname}}</td>
                                <td @mouseenter="tooltip($event,student.pid, student.description)">{{student.class_name}}</td>
                                <td @mouseenter="tooltip($event,student.pid, student.description)">{{student.abbr}}</td>
                                <td @mouseenter="tooltip($event,student.pid, student.description)">{{student.amount_paid}}</td>
                                <td @mouseenter="tooltip($event,student.pid, student.description)">{{student.ballance}}</td>
                                <td class="actionbtnx" @mouseleave="outTooltip">

                                    <button class="m-0 btn btn-sm btn-info text-white" @click="forModal3(student,student.pid, student.description)" data-toggle="modal" data-target="#exampleModalCenter3">Receipt</button>
                                    <button v-if="student.ballance > 0" class="m-0 btn btn-sm btn-warning text-white" @click="forModal2(student, index)" data-toggle="modal" data-target="#exampleModalCenter2">Pay balance</button>
                                    <span v-else class="badge bg-success text-white p-1">paid</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                </div>
                <input type="text" :value="forIframeLoaded" class="d-none" id="forIframeLoaded" >
            </div>
        </transition-group>

        <!-- Modal -->
        <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog  modal-lg" role="document">
                <div class="modal-content" style="box-shadow:1px 2px 20px #ccc;">
                    <div class="modal-header pb-1">
                        <h5 class="modal-title" id="exampleModalLongTitle">
                            [{{selected.adm_no??""}}] {{selected.fullname??""}}
                            <span style="min-height: 30px;display:inline-block; width:3px;"></span>
                            <span class="d-none xloader">
                                <div class="spinner-grow text-info d-inline-block" style="font-size: 7em;" role="status" key="loader">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </span>

                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div :class="'alert '+alertType+ ' no-visible py-1 px-2 text-center w-100'" id="successPay">{{message}}</div>
                    <div class="modal-body pt-1">
                        <label><b>Paying For:</b></label>
                        <select class="form-control" @change="check_selected_typ" v-model="selectedTypeid">
                            <option value=""></option>
                            <option v-for="payment in paymentTypes" :value="payment.id" :data-type="payment.category">{{payment.abbr}} ({{payment.amount}})</option>
                        </select>
                        <div v-if="datatype">
                            <label><b>Description:</b></label>
                            <input type="text" class="form-control" v-model="description">
                        </div>
                        <label><b>Payment Type</b></label>
                        <select v-model="payment_typee" class="form-control">
                            <option value=1>Bank </option>
                            <option value=2>Cash </option>
                        </select>
                        <div v-if="payment_typee==1">
                            <label><b>Teller No:</b></label>
                            <input type="text" class="form-control" v-model="teller_no">
                        </div>
                        <div v-if="selectedTypeid !=''" class="mt-3">
                            <label><b>Amount:</b></label>
                            <input class="form-control" type="number" @keyup="validateAmount($event, selectedType.amount)" v-model="paidamount" value="selectedType.amount" :max="selectedType.amount??0" :min="0">
                            <label><b>Balance:</b></label>
                            <input class="form-control" type="number" disabled :value="(selectedType.amount - paidamount) > 0 ?selectedType.amount - paidamount : 0" :min="0">
                            <label><b>Payment made by:</b></label>
                            <input type="text" v-model="paidby" class="form-control">
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" @click="makePaymentNow()" class="btn btn-primary" v-if="selectedType !='' && paidamount>0 ">Pay</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="exampleModalCenter2" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle2" aria-hidden="true">
            <div class="modal-dialog  modal-lg" role="document">
                <div class="modal-content" style="box-shadow:1px 2px 20px #ccc;">
                    <div class="modal-header pb-1">
                        <h5 class="modal-title" id="exampleModalLongTitle2">
                            [{{selectedForBalance.adm_no??""}}] {{selectedForBalance.fullname??""}}
                            <span style="min-height: 30px;display:inline-block; width:3px;"></span>
                            <span class="d-none xloader">
                                <div class="spinner-grow text-info d-inline-block" style="font-size: 7em;" role="status" key="loader">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </span>

                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div :class="'alert '+alertType+ ' py-1 px-2 text-center w-100'" id="successPay">{{message}}</div>
                    <div class="modal-body pt-1">
                        <label><b>Paying Balance For:</b></label>
                        <select class="form-control" @change="check_selected_typ" v-model="selectedTypeid" disabled>
                            <option :value="selectedForBalance.id">{{selectedForBalance.abbr}} ({{Number(selectedForBalance.amount_paid) + Number(selectedForBalance.ballance)}})</option>
                        </select>
                        <div v-if="datatype">
                            <label><b>Description:</b></label>
                            <input type="text" class="form-control" v-model="description">
                        </div>
                        <label><b>Payment Type</b></label>
                        <select v-model="payment_typee" class="form-control">
                            <option value=1>Bank </option>
                            <option value=2 selected>Cash </option>
                        </select>

                        <div v-if="payment_typee==1">
                            <label><b>Teller No:</b></label>
                            <input type="text" class="form-control" v-model="teller_no">
                        </div>
                        <div class="mt-3">
                            <label><b>Amount:</b></label>
                            <input class="form-control" type="number" @keyup="validateAmount($event, selectedForBalance.ballance)" v-model="paidamount" :max="selectedForBalance.ballance??0" :min="0">
                            <label><b>Balance:</b></label>
                            <input class="form-control" type="number" disabled :value="(selectedForBalance.ballance - paidamount) > 0 ?selectedForBalance.ballance - paidamount : 0" :min="0">
                            <label><b>Payment made by:</b></label>
                            <input type="text" v-model="paidby" class="form-control">
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" @click="payBalanceNow()" class="btn btn-primary" v-if="selectedType !='' && paidamount>0 ">Pay</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="exampleModalCenter3" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle3" aria-hidden="true">
            <div class="modal-dialog  modal-lg" role="document">
                <div class="modal-content" style="box-shadow:1px 2px 20px #ccc;">
                    <div class="modal-header pb-1">
                        <h5 class="modal-title" id="exampleModalLongTitle3">
                            [{{selectedForBalance.adm_no??""}}] {{selectedForBalance.fullname??""}}
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body pt-1" id="print-receipt-2020">
                        <center>
                            <img :src="'../../images/'+logo" width="70px"><br>
                            <h1>{{schoolName.toUpperCase()}}</h1>
                            <h5>{{schoolAddress.toLocaleUpperCase()}}</h5>
                        </center>
                        <br>                        
                        <table style="width:100%;">
                            <tbody>
                                <tr>
                                    <td width="20%">Name:</td>
                                    <td colspan="3" width="80%">{{selectedForBalance.fullname??""}}</td>
                                </tr>
                                <tr>
                                    <td>Adm. No:</td>
                                    <td>{{selectedForBalance.adm_no??""}}</td>
                                    <td width="20%">Class:</td>
                                    <td>{{selectedForBalance.class_name??""}}</td>
                                </tr>
                            </tbody>
                        </table>
                        <p style="margin-bottom:1px;">{{selectedDescrip}}</p>
                        <table class="w-100 table-bordered">
                            <tr v-for="part in receiptHistory">
                                <td>{{part.payment_date}}</td>
                                <td>{{part.amount}}</td>
                            </tr>
                        </table>
                        <div><br><br>
                            <p>
                                Sign:________________________
                            </p>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" @click="printReceipt('print-receipt-2020')" class="btn btn-primary">Print</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {

            $('.table').DataTable({
                responsive: true,
                dom: 'Bfrtip',
                buttons: [
                    /* 'copy', 'excel', */
                    //'pdf'
                    {
                        extend:'pdf',
                        text:'pdf',
                        title: '<?= $school_name ?>',
                        messageTop:'Payment Details'
                    }
                ],
                pageLength: 6,

            });
            $('#iframeloaderx', parent.document).hide();
        })

        var vm = new Vue({
            el: "#app",
            data: {
                forIframeLoaded: 200,
                years: <?php echo json_encode($years); ?>,
                students: <?php echo json_encode($students); ?>,
                selected: "",
                logo: '<?php echo $school_image; ?>',
                schoolName: '<?php echo $school_name; ?>',
                schoolAddress: '<?php echo $school_address; ?>',
                selectedForBalance: [],
                paymentTypes: <?php echo json_encode($paymentTypes); ?>,
                payment_history: [],
                payment_historyx: <?php echo json_encode($payment_historyx); ?>,
                amount: -1,
                paidamount: 0,
                selectedTypeid: 0,
                selectedType: {},
                paidby: "",
                description: "",
                payment_typee: 1,
                teller_no: 0,
                payment_no: "",
                mode: 1,
                datatype: false,
                isloading: true,
                message: "Paying for ...",
                alertType: "alert-light",
                data_fetched: false,
                index_of_slected: '',
                receiptHistory: [],
                selectedDescrip: "",
            },
            methods: {
                printReceipt(divid) {                    
                    var mywindow = window.open('', 'PRINT', 'height=400,width=600');
                    
                    mywindow.document.write(`
                        <html><head><title>Reciept</title>
                        <link rel="stylesheet" href="../css/boostrap4.css">
                        </head><body >${document.getElementById(divid).innerHTML}
                        </body></html>
                    `);                                
                    mywindow.document.close(); // necessary for IE >= 10
                    mywindow.focus(); // necessary for IE >= 10*/

                    mywindow.print();
                    /* mywindow.close();
                    return true; */

                },
                openHistory() {
                    this.data_fetched = true;
                    var $this = this;
                    $.get("other _payment_data.php", (response) => {
                        /* $('#successPay').addClass('d-none'); */
                        response = JSON.parse(response);
                        this.payment_history = response[0];
                        this.payment_historyx = response[1];
                        var $this = this;
                        setTimeout(() => {
                            $this.isloading = false;
                            $('.table2').DataTable({
                                responsive: true,
                                destroy: true,
                                dom: 'Bfrtip',
                                buttons: [
                                    /* 'copy', 'excel', */
                                    'pdf'
                                ],
                                pageLength: 6,

                            });
                        }, 1000);
                    })
                },
                outTooltip() {
                    $('.tooltip-box124').remove();
                },
                tooltip(e, pid, descri) {
                    var x = e.x;
                    /* console.log(this.payment_historyx)
                    console.log(this.payment_history) */
                    /* match payment to get payment history break down in payment _historyx */
                    var payment_history = this.payment_historyx.filter((item) => {
                        return item.misc_payment_id == pid;
                    });
                    /* console.log(payment_history) */

                    if ($(e.target).hasClass('actionbtnx')) {} else {
                        if (e.x > 1050) {
                            x = 1000;
                        }
                        var tooltip = `
                    <div class="alert alert-white bg-white shadow-sm bordered tooltip-box124" style="width:250px;position:fixed; top:${e.y+5}px;left:${x+5}px; ">
                        <table class="w-100">
                            <tbody>`;

                        payment_history.forEach((item, index, arr) => {


                            tooltip += `<tr>
                                    <td width='7%'><b>Pay: </b></td>
                                    <td width='30%'><b>${item.amount}</b></td>
                                    <td width='60%' style='white-space: nowrap;'><b> on ${item.payment_date} </b></td>
                                    </tr>`;
                            if (index == arr.length - 1) {
                                tooltip += `
                                    <tr>
                                    <td width='7%' colspan="3">Description: ${descri}</td>
                                    </tr>
                                    `;

                            }
                        });

                        tooltip += `
                                
                                    </tbody>                
                                </table>
                            </div>`;
                        $('.tooltip-box124').remove();
                        $('body').append(tooltip);
                    }
                    ///alert();
                },
                switchbtn(e, n) {
                    var $this = this;
                    $('.btn').removeClass('active');
                    $(e.target).addClass('active');
                    this.mode = n;
                    $this.isloading = true;
                    if (n == 1) {

                        setTimeout(() => {
                            $this.isloading = false;
                            $('.table').DataTable({
                                responsive: true,
                                destroy: true,
                                dom: 'Bfrtip',
                                buttons: [
                                    /* 'copy', 'excel', */
                                    'pdf'
                                ],
                                pageLength: 6,

                            });
                        }, 1500);

                    }
                },
                check_selected_typ(e) {
                    var $this = this;
                    this.selectedType = this.paymentTypes.filter((item) => {
                        return item.id == $this.selectedTypeid
                    })[0];
                    $this.showMessage()
                    if ($(e.target).find('option:selected').attr('data-type') != 'exam') {
                        this.datatype = true;
                    }
                    $this.message = "Paying for " + this.selectedType.name;
                    $this.alertType = "alert-light";
                    this.paidamount = this.selectedType.amount
                },
                forModal(student) {
                    //$('#exampleModalLongTitle').text(student)
                    this.showMessage()
                    this.selected = student;

                },
                forModal2(student, index) {
                    //$('#exampleModalLongTitle').text(student)
                    this.index_of_slected = index
                    this.selectedForBalance = student;
                    this.paidamount = student.ballance;
                    this.message = student.name;
                    this.payment_typee = 2;
                    this.selectedTypeid = student.pid;
                    var $this = this;
                    setTimeout(() => {
                        $this.showMessage()
                    }, 500);

                },
                forModal3(student, pid, descri) {
                    //$('#exampleModalLongTitle').text(student)                    
                    this.selectedForBalance = student;
                    this.selectedDescrip = descri;
                    this.receiptHistory = this.payment_historyx.filter((item) => {
                        return item.misc_payment_id == pid;
                    });
                    setTimeout(() => {                        
                        this.printReceipt('print-receipt-2020');
                    }, 1500);
                },

                validateAmount(event, total) {
                    /* console.log(total);
                    console.log(event.target.value); */
                    if (Number(event.target.value) > Number(total)) {
                        alert('value too big');
                        event.target.value = total;
                    }

                },
                makePaymentNow() {
                    var bal = this.selectedType.amount - this.paidamount;
                    //this.payment_no = 'OPT'+rand(1, 10);
                    var $this = this;
                    if (this.payment_typee == 1 && this.teller_no == 0) {
                        alert('please enter teller number');
                        return 0;
                    }

                    $('.xloader').removeClass('d-none');
                    $.post("../php/make_other_payment.php", {
                        teller_no: this.teller_no,
                        paidamount: this.paidamount,
                        balance: bal,
                        student_info_id: this.selected.student_info_id,
                        paidby: this.paidby,
                        payment_type: this.payment_typee,
                        paidfor: this.selectedTypeid,
                        class_id: this.selected.class_id,
                        type: this.selectedType.category,
                        description: this.description,
                        whichPayment: 'make'
                    }, function(response) {
                        /* $('#successPay').addClass('d-none'); */

                        if (response == 201) {
                            $this.message = "teller number already exist";
                            $this.alertType = "alert-danger";
                        } else if (response == 207) {
                            $this.message = "sorry you cannot make double Payment on this payment type";
                            $this.alertType = "alert-info";
                        } else {
                            $this.message = "Payment make successfullly";
                            $this.alertType = "alert-success";
                        }
                        setTimeout(() => {
                            $('.xloader').addClass('d-none');
                            $this.showMessage()
                        }, 1000);
                    })
                },
                payBalanceNow() {
                    var bal = Number(this.selectedForBalance.ballance) - Number(this.paidamount);
                    //this.payment_no = 'OPT'+rand(1, 10);
                    var $this = this;
                    if (this.payment_typee == 1 && this.teller_no == 0) {
                        this.message = 'please enter teller number';
                        this.alertType = 'alert-warning';
                        return 0;
                    }

                    $('.xloader').removeClass('d-none');
                    $.post("../php/make_other_payment.php", {
                        teller_no: this.teller_no,
                        paidamount: this.paidamount,
                        expectedamount: $this.selectedForBalance.expected_amount,
                        balance: bal,
                        student_info_id: this.selectedForBalance.student_info_id,
                        paidby: this.paidby,
                        payment_type: this.payment_typee,
                        paidfor: this.selectedForBalance.pid,
                        class_id: this.selectedForBalance.class_id,
                        type: this.selectedForBalance.category,
                        description: this.description,

                        whichPayment: 'balance'
                    }, function(response) {
                        /* $('#successPay').addClass('d-none'); */

                        if (response == 201) {
                            $this.message = "teller number already exist";
                            $this.alertType = "alert-danger";
                        } else if (response == 207) {
                            $this.message = "sorry you cannot make double Payment on this payment type";
                            $this.alertType = "alert-info";
                        } else {
                            $this.message = "Payment make successfullly";
                            $this.alertType = "alert-success";
                        }
                        setTimeout(() => {
                            $this.payment_history[$this.index_of_slected].amount_paid = Number($this.payment_history[$this.index_of_slected].amount_paid) + Number($this.paidamount);
                            $this.payment_history[$this.index_of_slected].ballance = bal;
                            $('.xloader').addClass('d-none');
                            $this.showMessage()
                        }, 1000);
                    })
                },
                showMessage() {
                    $('#successPay').removeClass('no-visible');
                }
            },
            created() {
                //console.log(this.paymentTypes)          
            },
            mounted() {
                var $this = this;
                $('#app').removeClass('no-visible');
                $(document).ready(function() {
                    setTimeout(() => {

                    }, 500);
                    $this.isloading = false;
                })
            }
        })
    </script>

</body>

</html>