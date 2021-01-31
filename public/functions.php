<?php

function fetch_posts($db){

  try {
      $stmt = $db->query('SELECT * FROM posts WHERE post_privacy = "public" ORDER BY post_date DESC');
      $post_count = $stmt->rowCount();
      if ($post_count > 0) {
        echo '<div class="cont">';
        while($row = $stmt->fetch()){

          echo '
            <div class="card" style="border:none; margin-bottom:10px" id="'.$row['post_id'].'">
            <table style="margin:10px">
              <tr>
                <td rowspan="2" width="36px">
                  <img src="'.user_avatar($db, $row['post_author']).'" class="img-responsive rounded-circle" width="32px" height="32px" style="margin:4px">
                </td>
              </tr>
               <tr>
                <td>
                  <span style="font-size:10pt; padding:10px 0 0 10px;"><a class="text-dark" id="user" data-user="'.$row['post_author'].'"><b>'.user_details_name($db, $row['post_author']).'</b></a></span>
                  <br/>
                  <span style="font-size: 10pt; padding:0px 0 10px 10px;" class="text-muted"><small><i>@'.$row['post_author'].'</i></small></span>
                </td>
              </tr>
            </table>
            
            
              <img id="postImg" data-post="'.$row['post_id'].'" class="card-img thumb" src="tmp/'.$row['post_media'].'" alt="'.$row['post_media'].'">
              <div class="card-body">
              ';
                  if(is_liked($db, $_SESSION['user'], $row['post_id'])){
                    echo '<i class="fa fa-heart fa-2x text-danger likeBtn" data-post="'.$row['post_id'].'"></i>';
                  }else{
                    echo '<i class="fa fa-heart-o fa-2x text-secondary likeBtn" data-post="'.$row['post_id'].'"></i></a>';
                  }
                  echo ' 
                  <i id="commentBtn" data-post="'.$row['post_id'].'" class="fa fa-comments-o fa-2x text-secondary" aria-hidden="true"></i>
                  <i class="fa fa-share fa-2x text-secondary"></i>
                  <br/>
                  <p class="semi-bold">'.post_like_count($db, $row['post_id']).' people like this</p>
                  <hr class="my-4"/>        
                  <p class="">'.$row['post_content'].'</p>
                  <p class="card-text">
                    <small class="text-muted">
                      '.time_elapsed_string($row['post_date']).'
                    </small>
                  </p>
              </div>
            </div>
          ';

        }
        echo '</div>';
      }
      else{
        echo "<hr/>No posts are available";
      }

  } catch(PDOException $e) {
      echo $e->getMessage();
  }
}


function fetch_post_by_id($db, $post){

  try {
      $stmt = $db->prepare('SELECT * FROM posts WHERE post_privacy = "public" AND post_id = :post_id');
      $stmt->execute(array(
        ':post_id' => $post
      ));
      $post_count = $stmt->rowCount();
      if ($post_count > 0) {
        echo '<div class="cont">';
        while($row = $stmt->fetch()){

          echo '
            <div class="card" style="border:none; margin-bottom:10px" id="'.$row['post_id'].'">
            <table style="margin:10px">
              <tr>
                <td rowspan="2" width="36px">
                  <img src="'.user_avatar($db, $row['post_author']).'" class="img-responsive rounded-circle" width="32px" height="32px" style="margin:4px">
                </td>
              </tr>
               <tr>
                <td>
                  <span style="font-size:10pt; padding:10px 0 0 10px;"><a class="text-dark" id="user" data-user="'.$row['post_author'].'"><b>'.user_details_name($db, $row['post_author']).'</b></a></span>
                  <br/>
                  <span style="font-size: 10pt; padding:0px 0 10px 10px;" class="text-muted"><small><i>@'.$row['post_author'].'</i></small></span>
                </td>
              </tr>
            </table>
            
            
              <img id="postImg" data-post="'.$row['post_id'].'" class="card-img thumb" src="tmp/'.$row['post_media'].'" alt="'.$row['post_media'].'">
              <div class="card-body">
              ';
                  if(is_liked($db, $_SESSION['user'], $row['post_id'])){
                    echo '<i class="fa fa-heart fa-2x text-danger likeBtn" data-post="'.$row['post_id'].'"></i>';
                  }else{
                    echo '<i class="fa fa-heart-o fa-2x text-secondary likeBtn" data-post="'.$row['post_id'].'"></i></a>';
                  }
                  echo ' 
                  <i class="fa fa-comments-o fa-2x text-secondary" aria-hidden="true"></i>
                  <i class="fa fa-share fa-2x text-secondary"></i>
                  <br/>
                  <p class="semi-bold">'.post_like_count($db, $row['post_id']).' people like this</p>
                  <hr class="my-4"/>        
                  <p class="">'.$row['post_content'].'</p>
                  <p class="card-text">
                    <small class="text-muted">
                      '.time_elapsed_string($row['post_date']).'
                    </small>
                  </p>
              </div>
            </div>
          ';

        }
      }
      else{
        echo "<hr/>No posts are available";
      }

  } catch(PDOException $e) {
      echo $e->getMessage();
  }
}


function fetch_user_posts_protected($db, $user){

  try {
      $stmt = $db->prepare('SELECT * FROM posts WHERE post_privacy = "public" AND post_author = :post_author ORDER BY post_date DESC');
      $stmt->execute(array(
        ':post_author' => $user
      ));

      $post_count = $stmt->rowCount();
      if ($post_count > 0) {
        while($row = $stmt->fetch()){

          echo '
            <div class="col-sm-3">
                <a class="lightbox" href="tmp/'.$row['post_media'].'" data-imtitle="'.$row['post_content'].'" data-post="'.$row['post_id'].'" data-toggle="lightbox" data-gallery="gallery" data-target="#modal">
                    <img class="img-responsive thumb" style="max-height:350px;" src="tmp/'.$row['post_media'].'" alt="Bridge">
                </a>
            </div>
          ';

        }
      }
      else{
        echo "<div class='col-md-12'>
                <div class='alert alert-primary text-center'>No posts are available</div>
              </div>";
      }

  } catch(PDOException $e) {
      echo $e->getMessage();
  }
}


function fetch_user_posts_all($db, $user){

  try {
      $stmt = $db->prepare('SELECT * FROM posts WHERE post_author = :post_author ORDER BY post_date DESC');
      $stmt->execute(array(
        ':post_author' => $user
      ));

      $post_count = $stmt->rowCount();
      if ($post_count > 0) {
        while($row = $stmt->fetch()){

          echo '
            <div class="col-sm-2">
                <a class="lightbox" href="tmp/'.$row['post_media'].'" data-imtitle="'.$row['post_content'].'" data-post="'.$row['post_id'].'" data-toggle="lightbox" data-gallery="gallery" data-target="#modal">
                    <img class="img-responsive thumb" style="max-height:350px;" src="tmp/'.$row['post_media'].'" alt="Bridge">
                </a>
            </div>
          ';

        }
      }
      else{
        echo "<hr/>No posts are available";
      }

  } catch(PDOException $e) {
      echo $e->getMessage();
  }
}


function new_post($db, $auther, $content, $media, $privacy){
  try {
    
    $stmt = $db->prepare('INSERT INTO posts (post_author, post_content, post_media, post_privacy, post_date) VALUES (:post_author, :post_content, :post_media, :post_privacy, :post_date)') ;
    $stmt->execute(array(
      ':post_author' => strip_tags($auther),
      ':post_content' => strip_tags($content),
      ':post_media' => strip_tags($media),
      ':post_privacy' => strip_tags($privacy),
      ':post_date' => date('Y-m-d H:i:s')
    ));

    header('Location: index.php');
    exit;

  } catch(PDOException $e) {
      echo $e->getMessage();
  }  
}


function new_comment($db, $post, $commented, $comment){
  try {
    
    $stmt = $db->prepare('INSERT INTO post_comments (post_id, comment_date, comment, commented_by) VALUES (:post_id, :comment_date, :comment, :commented_by)') ;
    $stmt->execute(array(
      ':post_id' => strip_tags($post),
      ':comment_date' => date('Y-m-d H:i:s'),
      ':comment' => strip_tags($comment),
      ':commented_by' => strip_tags($commented)
    ));

  } catch(PDOException $e) {
      echo $e->getMessage();
  }  
}


function fetch_comments($db, $post){

  try {
      $stmt = $db->prepare('SELECT * FROM post_comments WHERE post_id = :post_id ORDER BY comment_date DESC');
      $stmt->execute(array(
        ':post_id' => $post
      ));

      $post_count = $stmt->rowCount();
      if ($post_count > 0) {
        while($row = $stmt->fetch()){

          echo '

          <div class="card">
            <div class="card-body">
              '.$row['comment'].'<br/>
            </div>
            <div class="card-footer">
              <small>Commented by <b>'.user_details_name($db, $row['commented_by']).'</b></small>
              <i class="fa fa-heart-o fa-sm text-secondary likeBtn" data-post="'.$row['post_id'].'"></i></a>
            </div>
          </div><br/>

          ';
        
        }
      }
      else{
        echo "<div class='alert alert-primary'>No comments</div>";
      }

  } catch(PDOException $e) {
      echo $e->getMessage();
  }
}


function change_dp($db, $user, $avatar_url){
  try {
    $stmt = $db->prepare('UPDATE users SET avatar = :avatar WHERE username = :username') ;
    $stmt->execute(array(
      ':avatar' => $avatar_url,
      ':username' => $user
    ));

    echo $user.'<br/>';
    echo $avatar_url;

  } catch(PDOException $e) {
      echo $e->getMessage();
  }
}


function user_details_name($db, $user){
  try {
        $stmt = $db->prepare('SELECT * FROM users WHERE username = :username');
        $stmt->execute(array(
        ':username' => $user
      ));
        $user_count = $stmt->rowCount();
        if ($user_count > 0) {
          while($row = $stmt->fetch()){
            
            return $row['firstname'].' '.$row['lastname'];

          }
        }
        else{
          echo "<hr/>No users";
        }

    } catch(PDOException $e) {
        echo $e->getMessage();
    }
}


function user_firstname($db, $user){
  try {
        $stmt = $db->prepare('SELECT * FROM users WHERE username = :username');
        $stmt->execute(array(
        ':username' => $user
      ));
        $user_count = $stmt->rowCount();
        $row = $stmt->fetch();
        
        if ($user_count > 0) {
          return $row['firstname'];
        }
        else{
          echo "<hr/>No users";
        }

    } catch(PDOException $e) {
        echo $e->getMessage();
    }
}


function user_lastname($db, $user){
  try {
        $stmt = $db->prepare('SELECT * FROM users WHERE username = :username');
        $stmt->execute(array(
        ':username' => $user
      ));
        $user_count = $stmt->rowCount();
        $row = $stmt->fetch();
        
        if ($user_count > 0) {
          return $row['lastname'];
        }
        else{
          echo "<hr/>No users";
        }

    } catch(PDOException $e) {
        echo $e->getMessage();
    }
}



function user_email($db, $user){
  try {
        $stmt = $db->prepare('SELECT * FROM users WHERE username = :username');
        $stmt->execute(array(
        ':username' => $user
      ));
        $user_count = $stmt->rowCount();
        $row = $stmt->fetch();
        
        if ($user_count > 0) {
          return $row['email'];
        }
        else{
          echo "<hr/>No users";
        }

    } catch(PDOException $e) {
        echo $e->getMessage();
    }
}


function username(){
  if (isset($_SESSION['user'])) {
    return $_SESSION['user'];
  }
  else{
    return 'Guest';
  }
}


function user_avatar($db, $user){
  try {
        $stmt = $db->prepare('SELECT * FROM users WHERE username = :username');
        $stmt->execute(array(
        ':username' => $user
      ));
        $user_count = $stmt->rowCount();
        if ($user_count > 0) {
          while($row = $stmt->fetch()){
            
            return $row['avatar'];

          }
        }
        else{
          echo "<hr/>No users";
        }

    } catch(PDOException $e) {
        echo $e->getMessage();
    }
}



function user_search($db, $user, $search){
  try
  {
    $stmt = $db->prepare('SELECT * FROM users WHERE username LIKE "%":username"%" OR firstname LIKE "%":firstname"%"');
    $stmt->execute(array(
        ':username' => $search,
        ':firstname' => $search
      ));
    $count = $stmt->rowCount();
  
    if ($count > 0) {
      while ($row = $stmt->fetch()) {
        echo '
          
          <br/>
          <div class="card">
            <div class="card-body">

            <table width="100%" border="0">
              <tr>
                <td style="width:50px">
                  <img src="'.user_avatar($db, $row['username']).'" class="img-responsive rounded-circle" width="48px" height="48px" style="margin-right:40px">
                </td>
                <td>
                  <b>
                  <a id="user" data-user="'.$row['username'].'" class="text-primary">'.$row['firstname'].' '.$row['lastname'].'</a>
                  </b>
                <br/>
                <i>@'.$row['username'].'</i>
                </td>
             </tr>
            </table>   
            </div>
          </div>

        ';
      }
    }
    else{
      echo '<br/><div class="card"><div class="card-body">No results found</div></div>';
    }
  }catch(PDOException $e) {
      echo $e->getMessage();
  }
}


function is_liked($db, $user_id, $post_id){
  try
  {
    $stmt = $db->query('SELECT * FROM post_likes WHERE post_id = "'.$post_id.'" AND liked_by = "'.$user_id.'"');
    $count = $stmt->rowCount();
  
    if ($count > 0) {
      return true;
    }
    else{
      return false;
    }
  }catch(PDOException $e) {
      echo $e->getMessage();
  }

}

function like_post($db, $user_id, $post_id){
  try
  {
    $stmt = $db->query('SELECT * FROM post_likes WHERE post_id = "'.$post_id.'" AND liked_by = "'.$user_id.'"');
    $count = $stmt->rowCount();
    echo $count;
  
    if ($count > 0) {
      $stmt = $db->prepare('DELETE FROM post_likes WHERE post_id = :post_id AND liked_by = :user_id');
      $stmt->execute(array(
        ':post_id' => $post_id,
        ':user_id' => $user_id
      ));
    }
    else{
      $stmt = $db->prepare('INSERT INTO post_likes (post_id, liked_by) VALUES (:post_id, :liked_by)');
      $stmt->execute(array(
        ':post_id' => $post_id,
        ':liked_by' => $user_id,
      ));
      echo 'liked';
    }

  }catch(PDOException $e) {
      echo $e->getMessage();
  }

}



function is_following($db, $user, $follower){
  try
  {
    $stmt = $db->prepare('SELECT * FROM followers WHERE user_name = :username AND follower_name = :follower');
    $stmt->execute(array(
      ':username' => $user,
      ':follower' => $follower
    ));
    $count = $stmt->rowCount();
  
    if ($count > 0) {
      return true;
    }
    else{
      return false;
    }
  }catch(PDOException $e) {
      echo $e->getMessage();
  }
}


function follow_user($db, $user, $follower){
  try
  {
    $stmt = $db->prepare('SELECT * FROM followers WHERE user_name = :username AND follower_name = :follower');
    $stmt->execute(array(
      ':username' => $user,
      ':follower' => $follower
    ));
    $count = $stmt->rowCount();
    echo $count;
  
    if ($count > 0) {
      $stmt = $db->prepare('DELETE FROM followers WHERE user_name = :user AND follower_name = :follower_name');
      $stmt->execute(array(
        ':user' => $user,
        ':follower_name' => $follower
      ));
      echo 'Unfollowed';
    }
    else{
      $stmt = $db->prepare('INSERT INTO followers (user_name, follower_name) VALUES (:user_name, :follower_name)');
      $stmt->execute(array(
        ':user_name' => $user,
        ':follower_name' => $follower
      ));
      echo 'Followed';
    }

  }catch(PDOException $e) {
      echo $e->getMessage();
  }

}


function user_followers($db, $user){
  try
  {
    $stmt = $db->prepare('SELECT * FROM followers WHERE follower_name = :username');
    $stmt->execute(array(
      ':username' => $user
    ));
    $count = $stmt->rowCount();
  
    if ($count > 0) {
      while ($row = $stmt->fetch()) {
        echo '
          <div class="card">
            <div class="card-body">
            <table width="100%" border="0">
              <tr>
                <td style="width:50px">
                  <img src="'.user_avatar($db, $row['user_name']).'" class="img-responsive rounded-circle" width="48px" height="48px" style="margin-right:40px">
                </td>
                <td>
                  <b>
                  <a id="user" data-user="'.$row['user_name'].'" class="text-primary">'.user_details_name($db, $row['user_name']).'</a>
                  </b>
                <br/>
                <i>@'.$row['user_name'].'</i>
                </td>';


        if (is_following($db, $row['follower_name'], $row['user_name'])) {
          echo '<td style="text-align:right; margin-right:10px"><a class="text-primary" id="followBtnFollower" data-user="'.$row['user_name'].'"><b>Unfollow</b></a></td>';
        }else{
          echo '<td style="text-align:right; margin-right:10px"><a class="text-primary" id="followBtnFollower" data-user="'.$row['user_name'].'"><b>Follow Back</b></a></td>';
        }

        echo '
             </tr>
            </table>
            </div>
          </div>
          ';
      };
    }
    else{
      echo "You don't have any followers right now";
    }
  }catch(PDOException $e) {
      echo $e->getMessage();
  }
}


function user_following($db, $user){
  try
  {
    $stmt = $db->prepare('SELECT * FROM followers WHERE user_name = :username');
    $stmt->execute(array(
      ':username' => $user
    ));
    $count = $stmt->rowCount();
  
    if ($count > 0) {
      while ($row = $stmt->fetch()) {
        echo '
          <div class="card">
            <div class="card-body">
            <table width="100%" border="0">
              <tr>
                <td style="width:50px">
                  <img src="'.user_avatar($db, $row['follower_name']).'" class="img-responsive rounded-circle" width="48px" height="48px" style="margin-right:40px">
                </td>
                <td>
                  <b>
                  <a id="user" data-user="'.$row['follower_name'].'" class="text-primary">'.user_details_name($db, $row['follower_name']).'</a>
                  </b>
                <br/>
                <i>@'.$row['follower_name'].'</i>
                </td>
                <td style="text-align:right; margin-right:10px"><a class="text-primary" id="followBtnFollowing" data-user="'.$row['follower_name'].'"><b>Unfollow</b></a></td>
              </tr>
            </table>
            </div>
          </div>
          ';
      };
    }
    else{
      echo "You are not following anyone right now";
    }
  }catch(PDOException $e) {
      echo $e->getMessage();
  }
}


function user_follower_count($db, $follower){
  try
  {
    $stmt = $db->prepare('SELECT * FROM followers WHERE follower_name = :follower');
    $stmt->execute(array(
      ':follower' => $follower
    ));
    $count = $stmt->rowCount();
  
    return $count;

  }catch(PDOException $e) {
      echo $e->getMessage();
  }
}


function user_following_count($db, $following){
  try
  {
    $stmt = $db->prepare('SELECT * FROM followers WHERE user_name = :follower');
    $stmt->execute(array(
      ':follower' => $following
    ));
    $count = $stmt->rowCount();
  
    return $count;

  }catch(PDOException $e) {
      echo $e->getMessage();
  }
}


function user_posts_count($db, $user){
  try
  {
    $stmt = $db->prepare('SELECT * FROM posts WHERE post_author = :user');
    $stmt->execute(array(
      ':user' => $user
    ));
    $count = $stmt->rowCount();
  
    return $count;

  }catch(PDOException $e) {
      echo $e->getMessage();
  }
}


function post_like_count($db, $post){
  try
  {
    $stmt = $db->prepare('SELECT * FROM post_likes WHERE post_id = :post');
    $stmt->execute(array(
      ':post' => $post
    ));
    $count = $stmt->rowCount();
  
    return $count;

  }catch(PDOException $e) {
      echo $e->getMessage();
  }
}


function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}

?>