<?php
session_start();
include('configs/config.php');
include('configs/checklogin.php');
include('configs/codeGen.php');
check_login();

if (isset($_POST['update_payroll'])) {

    $update = $_GET['update'];
    $payroll_code = $_POST['payroll_code'];
    $payroll_month = $_POST['payroll_month'];
    $payroll_salary = $_POST['payroll_salary'];

    $query = "UPDATE  payrolls SET payroll_code =?, payroll_month =?, payroll_salary =? WHERE payroll_id =?";
    $stmt = $mysqli->prepare($query);
    $rc = $stmt->bind_param('ssss', $payroll_code, $payroll_month, $payroll_salary, $update);
    $stmt->execute();
    if ($stmt) {
        $success = "Payroll Updated" && header("refresh:1; url=manage_payrolls.php");
    } else {
        //inject alert that task failed
        $info = "Please Try Again Or Try Later";
    }
}


require_once('partials/_head.php');
?>

<body>

    <!--  BEGIN NAVBAR  -->
    <?php
    require_once('partials/_nav.php');
    ?>
    <!--  END NAVBAR  -->

    <!--  BEGIN NAVBAR  -->
    <div class="sub-header-container">
        <header class="header navbar navbar-expand-sm">
            <a href="javascript:void(0);" class="sidebarCollapse" data-placement="bottom"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-menu">
                    <line x1="3" y1="12" x2="21" y2="12"></line>
                    <line x1="3" y1="6" x2="21" y2="6"></line>
                    <line x1="3" y1="18" x2="21" y2="18"></line>
                </svg></a>

            <ul class="navbar-nav flex-row">
                <li>
                    <div class="page-header">

                        <nav class="breadcrumb-one" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="manage_payrolls.php">Payrolls</a></li>
                                <li class="breadcrumb-item"><a href="manage_payrolls.php">Manage Payrolls</a></li>
                                <li class="breadcrumb-item active" aria-current="page"><span>Update Payroll</span></li>
                            </ol>
                        </nav>

                    </div>
                </li>
            </ul>
        </header>
    </div>
    <!--  END NAVBAR  -->

    <!--  BEGIN MAIN CONTAINER  -->
    <div class="main-container" id="container">

        <div class="overlay"></div>
        <div class="search-overlay"></div>

        <!--  BEGIN SIDEBAR  -->
        <?php require_once('partials/_sidebar.php');
        $update = $_GET['update'];
        $ret = "SELECT * FROM `payrolls` WHERE payroll_id ='$update' ";
        $stmt = $mysqli->prepare($ret);
        $stmt->execute(); //ok
        $res = $stmt->get_result();
        while ($row = $res->fetch_object()) {
        ?>
            <!--  BEGIN CONTENT AREA  -->
            <div id="content" class="main-content">
                <div class="layout-px-spacing">

                    <div class="row layout-top-spacing">

                        <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
                            <div class="widget-content widget-content-area br-6">
                                <form method="POST" enctype="multipart/form-data">
                                    <div class="form-row mb-4">
                                        <div class="form-group col-md-6">
                                            <label for="inputEmail4">Full Name</label>
                                            <input required type="text" value="<?php echo $row->doc_name; ?>" readonly name="doc_name" class="form-control">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="inputEmail4">Number</label>
                                            <input required type="text" value="<?php echo $row->doc_number; ?>" readonly name="doc_number" class="form-control">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="inputEmail4">Email Address</label>
                                            <input required type="text" value="<?php echo $row->doc_email; ?>" readonly name="doc_email" class="form-control">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="inputEmail4">Payroll Code</label>
                                            <input type="text" name="payroll_code" value="<?php echo $a; ?>-<?php echo $b; ?>" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-row mb-4">
                                        <div class="form-group col-md-6">
                                            <label for="inputEmail4">Month</label>
                                            <select class='form-control basic' name="payroll_month" id="">
                                                <option selected><?php echo $row->payroll_month; ?></option>
                                                <option>January</option>
                                                <option>February</option>
                                                <option>March</option>
                                                <option>April</option>
                                                <option>May</option>
                                                <option>June</option>
                                                <option>July</option>
                                                <option>August</option>
                                                <option>September</option>
                                                <option>Octomber</option>
                                                <option>November</option>
                                                <option>December</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="inputEmail4">Salary</label>
                                            <input required type="text" value="<?php echo $row->payroll_salary; ?>" name="payroll_salary" class="form-control">
                                        </div>
                                    </div>
                                    <button type="submit" name="update_payroll" class="btn btn-primary mt-3">Update Payroll</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
            require_once('partials/_footer.php');
        }
            ?>
            </div>
            <!--  END CONTENT AREA  -->
    </div>
    <!-- END MAIN CONTAINER -->

    <?php
    require_once('partials/_scripts.php');
    ?>
</body>

</html>