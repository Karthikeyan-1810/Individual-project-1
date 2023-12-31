<?php
session_start();
require_once('configs/config.php');
require_once('configs/checklogin.php');

if (isset($_POST['change_password'])) {

    //Change Password
    $error = 0;
    if (isset($_POST['old_password']) && !empty($_POST['old_password'])) {
        $old_password = mysqli_real_escape_string($mysqli, trim(sha1(md5($_POST['old_password']))));
    } else {
        $error = 1;
        $err = "Old Password Cannot Be Empty";
    }
    if (isset($_POST['new_password']) && !empty($_POST['new_password'])) {
        $new_password = mysqli_real_escape_string($mysqli, trim(sha1(md5($_POST['new_password']))));
    } else {
        $error = 1;
        $err = "New Password Cannot Be Empty";
    }
    if (isset($_POST['confirm_password']) && !empty($_POST['confirm_password'])) {
        $confirm_password = mysqli_real_escape_string($mysqli, trim(sha1(md5($_POST['confirm_password']))));
    } else {
        $error = 1;
        $err = "Confirmation Password Cannot Be Empty";
    }

    if (!$error) {
        $member_id = $_SESSION['member_id'];
        $sql = "SELECT * FROM  members  WHERE member_id = '$member_id'";
        $res = mysqli_query($mysqli, $sql);
        if (mysqli_num_rows($res) > 0) {
            $row = mysqli_fetch_assoc($res);
            if ($old_password != $row['member_password']) {
                $err =  "Please Enter Correct Old Password";
            } elseif ($new_password != $confirm_password) {
                $err = "Confirmation Password Does Not Match";
            } else {
                $new_password  = sha1(md5($_POST['new_password']));
                $query = "UPDATE members SET  member_password =? WHERE member_id =?";
                $stmt = $mysqli->prepare($query);
                $rc = $stmt->bind_param('ss', $new_password, $member_id);
                $stmt->execute();

                if ($stmt) {
                    $success = "Password Changed" && header("refresh:1; url=user_profile.php");
                } else {
                    $err = "Please Try Again Or Try Later";
                }
            }
        }
    }
}
require_once('partials/_head.php');
?>

<body>

    <!--  BEGIN NAVBAR  -->
    <?php require_once('partials/_nav.php'); ?>
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
                                <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                                <li class="breadcrumb-item active" aria-current="page"><span>Profile</span></li>
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
        <?php
        require_once('partials/_sidebar.php');
        $member_id = $_SESSION['member_id'];
        $ret = "SELECT * FROM `members`  WHERE member_id = '$member_id' ";
        $stmt = $mysqli->prepare($ret);
        $stmt->execute(); //ok
        $res = $stmt->get_result();
        while ($row = $res->fetch_object()) {
        ?>
            <!--  END SIDEBAR  -->

            <!--  BEGIN CONTENT AREA  -->
            <div id="content" class="main-content">
                <div class="layout-px-spacing">

                    <div class="row layout-spacing">

                        <!-- Content -->
                        <div class="col-xl-4 col-lg-6 col-md-5 col-sm-12 layout-top-spacing">

                            <div class="user-profile layout-spacing">
                                <div class="widget-content widget-content-area">
                                    <div class="d-flex justify-content-between">
                                        <h3 class=""><?php echo $row->member_name; ?> Profile</h3>
                                        <a href="update_client.php?update=<?php echo $row->member_id; ?>" class="mt-2 edit-profile"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-3">
                                                <path d="M12 20h9"></path>
                                                <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
                                            </svg></a>
                                    </div>
                                    <div class="text-center user-info">
                                        <?php
                                        if ($row->member_pic == '') {
                                            echo "<img src='../admin/assets/img/admin/admin.png' class='img-thumbnail img-fluid' alt='avatar'>";
                                        } else {
                                            echo "<img src='../admin/assets/img/clients/$row->member_pic' class='img-thumbnail img-fluid' alt='avatar'>";
                                        }
                                        ?>
                                        <p class=""><?php echo $row->member_name; ?></p>
                                    </div>
                                    <div class="user-info-list">

                                        <div class="">
                                            <ul class="contacts-block list-unstyled">

                                                <li class="contacts-block__item">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar">
                                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                                        <line x1="16" y1="2" x2="16" y2="6"></line>
                                                        <line x1="8" y1="2" x2="8" y2="6"></line>
                                                        <line x1="3" y1="10" x2="21" y2="10"></line>
                                                    </svg><?php echo date('d M Y g:i', strtotime($row->created_at)); ?>
                                                </li>
                                                <li class="contacts-block__item">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-map-pin">
                                                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                                        <circle cx="12" cy="10" r="3"></circle>
                                                    </svg><?php echo $row->member_adr; ?>
                                                </li>
                                                <li class="contacts-block__item">
                                                    <a href="mailto:<?php echo $row->member_email; ?>"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-mail">
                                                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                                            <polyline points="22,6 12,13 2,6"></polyline>
                                                        </svg>
                                                        <?php echo $row->member_email; ?>
                                                    </a>
                                                </li>
                                                <li class="contacts-block__item">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-phone">
                                                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                                                    </svg> <?php echo $row->member_phone; ?>
                                                </li>
                                                <li class="contacts-block__item">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-activity">
                                                        <path d="M12.89 1.45l8 4A2 2 0 0 1 22 7.24v9.53a2 2 0 0 1-1.11 1.79l-8 4a2 2 0 0 1-1.79 0l-8-4a2 2 0 0 1-1.1-1.8V7.24a2 2 0 0 1 1.11-1.79l8-4a2 2 0 0 1 1.78 0z"></path>
                                                        <polyline points="2.32 6.16 12 11 21.68 6.16"></polyline>
                                                        <line x1="12" y1="22.76" x2="12" y2="11"></line>
                                                        <line x1="7" y1="3.5" x2="17" y2="8.5"></line>
                                                    </svg>
                                                    <?php
                                                    if ($row->member_package == 'Gold Package') {
                                                        echo "<span class='badge outline-badge-success'>$row->member_package</span>";
                                                    } elseif ($row->member_package == 'Silver Package') {
                                                        echo "<span class='badge outline-badge-warning'>$row->member_package</span>";
                                                    } elseif ($row->member_package == 'Bronze Package') {
                                                        echo "<span class='badge outline-badge-primary'>$row->member_package</span>";
                                                    } else {
                                                        echo "<span class='badge outline-badge-danger'>$row->member_package</span>";
                                                    }
                                                    ?>
                                                </li>
                                                <!-- <li class="contacts-block__item">
                                                    <ul class="list-inline">
                                                        <li class="list-inline-item">
                                                            <div class="social-icon">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-facebook">
                                                                    <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path>
                                                                </svg>
                                                            </div>
                                                        </li>
                                                        <li class="list-inline-item">
                                                            <div class="social-icon">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-twitter">
                                                                    <path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"></path>
                                                                </svg>
                                                            </div>
                                                        </li>
                                                        <li class="list-inline-item">
                                                            <div class="social-icon">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-linkedin">
                                                                    <path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"></path>
                                                                    <rect x="2" y="9" width="4" height="12"></rect>
                                                                    <circle cx="4" cy="4" r="2"></circle>
                                                                </svg>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </li> -->
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>



                        </div>

                        <div class="col-xl-8 col-lg-6 col-md-7 col-sm-12 layout-top-spacing">

                            <div class="bio layout-spacing ">
                                <div class="education layout-spacing ">
                                    <div class="widget-content widget-content-area">
                                        <h3 class="">Consultation History</h3>
                                        <div class="timeline-alter">
                                            <?php
                                            $ret = "SELECT * FROM `consultations`  WHERE member_id = '$member_id' ";
                                            $stmt = $mysqli->prepare($ret);
                                            $stmt->execute(); //ok
                                            $res = $stmt->get_result();
                                            while ($row = $res->fetch_object()) {
                                            ?>
                                                <div class="item-timeline">
                                                    <div class="t-meta-date">
                                                        <p class=""><?php echo date('d M Y g:i', strtotime($row->created_at)); ?></p>
                                                    </div>
                                                    <div class="t-dot">
                                                    </div>
                                                    <div class="t-text">
                                                        <p><?php echo $row->doc_name; ?></p>
                                                        <p><?php echo $row->consul_details; ?></p>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="bio layout-spacing ">
                                <div class="widget-content widget-content-area">
                                    <h3 class="">Change Password</h3>
                                    <form method="POST">
                                        <div class="form-group mb-4">
                                            <input type="password" required name="old_password" class="form-control" placeholder="Old Password">
                                        </div>
                                        <div class="form-group mb-4">
                                            <input type="password" required name="new_password" class="form-control" placeholder="New Password">
                                        </div>
                                        <div class="form-group mb-4">
                                            <input type="password" required name="confirm_password" class="form-control" placeholder="Confirm New Password">
                                        </div>
                                        <small id="emailHelp" class="form-text text-muted">*Required Fields</small>
                                        <button type="submit" name="change_password" class="btn btn-primary mt-4">Change Password</button>
                                    </form>
                                    <br>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php require_once('partials/_footer.php'); ?>
            </div>
            <!--  END CONTENT AREA  -->
    </div>
<?php
            require_once('partials/_scripts.php');
        }
?>
<!-- END GLOBAL MANDATORY SCRIPTS -->
</body>

</html>