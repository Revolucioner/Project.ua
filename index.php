<?php
session_start();
require('include/connectdb.php');
$user_id = $_SESSION['user_id']??'';
if (empty($_SESSION['comment'])){
    $_SESSION['comment'] = 0;
}
var_dump($_SESSION);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Comments</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="css/app.css" rel="stylesheet">
</head>
<body>
<div id="app">
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                Project
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav mr-auto">

                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ml-auto">
                    <!-- Authentication Links -->
                    <li class="nav-item">
<?php
    if (isset($_SESSION['user_id'])){
        if ($_SESSION['user_id'] == 1){
            echo '<li class="nav-item">
            <a class="nav-link" href="admin.php">Admin panel</a>
           </li>';
        }
    }
    if (isset($_SESSION['user_id'])){?>
                        <a class="nav-link" href="profile.php">
<?php
    $query_name = mysqli_query($connect, "SELECT name FROM users WHERE id = '{$user_id}'");
    $result = mysqli_fetch_assoc($query_name);
    echo $result['name'];
?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="log_out.php">Log out</a>
                    </li>
<?php  }else{ ?>
                        <a class="nav-link" href="login.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="register.php">Register</a>
<?php } ?>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="py-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header"><h3>Комментарии</h3></div>

                        <div class="card-body">
<?php
$comment_ok_text = '<div class="alert alert-success" role="alert">
                    Комментарий успешно добавлен
                   </div>';
$comment_error_text = '<div class="alert alert-danger" role="alert">
                    Ошибка добавления комментария
                       </div>';
    if ($_SESSION['comment'] === 'ok'){
        echo $comment_ok_text;
        $_SESSION['comment'] = 0;
     }elseif ($_SESSION['comment'] === 'error'){
        echo $comment_error_text;
        $_SESSION['comment'] = 0;
    }
    $query = mysqli_query($connect,'SELECT * FROM comments WHERE visible = 1 ORDER BY id DESC');
    while($result = mysqli_fetch_assoc($query)){?>
                            <div class="media">
                                <img src="
<?php
    $commenter_user_id = $result['user_id'];
    $img_query = mysqli_query($connect, "SELECT img FROM users WHERE id = '{$commenter_user_id}'");
    $img_query_result = mysqli_fetch_assoc($img_query);
    if ($img_query_result['img'] != NULL){
        echo $img_query_result['img'];
    }else{
        echo 'img/no-user.jpg';
    }
?>
                                " class="mr-3" alt="..." width="64" height="64">
                                <div class="media-body">
                                    <h5 class="mt-0">
<?php
    if ($result['commenter'] == NULL){
        $name_query = mysqli_query($connect, "SELECT name FROM users WHERE id = '{$commenter_user_id}'");
        $name_query_result = mysqli_fetch_assoc($name_query);
        echo $name_query_result['name'];
    }else{
        echo $result['commenter'];
    }
?>
                                    </h5>
                                    <span><small><?php echo $result['date']; ?></small></span>
                                    <p><?php echo $result['comments']; ?></p>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>

                <div class="col-md-12" style="margin-top: 20px;">
                    <div class="card">
                        <div class="card-header"><h3>Оставить комментарий</h3></div>

                        <div class="card-body">
                            <form action="/store.php" method="post">
<?php
    if (!isset($_SESSION['user_id'])){ ?>
                                  <div class="form-group">
                                    <label for="exampleFormControlTextarea1">Имя</label>
                                    <input name="name" class="form-control" id="exampleFormControlTextarea1"/>
                                </div>
<?php } ?>
                                <div class="form-group">
                                    <label for="exampleFormControlTextarea1">Сообщение</label>
                                    <textarea name="text" class="form-control" id="exampleFormControlTextarea1"
                                              rows="3"></textarea>
                                </div>
                                <button type="submit" class="btn btn-success">Отправить</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
</body>
</html>
<?php
mysqli_close($connect);
?>