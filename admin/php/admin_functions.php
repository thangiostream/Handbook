<?php
require_once($function_url ?? '../../assets/php/functions.php');

// Kiểm tra nếu dữ liệu từ biểu mẫu được gửi (thay vì kiểm tra request method)




//for checking the user
// function checkAdminUser($login_data)
// {
//     global $db;
//     $email = $login_data['email'];
//     $password = $login_data['password'];
//     $role = $login_data['role'];
    

//     $query = "SELECT * FROM users WHERE email='$email' && password='$password' ";
//     $run = mysqli_query($db, $query);
//     $data['user'] = mysqli_fetch_assoc($run) ?? array();
//     if (count($data['user']) > 0) {
//         $data['status'] = true;
//         $data['user_id'] = $data['user']['id'];
//     } else {
//         $data['status'] = false;
//     }

//     return $data;
// }

//Kiểm tra tài khoản Admin
function checkAdminUser($login_data)
{
    global $db;
    $email = $login_data['email'];
    $password = $login_data['password'];
    $role = $login_data['role'];
    
    // Thêm điều kiện kiểm tra role là 'Admin'
    $query = " SELECT * FROM users WHERE email='$email' AND password='$password' AND role='Admin' ";
    $run = mysqli_query($db, $query);

    // Lấy thông tin người dùng nếu tồn tại
    $data['user'] = mysqli_fetch_assoc($run) ?? array();

    // Kiểm tra nếu có người dùng và role là Admin
    if (count($data['user']) > 0) {
        $data['status'] = true;
        $data['user_id'] = $data['user']['id'];
        $data['role'] = $data['user']['role'];
    } else {
        $data['status'] = false;
    }

    return $data;
}


function getAdmin($user_id)
{
    global $db;
    $query = "SELECT * FROM users WHERE id=$user_id";
    $run = mysqli_query($db, $query);
    return mysqli_fetch_assoc($run);
}


// function getUsersList()
// {
//     global $db;
//     $query = "SELECT * FROM users ORDER BY id DESC";
//     $run = mysqli_query($db, $query);
//     return mysqli_fetch_all($run, true);
// }
function getUsersList($searchKeyword = '') {
    // Kết nối cơ sở dữ liệu (giả sử bạn đã có kết nối $conn)
    global $db;

    // Truy vấn SQL cơ bản
    $sql = "SELECT * FROM users";

    // Nếu có từ khóa tìm kiếm, thêm điều kiện WHERE
    if (!empty($searchKeyword)) {
        $searchKeyword = mysqli_real_escape_string($db, $searchKeyword);
        $sql .= " WHERE first_name LIKE '%$searchKeyword%' OR last_name LIKE '%$searchKeyword%' OR username LIKE '%$searchKeyword%' OR email LIKE '%$searchKeyword%'";
    }

    // Thực thi truy vấn
    $result = mysqli_query($db, $sql);

    // Xử lý kết quả
    $users = [];
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $users[] = $row;
        }
    }
    return $users;
}

// Xâm nhập vào tài khoản người dùng
function loginUserByAdmin($email)
{
    global $db;

    $query = "SELECT * FROM users WHERE email='$email'";
    $run = mysqli_query($db, $query);
    $data['user'] = mysqli_fetch_assoc($run) ?? array();
    if (count($data['user']) > 0) {
        $data['status'] = true;
    } else {
        $data['status'] = false;
    }

    return $data;
}

function totalCommentsCount()
{
    global $db;
    $query = "SELECT count(*) as row FROM comments";
    $run = mysqli_query($db, $query);
    return mysqli_fetch_assoc($run)['row'];
}

function totalPostsCount()
{
    global $db;
    $query = "SELECT count(*) as row FROM posts";
    $run = mysqli_query($db, $query);
    return mysqli_fetch_assoc($run)['row'];
}

function totalUsersCount()
{
    global $db;
    $query = "SELECT count(*) as row FROM users";
    $run = mysqli_query($db, $query);
    return mysqli_fetch_assoc($run)['row'];
}

function totalLikesCount()
{
    global $db;
    $query = "SELECT count(*) as row FROM likes";
    $run = mysqli_query($db, $query);
    return mysqli_fetch_assoc($run)['row'];
}


function blockUserByAdmin($user_id)
{
    global $db;
    $query = "UPDATE users SET ac_status=2 WHERE id=$user_id";
    return mysqli_query($db, $query);
}
function unblockUserByAdmin($user_id)
{
    global $db;
    $query = "UPDATE users SET ac_status=1 WHERE id=$user_id";
    return mysqli_query($db, $query);
}
// function updateAdmin($data)
// {
//     global $db;
//     $user_id = $data['user_id'];
//     $first_name = $data['first_name'];
//     $last_name = $data['last_name'];
//     $email = $data['email'];
//     $password = $data['password'];
//     //$password_text = md5($data['password']);
   
  
    
//     $query = "UPDATE users SET first_name='$first_name',last_name='$last_name',email='$email',password='$password' WHERE id=$user_id";
//     return mysqli_query($db, $query);
// }
function updateAdmin($data)
{
    global $db;
    $user_id = $data['user_id'];
    $first_name = $data['first_name'];
    $last_name = $data['last_name'];
    $email = $data['email'];
    $password = $data['password'];
    $role = $data['role'];
    
    // Cập nhật thông tin người dùng bao gồm role
    $query = "UPDATE users 
              SET first_name='$first_name',
                  last_name='$last_name',
                  email='$email',
                  password='$password',
                  role='$role'
              WHERE id='$user_id'";

    // Thực hiện truy vấn
    $result = mysqli_query($db, $query);

    // Kiểm tra và trả về kết quả
    if ($result) {
        return true; // Cập nhật thành công
    } else {
        return false; // Cập nhật thất bại
    }
}

