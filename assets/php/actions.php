<?php
require_once 'functions.php';
require_once 'send_code.php';




if(isset($_GET['block'])){
    $user_id = $_GET['block'];
    $user = $_GET['username']; 
      if(blockUser($user_id)){
          header("location:../../?u=$user");
      }else{
          echo "something went wrong";
      }
  
    
  }

  if(isset($_GET['deletepost'])){
    $post_id = $_GET['deletepost'];
      if(deletePost($post_id)){
          header("location:{$_SERVER['HTTP_REFERER']}");
      }else{
          echo "something went wrong";
      }
  
    
  }



//for managaing signup
if(isset($_GET['signup'])){
$response=validateSignupForm($_POST);
if($response['status']){
    if(createUser($_POST)){
    header('location:../../?login&newuser');
    }else{
        echo "<script>alert('somethihng is wrong')</script>";
    }
   

}else{
    $_SESSION['error']=$response;
    $_SESSION['formdata']=$_POST;
    header("location:../../?signup");
}
    
}



//for managing login
if(isset($_GET['login'])){

  
    $response=validateLoginForm($_POST);
  
    if($response['status']){
     $_SESSION['Auth'] = true;
     $_SESSION['userdata'] = $response['user'];

     if($response['user']['ac_status']==0){
     $_SESSION['code']=$code = rand(111111,999999);
     sendCode($response['user']['email'],'Verify Your Email',$code);
     }

     header("location:../../");

    }else{
        $_SESSION['error']=$response;
        $_SESSION['formdata']=$_POST;
        header("location:../../?login");
    }
        
    }


    if(isset($_GET['resend_code'])){
       
            $_SESSION['code']=$code = rand(111111,999999);
            sendCode($_SESSION['userdata']['email'],'Verify Your Email',$code);
            header('location:../../?resended');
    }

    if(isset($_GET['verify_email'])){
       $user_code = $_POST['code'];
       $code = $_SESSION['code'];
       if($code==$user_code){
       if(verifyEmail($_SESSION['userdata']['email'])){
        header('location:../../');
       }else{
           echo "something is wrong";
       }

       }else{
           $response['msg']='incorrect verifictaion code !';
           if(!$_POST['code']){
            $response['msg']='enter 6 digit code !';

           }
           $response['field']='email_verify';
        $_SESSION['error']=$response;
        header('location:../../');

       }
       
}


if(isset($_GET['forgotpassword'])){
    if(!$_POST['email']){
        $response['msg']="enter your email id !";
        $response['field']='email';
        $_SESSION['error']=$response;
        header('location:../../?forgotpassword');

    }elseif(!isEmailRegistered($_POST['email'])){
        $response['msg']="email id is not registered";
        $response['field']='email';
        $_SESSION['error']=$response;
        header('location:../../?forgotpassword');

    }else{
          $_SESSION['forgot_email']=$_POST['email'];
           $_SESSION['forgot_code']=$code = rand(111111,999999);
            sendCode($_POST['email'],'Forgot Your Password ?',$code);
            header('location:../../?forgotpassword&resended');
    }


}



//for logout the user
if(isset($_GET['logout'])){
    session_destroy();
    header('location:../../');

}


// for verify forgot code
if(isset($_GET['verifycode'])){
    $user_code = $_POST['code'];
    $code = $_SESSION['forgot_code'];
    if($code==$user_code){
    $_SESSION['auth_temp']=true;
     header('location:../../?forgotpassword');
    }else{
        $response['msg']='incorrect verifictaion code !';
        if(!$_POST['code']){
         $response['msg']='enter 6 digit code !';

        }
        $response['field']='email_verify';
     $_SESSION['error']=$response;
     header('location:../../?forgotpassword');

    }
    
}

if(isset($_GET['changepassword'])){
    if(!$_POST['password']){
        $response['msg']="enter your new password";
        $response['field']='password';
        $_SESSION['error']=$response;
        header('location:../../?forgotpassword');
    }else{
        resetPassword($_SESSION['forgot_email'],$_POST['password']);
        session_destroy();
        header('location:../../?reseted');
    }


}


if(isset($_GET['updateprofile'])){

    $response=validateUpdateForm($_POST,$_FILES['profile_pic']);

    if($response['status']){
       
        if(updateProfile($_POST,$_FILES['profile_pic'])){
            header("location:../../?editprofile&success");

        }else{
            echo "something is wrong";
        }
       
    
    }else{
        $_SESSION['error']=$response;
        header("location:../../?editprofile");
    }
     
}
if (isset($_GET['deletecomment'])) {
    $comment_id = $_GET['deletecomment'];

    // Kiểm tra xem người dùng có quyền xóa bình luận này không
    $user_id = $_SESSION['userdata']['id']; // Lấy ID người dùng từ session

    // Kiểm tra nếu bình luận thuộc về người dùng hoặc người dùng là admin
    $check_comment = mysqli_query($db, "SELECT * FROM comments WHERE id='$comment_id' AND (user_id='$user_id' OR '$user_id' = 'admin')");
    if (mysqli_num_rows($check_comment) > 0) {
        // Tiến hành xóa bình luận
        $delete_comment = mysqli_query($db, "DELETE FROM comments WHERE id='$comment_id'");
        if ($delete_comment) {
            // Chuyển hướng về trang trước đó
            $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '../../';
            header("Location: $referer");
            exit();
        } else {
            echo "Error deleting comment.";
        }
    } else {
        echo "You do not have permission to delete this comment.";
    }
}


if (isset($_GET['updatecomment']) && $_GET['updatecomment'] == 1) {
    // Get the data from the form
    $comment_id = $_POST['comment_id'];
    $comment_text = mysqli_real_escape_string($db, $_POST['comment_text']); // Sanitize input

    // Get the user ID from the session
    $user_id = $_SESSION['userdata']['id'];

    // Check if the comment exists and if the user is authorized to update it
    $check_comment = mysqli_query($db, "SELECT * FROM comments WHERE id='$comment_id' AND (user_id='$user_id' OR '$user_id' = 'admin')");
    
    if (mysqli_num_rows($check_comment) > 0) {
        // Update the comment
        $update_query = "UPDATE comments SET comment='$comment_text' WHERE id='$comment_id'";
        $result = mysqli_query($db, $update_query);
        
        if ($result) {
            // Redirect to the previous page or a success page
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit();
        } else {
            echo "Error updating the comment.";
        }
    } else {
        echo "You do not have permission to edit this comment.";
    }
}


// Code for actions.php to handle post editing
if (isset($_POST['update_post']) && $_POST['update_post'] == 1) {
    $post_id = $_POST['post_id'];
    $post_content = $_POST['post_content'];

    // Sanitize input and perform the update in the database
    $stmt = $db->prepare("UPDATE posts SET post_text = ? WHERE id = ?");
    $stmt->bind_param("si", $post_content, $post_id);

    if ($stmt->execute()) {
        // Redirect or show success message
        header("location:../../?new_post_added");
        exit();
    } else {
        // Show error message if the update fails
        echo "Error updating post: " . $db->error;
    }
}

//for managing add post
if(isset($_GET['addpost'])){
   $response = validatePostImage($_FILES['post_img']);

   if($response['status']){
if(createPost($_POST,$_FILES['post_img'])){
    header("location:../../?new_post_added");
}else{
    echo "something went wrong";
}
   }else{
    $_SESSION['error']=$response;
    header("location:../../");
   }
}