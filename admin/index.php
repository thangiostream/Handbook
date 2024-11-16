<?php
$function_url = "../assets/php/functions.php";
include('./php/admin_functions.php');
if (!isset($_SESSION['admin_auth'])) header('Location:./pages/login.php');
$admin = getAdmin($_SESSION['admin_auth']);

$message = ''; // Biến để lưu thông báo

if (isset($_POST['update_role']) && isset($_POST['id'])) {

    $role = $_POST['update_role'];
    $id = $_POST['id'];

    // Cập nhật vai trò người dùng
    $stmt = $db->prepare("UPDATE users SET role = ? WHERE id = ?");
    $stmt->bind_param("si", $role, $id);
    $result = $stmt->execute();
    $stmt->close();

    // Kiểm tra kết quả và hiển thị thông báo
    if ($result) {
        $message = 'Cập nhật vai trò người dùng thành công!';
    } else {
        $message = 'Cập nhật vai trò người dùng thất bại. Vui lòng thử lại.';
    }
}

if (isset($_POST['delete_user'])) {
    $user_id = $_POST['user_id'];

    // Thực hiện câu truy vấn xóa người dùng
    $stmt = $db->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        echo "<p style='color: green;'>Người dùng đã được xóa thành công!</p>";
    } else {
        echo "<p style='color: red;'>Xóa người dùng thất bại!</p>";
    }

    $stmt->close();
}



?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pictogram | Dashboard</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- JQVMap -->
    <link rel="stylesheet" href="plugins/jqvmap/jqvmap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">
    <!-- summernote -->
    <link rel="stylesheet" href="plugins/summernote/summernote-bs4.min.css">



</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <!-- Load lại trang -->
        <!-- <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="../assets/images/icon.png" alt="AdminLTELogo" height="60" width="60">
        </div> -->

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>

            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <!-- Navbar Search -->
                <!-- <li class="nav-item">
        <a class="nav-link" data-widget="navbar-search" href="#" role="button">
          <i class="fas fa-search"></i>
        </a>
        <div class="navbar-search-block">
          <form class="form-inline">
            <div class="input-group input-group-sm">
              <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
              <div class="input-group-append">
                <button class="btn btn-navbar" type="submit">
                  <i class="fas fa-search"></i>
                </button>
                <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                  <i class="fas fa-times"></i>
                </button>
              </div>
            </div>
          </form>
        </div>
      </li> -->

                <li class="nav-item">
                    <a class=" btn btn-sm btn-danger" href="php/admin_actions.php?logout" role="button">
                        Logout
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                        <i class="fas fa-expand-arrows-alt"></i>
                    </a>
                </li>

            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="index3.html" class="brand-link">
                <img src="../assets/images/icon.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
                    style="opacity: .8">
                <span class="brand-text font-weight-light">Pictogram</span>
            </a>

            <div class="sidebar">
                <!-- Sidebar user panel (optional) -->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">

                    <div class="info">
                        <a href="#" class="d-block"><?= $admin['first_name'] . ' ' . $admin['last_name'] ?></a>
                    </div>
                </div>




                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">
                        <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
                        <li class="nav-item">
                            <a href="?dashboard" class="nav-link">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>
                                    Dashboard

                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="?edit_profile" class="nav-link">
                                <i class="nav-icon fas fa-user"></i>
                                <p>
                                    Edit Profile
                                </p>
                            </a>
                        </li>

                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">
                                <?php if (isset($_GET['edit_profile'])) {
                                    echo "Edit Profile";
                                } else {

                                    echo "Dashboard";
                                } ?>
                            </h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">

                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <!-- Small boxes (Stat box) -->
                    <?php if (isset($_GET['edit_profile'])) {
                    } else {
                    ?>
                    <div class="row">
                        <div class="col-lg-3 col-6">
                            <!-- small box -->
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3><?= totalUsersCount() ?></h3>

                                    <p>Total Users</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-bag"></i>
                                </div>
                            </div>
                        </div>
                        <!-- ./col -->
                        <div class="col-lg-3 col-6">
                            <!-- small box -->
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3><?= totalPostsCount() ?></h3>
                                    <p>Total Posts</p>
                                </div>

                                <div class="icon">
                                    <i class="ion ion-stats-bars"></i>
                                </div>

                            </div>
                        </div>
                        <!-- ./col -->
                        <div class="col-lg-3 col-6">
                            <!-- small box -->
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3><?= totalCommentsCount() ?></h3>
                                    <p>Total Comments</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-person-add"></i>
                                </div>

                            </div>
                        </div>
                        <!-- ./col -->
                        <div class="col-lg-3 col-6">
                            <!-- small box -->
                            <div class="small-box bg-danger">
                                <div class="inner">
                                    <h3><?= totalLikesCount() ?></h3>
                                    <p>Total Likes</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-pie-graph"></i>
                                </div>
                            </div>
                        </div>
                        <!-- ./col -->
                    </div>
                    <?php
                    }

                    ?>

                    <!-- /.row -->
                    <!-- Main row -->
                    <div class="row">
                        <?php
                        if (isset($_GET['edit_profile'])) {
                        ?>
                        <div class="card card-primary col-12">
                            <div class="card-header">
                                <h3 class="card-title">Edit Your Profile</h3>
                            </div>
                            <!-- form start -->
                            <?= showError('adminprofile') ?>
                            <form method="post" action="php/admin_actions.php?updateprofile">
                                <input type="hidden" name="user_id" value="<?= $admin['id'] ?>">
                                <div class="card-body">
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="firstName">First Name</label>
                                            <input type="text" name="first_name" value="<?= $admin['first_name'] ?>"
                                                class="form-control" id="firstName" placeholder="Enter First Name"
                                                required>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="lastName">Last Name</label>
                                            <input type="text" name="last_name" value="<?= $admin['last_name'] ?>"
                                                class="form-control" id="lastName" placeholder="Enter Last Name"
                                                required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Email address</label>
                                        <input type="email" name="email" value="<?= $admin['email'] ?>"
                                            class="form-control" id="exampleInputEmail1" placeholder="Enter email"
                                            required>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputPassword1">Password</label>
                                        <input type="text" name="password" value="<?= $admin['password'] ?>"
                                            class="form-control" id="exampleInputPassword1" placeholder="Password">
                                    </div>

                                    <!-- Dropdown Role -->
                                    <div class="form-group">
                                        <label for="role">Select Role</label>
                                        <select class="form-control" name="role" id="role" required>
                                            <option value="Admin" <?= $admin['role'] == 'Admin' ? 'selected' : '' ?>>
                                                Admin</option>
                                            <option value="User" <?= $admin['role'] == 'User' ? 'selected' : '' ?>>User
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <!-- /.card-body -->

                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">Update Profile</button>
                                </div>
                            </form>
                        </div>



                        <?php
                        } else {
                        ?>
                        <div class="card w-100">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <!-- Tiêu đề User Lists -->
                                <h3 class="card-title mb-0">User Lists</h3>

                                <!-- Form tìm kiếm -->
                                <form method="get" action="" id="searchForm">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="search"
                                            placeholder="Tìm kiếm người dùng"
                                            value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>"
                                            id="searchInput">
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- /.card-header -->
                        <div class="card-body">
                            <?php
    // Kiểm tra từ khóa tìm kiếm
    $searchKeyword = isset($_GET['search']) ? trim($_GET['search']) : '';

    // Gọi hàm lấy danh sách người dùng với điều kiện tìm kiếm (nếu có)
    $userslist = getUsersList($searchKeyword);
    $count = 1;
    ?>
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#No</th>
                                        <th>User</th>
                                        <th>Actions</th>
                                        <th>Roles</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Liệt kê danh sách người dùng -->
                                    <?php
            if (!empty($userslist)) {
                foreach ($userslist as $user) {
                    ?>
                                    <tr>
                                        <td>#<?= $count ?></td>
                                        <td>
                                            <div class="d-flex">
                                                <div>
                                                    <img src="../assets/images/profile/<?= $user['profile_pic'] ?>"
                                                        class="rounded-circle border border-2 shadow-sm mx-2"
                                                        width="55px" height="55px" />
                                                </div>
                                                <div>
                                                    <h5><?= $user['first_name'] . ' ' . $user['last_name'] ?> -
                                                        <span class="text-muted">@<?= $user['username'] ?></span>
                                                    </h5>
                                                    <h6 class="text-muted"><?= $user['email'] ?></h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2 px-2">
                                                <!-- Login User -->
                                                <a href="./php/admin_actions.php?userlogin=<?= $user['email'] ?>"
                                                    target="_blank">
                                                    <input type="button" value="Login User"
                                                        class="btn btn-success btn-sm" style="margin-right: 10px;">
                                                </a>

                                                <!-- Verify User -->
                                                <?php if ($user['ac_status'] == 0): ?>
                                                <input type="button" value="Verify"
                                                    class="btn btn-warning btn-sm verify_user_btn"
                                                    data-user-id="<?= $user['id'] ?>" style="margin-right: 10px;">
                                                <?php endif; ?>

                                                <!-- Block User -->
                                                <input type="button" value="Block"
                                                    style="display:<?= $user['ac_status'] == 1 ? '' : 'none' ?>; margin-right: 10px;"
                                                    class="btn btn-danger btn-sm block_user_btn ub"
                                                    data-user-id="<?= $user['id'] ?>">

                                                <!-- Unblock User -->
                                                <input type="button" value="Unblock"
                                                    style="display:<?= $user['ac_status'] == 2 ? '' : 'none' ?>; margin-right: 10px;"
                                                    class="btn btn-primary btn-sm unblock_user_btn"
                                                    data-user-id="<?= $user['id'] ?>">

                                                <!-- Delete User Form -->
                                                <form method="POST" action="" class="m-0 p-0" style="display:inline;">
                                                    <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                                    <button type="submit" name="delete_user"
                                                        class="btn btn-danger btn-sm">Xóa</button>
                                                </form>
                                            </div>
                                        </td>
                                        <td>
                                            <form method="post" action="">
                                                <input type="hidden" name="id" value="<?= $user['id'] ?>">
                                                <select name="update_role" onchange="this.form.submit()">
                                                    <option value="Admin"
                                                        <?= ($user['role'] == 'Admin' ? 'selected' : '') ?>>Admin
                                                    </option>
                                                    <option value="User"
                                                        <?= ($user['role'] == 'User' ? 'selected' : '') ?>>User</option>
                                                </select>
                                            </form>
                                        </td>
                                    </tr>
                                    <?php
                    $count++;
                }
            } else {
                echo '<tr><td colspan="4">Không tìm thấy người dùng nào.</td></tr>';
            }
            ?>
                                </tbody>
                            </table>
                        </div>

                        <script>

                        </script>

                    </div>


                    <?php
                        }
                            ?>
                </div>
                <!-- /.row (main row) -->
        </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
    let typingTimer; // Timer để đợi trước khi gửi form
    const doneTypingInterval = 1000; // Thời gian chờ (300ms)
    const searchInput = document.getElementById('searchInput');

    // Bắt sự kiện nhập vào ô tìm kiếm
    searchInput.addEventListener('input', () => {
        clearTimeout(typingTimer); // Xóa timer cũ để tránh gửi form liên tục
        typingTimer = setTimeout(() => {
            document.getElementById('searchForm').submit(); // Gửi form khi người dùng dừng nhập
        }, doneTypingInterval);
    });
    </script>
    <!-- /.content-wrapper -->
    <footer class="main-footer">
        <strong>Copyright &copy; 2021 <a href="https://www.youtube.com/channel/UCtpdZTndGnAyX-8uxUdTDnQ"
                target="_blank">DevNinja Pvt Ltd.</a>.</strong>
        All rights reserved.
        <div class="float-right d-none d-sm-inline-block">
            <b>Version</b> 3.1.0
        </div>
    </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="plugins/jquery-ui/jquery-ui.min.js"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
    $.widget.bridge('uibutton', $.ui.button)
    </script>
    <!-- Bootstrap 4 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>

    <!-- ChartJS -->
    <script src="plugins/chart.js/Chart.min.js"></script>
    <!-- Sparkline -->
    <script src="plugins/sparklines/sparkline.js"></script>
    <!-- JQVMap -->
    <script src="plugins/jqvmap/jquery.vmap.min.js"></script>
    <script src="plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
    <!-- jQuery Knob Chart -->
    <script src="plugins/jquery-knob/jquery.knob.min.js"></script>
    <!-- daterangepicker -->
    <script src="plugins/moment/moment.min.js"></script>
    <script src="plugins/daterangepicker/daterangepicker.js"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
    <!-- Summernote -->
    <script src="plugins/summernote/summernote-bs4.min.js"></script>
    <!-- overlayScrollbars -->
    <script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
    <!-- AdminLTE App -->





    <script src="dist/js/adminlte.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="dist/js/demo.js"></script>
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <script src="dist/js/pages/dashboard.js"></script>
    <script src="js/actions.js?v=<?= time() ?>"></script>

</body>

</html>
<?php

if (isset($_SESSION['error'])) {
    unset($_SESSION['error']);
}
?>