<?php
 global $user;
 global $posts;
 global $follow_suggestions;
 
 ?>
<div class="container col-md-10 col-sm-12 col-lg-9 rounded-0 d-flex justify-content-between">
    <div class="col-md-8 col-sm-12" style="max-width:93vw">


        <?php
           
            showError('post_img');
            if(count($posts)<1){
                echo "<p style='width:100%' class='p-2 bg-white border rounded text-center my-3 col-12'>Follow Someone or Add a new post</p>";
            }
foreach($posts as $post){
    $likes = getLikes($post['id']);
    $comments = getComments($post['id']);
    ?>
        <div class="card mt-4">
            <div class="card-title d-flex justify-content-between  align-items-center">

                <div class="d-flex align-items-center p-2">
                    <img src="assets/images/profile/<?=$post['profile_pic']?>" alt="" height="30" width="30"
                        class="rounded-circle border">&nbsp;&nbsp;<a href='?u=<?=$post['username']?>'
                        class="text-decoration-none text-dark"><?=$post['first_name']?> <?=$post['last_name']?></a>
                </div>
                <div class="p-2">
                    <?php
if($post['uid'] == $user['id']) {
    ?>

                    <div class="dropdown">

                        <i class="bi bi-three-dots-vertical" id="option<?=$post['id']?>" data-bs-toggle="dropdown"
                            aria-expanded="false"></i>

                        <ul class="dropdown-menu" aria-labelledby="option<?=$post['id']?>">
                            <li><a class="dropdown-item" href="assets/php/actions.php?deletepost=<?=$post['id']?>"><i
                                        class="bi bi-trash-fill"></i> Delete Post</a></li>
                            <li><a class="dropdown-item" href="#" data-bs-toggle="modal"
                                    data-bs-target="#editPostModal<?=$post['id']?>"><i class="bi bi-pencil-fill"></i>
                                    Edit Post</a></li>
                        </ul>
                    </div>

                    <!-- Modal for Editing Post -->
                    <div class="modal fade" id="editPostModal<?=$post['id']?>" tabindex="-1"
                        aria-labelledby="editPostModalLabel<?=$post['id']?>" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editPostModalLabel<?=$post['id']?>">Edit Post</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="assets/php/actions.php" method="POST">
                                        <input type="hidden" name="update_post" value="1">
                                        <input type="hidden" name="post_id" value="<?=$post['id']?>">
                                        <textarea name="post_content" class="form-control"
                                            rows="3"><?= htmlspecialchars($post['post_text']) ?></textarea>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php
    }
    
?>


                </div>
            </div>
            <div class="card-body">
                <?=$post['post_text']?>
            </div>
            <img src="assets/images/posts/<?=$post['post_img']?>" loading=lazy class="" alt="...">
            <h4 style="font-size: x-larger" class="p-2 border-bottom d-flex">
                <span>
                    <!--xxxxxxxxxxxxxxxxxxxxxx -->
                    <?php

if(checkLikeStatus($post['id'])){
$like_btn_display='none';
$unlike_btn_display='';
}else {
    $like_btn_display='';
    $unlike_btn_display='none';  
}
    ?>
                    <i class="bi bi-heart-fill unlike_btn text-danger" style="display:<?=$unlike_btn_display?>"
                        data-post-id='<?=$post['id']?>'></i>
                    <i class="bi bi-heart like_btn" style="display:<?=$like_btn_display?>"
                        data-post-id='<?=$post['id']?>'></i>

                </span>
                &nbsp;&nbsp;<i class="bi bi-chat-left d-flex align-items-center"><span class="p-1 mx-2 text-small"
                        style="font-size:small" data-bs-toggle="modal"
                        data-bs-target="#postview<?=$post['id']?>"><?=count($comments)?> comments</span></i>

            </h4>
            <div>
                <span class="p-1 mx-2" data-bs-toggle="modal" data-bs-target="#likes<?=$post['id']?>"><span
                        id="likecount<?=$post['id']?>"><?=count($likes)?></span> likes</span>
                <span style="font-size:small" class="text-muted">Posted</span> <?=show_time($post['created_at'])?>

            </div>
            <?php
if($post['post_text']){
    ?>
            <?php
}

// Đếm tổng số bình luận
$totalComments = count($comments);
?>

<div id="comments-section">
    <?php
    if ($totalComments > 0) {
        foreach ($comments as $index => $comment) {
            $cuser = getUser($comment['user_id']);
            $hiddenClass = ($index >= 3) ? 'd-none' : ''; // Ẩn bình luận vượt quá 3 cái đầu tiên
    ?>
            <div class="d-flex align-items-center p-2 comment-item <?=$hiddenClass?>">
                <div>
                    <img src="assets/images/profile/<?=$cuser['profile_pic']?>" alt="" height="40" width="40"
                        class="rounded-circle border">
                </div>
                <div>&nbsp;&nbsp;&nbsp;</div>
                <div class="d-flex flex-column justify-content-start align-items-start">
                    <h6 style="margin: 0px;">
                        <a href="?u=<?=$cuser['username']?>"
                            class="text-decoration-none text-dark text-small text-muted">@<?=$cuser['username']?></a>
                        - <?=$comment['comment']?>
                    </h6>
                    <p style="margin:0px;" class="text-muted">(<?=show_time($comment['created_at'])?>)</p>
                </div>

                <?php
                // Check if the logged-in user is the comment owner or an admin
                if ($comment['user_id'] == $user['id'] || $user['role'] == 'admin') {
                ?>
                    <div class="ms-auto">
                        <!-- Dropdown button for actions with three-dots icon -->
                        <div class="dropdown">
                            <i class="bi bi-three-dots-vertical" id="dropdownMenuButton<?=$comment['id']?>"
                                data-bs-toggle="dropdown" aria-expanded="false" style="cursor: pointer;"></i>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton<?=$comment['id']?>">

                                <!-- Xóa -->
                                <li>
                                    <a href="assets/php/actions.php?deletecomment=<?=$comment['id']?>" class="dropdown-item"
                                        style="color: black;">
                                        <i class="bi bi-trash-fill"></i> Xóa
                                    </a>
                                </li>

                                <!-- Sửa -->
                                <li>
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#editCommentModal<?=$comment['id']?>"
                                        class="dropdown-item" style="color: black;">
                                        <i class="bi bi-pencil-fill"></i> Sửa
                                    </a>
                                </li>

                            </ul>
                        </div>
                    </div>
                <?php
                }
                ?>

            </div>

            <!-- Modal for Editing Comment -->
            <div class="modal fade" id="editCommentModal<?=$comment['id']?>" tabindex="-1"
                aria-labelledby="editCommentModalLabel<?=$comment['id']?>" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editCommentModalLabel<?=$comment['id']?>">Chỉnh sửa bình luận</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="assets/php/actions.php?updatecomment=1" method="POST">
                                <input type="hidden" name="comment_id" value="<?=$comment['id']?>">
                                <textarea name="comment_text" class="form-control"
                                    rows="3"><?=$comment['comment']?></textarea>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                            <button type="submit" class="btn btn-primary">Cập nhật</button>
                        </div>
                            </form>
                    </div>
                </div>
            </div>
    <?php
        }
    } else {
        echo '<p>Chưa có bình luận nào.</p>';
    }
    ?>

    <!-- Chữ "Xem thêm" -->
    <?php if ($totalComments > 3) { ?>
        <div class="text-center mt-2">
            <a href="javascript:void(0);" id="showMoreLink" style="color: blue; text-decoration: underline;">Xem thêm</a>
        </div>
    <?php } ?>
</div>

<!-- JavaScript -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const showMoreLink = document.getElementById('showMoreLink');
        const hiddenComments = document.querySelectorAll('.comment-item.d-none');

        showMoreLink.addEventListener('click', function () {
            hiddenComments.forEach(comment => {
                comment.classList.remove('d-none'); // Hiện tất cả bình luận
            });
            showMoreLink.style.display = 'none'; // Ẩn chữ "Xem thêm"
        });
    });
</script>

            <?php
    }
?>
<!-- XXXXXXXXXXXXXX -->
<div class="input-group p-2 <?=$post['post_text']?'border-top':''?>">

    <input type="text" class="form-control rounded-0 border-0 comment-input" placeholder="say something.."
        aria-label="Recipient's username" aria-describedby="button-addon2">
    <button class="btn btn-outline-primary rounded-0 border-0 add-comment" data-page='wall'
        data-cs="comment-section<?=$post['id']?>" data-post-id="<?=$post['id']?>" type="button" id="button-addon2">
        <i class="bi bi-send"></i> <!-- Biểu tượng máy bay -->
    </button>
</div>

        <div class="modal fade" id="postview<?=$post['id']?>" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content">

                    <div class="modal-body d-md-flex p-0">
                        <div class="col-md-8 col-sm-12">
                            <img src="assets/images/posts/<?=$post['post_img']?>" style="max-height:90vh"
                                class="w-100 overflow:hidden">
                        </div>



                        <div class="col-md-4 col-sm-12 d-flex flex-column">
                            <div class="d-flex align-items-center p-2 border-bottom">
                                <div><img src="assets/images/profile/<?=$post['profile_pic']?>" alt="" height="50"
                                        width="50" class="rounded-circle border">
                                </div>
                                <div>&nbsp;&nbsp;&nbsp;</div>
                                <div class="d-flex flex-column justify-content-start">
                                    <h6 style="margin: 0px;"><?=$post['first_name']?> <?=$post['last_name']?></h6>
                                    <p style="margin:0px;" class="text-muted">@<?=$post['username']?></p>
                                </div>
                                <div class="d-flex flex-column align-items-end flex-fill">
                                    <div class=""></div>
                                    <div class="dropdown">
                                        <span class="<?=count($likes)<1?'disabled':''?>" id="dropdownMenuButton1"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <?=count($likes)?> likes
                                        </span>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                            <?php
  foreach($likes as $like){
      $lu = getUser($like['user_id']);
      ?>
                                            <li><a class="dropdown-item"
                                                    href="?u=<?=$lu['username']?>"><?=$lu['first_name'].' '.$lu['last_name']?>
                                                    (@<?=$lu['username']?>)</a></li>

                                            <?php
  }
  ?>

                                        </ul>
                                    </div>
                                    <div style="font-size:small" class="text-muted">Posted
                                        <?=show_time($post['created_at'])?> </div>

                                </div>
                            </div>


                            <div class="flex-fill align-self-stretch overflow-auto" id="comment-section<?=$post['id']?>"
                                style="height: 100px;">

                                <?php
if(count($comments)<1){
    ?>
                                <p class="p-3 text-center my-2 nce">no comments</p>
                                <?php
}
foreach($comments as $comment){
    $cuser = getUser($comment['user_id']);
    ?>
                                <div class="d-flex align-items-center p-2">
                                    <div><img src="assets/images/profile/<?=$cuser['profile_pic']?>" alt="" height="40"
                                            width="40" class="rounded-circle border">
                                    </div>
                                    <div>&nbsp;&nbsp;&nbsp;</div>
                                    <div class="d-flex flex-column justify-content-start align-items-start">
                                        <h6 style="margin: 0px;"><a href="?u=<?=$cuser['username']?>"
                                                class="text-decoration-none text-dark text-small text-muted">@<?=$cuser['username']?></a>
                                            - <?=$comment['comment']?></h6>
                                        <p style="margin:0px;" class="text-muted">
                                            (<?=show_time($comment['created_at'])?>)</p>
                                    </div>
                                </div>

                                <?php
}
                          ?>






                            </div>
                            <div class="input-group p-2 border-top">
                                <input type="text" class="form-control rounded-0 border-0 comment-input"
                                    placeholder="say something.." aria-label="Recipient's username"
                                    aria-describedby="button-addon2">
                                <button class="btn btn-outline-primary rounded-0 border-0 add-comment"
                                    data-cs="comment-section<?=$post['id']?>" data-post-id="<?=$post['id']?>"
                                    type="button" id="button-addon2">Post</button>
                            </div>
                        </div>



                    </div>

                </div>
            </div>
        </div>

        <div class="modal fade" id="likes<?=$post['id']?>" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Likes</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <?php
                if(count($likes)<1){
                    ?>
                        <p>Currently No Likes</p>
                        <?php
                }
foreach($likes as $f){

    $fuser = getUser($f['user_id']);
    $fbtn='';
    if(checkBS($f['user_id'])){
continue;
    }else if(checkFollowStatus($f['user_id'])){
        $fbtn = '<button class="btn btn-sm btn-danger unfollowbtn" data-user-id='.$fuser['id'].' >Unfollow</button>';
    }else if($user['id']==$f['user_id']){
        $fbtn='';
    }else{
        $fbtn = '<button class="btn btn-sm btn-primary followbtn" data-user-id='.$fuser['id'].' >Follow</button>';

    }
    ?>
                        <div class="d-flex justify-content-between">
                            <div class="d-flex align-items-center p-2">
                                <div><img src="assets/images/profile/<?=$fuser['profile_pic']?>" alt="" height="40"
                                        width="40" class="rounded-circle border">
                                </div>
                                <div>&nbsp;&nbsp;</div>
                                <div class="d-flex flex-column justify-content-center">
                                    <a href='?u=<?=$fuser['username']?>' class="text-decoration-none text-dark">
                                        <h6 style="margin: 0px;font-size: small;"><?=$fuser['first_name']?>
                                            <?=$fuser['last_name']?></h6>
                                    </a>
                                    <p style="margin:0px;font-size:small" class="text-muted">@<?=$fuser['username']?>
                                    </p>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <?=$fbtn?>

                            </div>
                        </div>
                        <?php
}
                ?>
                    </div>

                </div>
            </div>
        </div>

        <?php

            ?>



    </div>

    <div class="col-lg-4 col-sm-0 overflow-hidden mt-4 p-sm-0 p-md-3">


        <div class="d-flex align-items-center p-2">
            <div><img src="assets/images/profile/<?=$user['profile_pic']?>" alt="" height="60" width="60"
                    class="rounded-circle border">
            </div>
            <div>&nbsp;&nbsp;&nbsp;</div>
            <div class="d-flex flex-column justify-content-center">
                <a href='?u=<?=$user['username']?>' class="text-decoration-none text-dark">
                    <h6 style="margin: 0px;"><?=$user['first_name']?> <?=$user['last_name']?></h6>
                </a>
                <p style="margin:0px;" class="text-muted">@<?=$user['username']?></p>
            </div>
        </div>


        <div>
            <h6 class="text-muted p-2">You Can Follow Them</h6>
            <?php
foreach($follow_suggestions as $suser){
    ?>
            <div class="d-flex justify-content-between">
                <div class="d-flex align-items-center p-2">
                    <div><img src="assets/images/profile/<?=$suser['profile_pic']?>" alt="" height="40" width="40"
                            class="rounded-circle border">
                    </div>
                    <div>&nbsp;&nbsp;</div>
                    <div class="d-flex flex-column justify-content-center">
                        <a href='?u=<?=$suser['username']?>' class="text-decoration-none text-dark">
                            <h6 style="margin: 0px;font-size: small;"><?=$suser['first_name']?> <?=$suser['last_name']?>
                            </h6>
                        </a>
                        <p style="margin:0px;font-size:small" class="text-muted">@<?=$suser['username']?></p>
                    </div>
                </div>
                <div class="d-flex align-items-center">
                    <button class="btn btn-sm btn-primary followbtn" data-user-id='<?=$suser['id']?>'>Follow</button>

                </div>
            </div>
            <?php
}

if(count($follow_suggestions)<1){
    echo "<p class='p-2 bg-white border rounded text-center'>No Suggestions For You</p>";
}
                ?>




        </div>
    </div>
</div>