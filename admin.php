<?php
session_start();
require('include/connectdb.php');
$user_id = $_SESSION['user_id']??'';

if (!empty($_POST)){
    $is_ban = explode('*', $_POST['act']);
    $comment_id = $is_ban['0'];
    $act = $is_ban['1'];
    if ($act === 'ban')
    {
        $query = "UPDATE comments SET visible = 0 WHERE id = '{$comment_id}'";
    }elseif($act === 'no_ban')
    {
        $query = "UPDATE comments SET visible = 1 WHERE id = '{$comment_id}'";
    }elseif ($act === 'delete'){
        $query = "DELETE FROM comments WHERE id = '{$comment_id}'";
    }
    $query = mysqli_query($connect, $query);
    var_dump($is_ban);
    var_dump($act);
}
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
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
<?php
    $guest = '<li class="nav-item">
                <a class="nav-link" href="login.php">Login</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="register.php">Register</a>
            </li>';
if (isset($_SESSION['user_id'])){
    $query_name = mysqli_query($connect, "SELECT name FROM users WHERE id = '{$user_id}'");
    $result = mysqli_fetch_assoc($query_name);

    $user = '<li class="nav-item">
                <a class="nav-link" href="profile.php">' . $result['name'] . '</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="log_out.php">Log out</a>
            </li>';
    echo $user;
}else{
    echo $guest;
}
?>
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header"><h3>Админ панель</h3></div>

                            <div class="card-body">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Аватар</th>
                                            <th>Имя</th>
                                            <th>Дата</th>
                                            <th>Комментарий</th>
                                            <th>Действия</th>
                                        </tr>
                                    </thead>

                                    <tbody>
<?php
    $query = mysqli_query($connect,"SELECT * FROM comments ORDER BY id DESC");
    while($result = mysqli_fetch_assoc($query)){
        $user_id = $result['user_id'];
        if ($result['user_id'] != NULL){
            $query_user_url_img = mysqli_query($connect, "SELECT img FROM users WHERE id = '{$user_id}'");
            $result_url_img = mysqli_fetch_assoc($query_user_url_img);
            $user_url_img = $result_url_img['img'];
        }else{
            $user_url_img = "img/no-user.jpg";
        }
        if($result['commenter'] == NULL){
            $query_user_name = mysqli_query($connect, "SELECT name FROM users where id = '{$user_id}'");
            $result_user_name = mysqli_fetch_assoc($query_user_name);
            $user_name = $result_user_name['name'];
        }else{
            $user_name = $result['commenter'];
        }
    ?>
                                        <tr>
                                            <td>
                                                <img src="<?php echo $user_url_img; ?>" alt="" class="img-fluid" width="64" height="64">
                                            </td>
                                            <td><?php echo $user_name; ?></td>
                                            <td><?php echo $result['date']; ?></td>
                                            <td><?php echo $result['comments']; ?></td>
                                            <td>
                                                <form action="" method="POST">
<?php
    if ($result['visible'] != TRUE){
                                            echo '<button class="btn btn-success" type="submit" name="act" value="' . $result['id'] . '*no_ban">Разрешить</button>';
    }else{
                                            echo '<button class="btn btn-warning" type="submit" name="act" value="' . $result['id'] . '*ban">Запретить</button>';
}
?>
                                                    <button onclick="return confirm('are you sure?')" class="btn btn-danger" type="submit" name="act" value="<?php echo $result['id'] . '*delete'; ?>">Удалить</button>
                                                </form>
                                            </td>
                                        </tr>
<?php } var_dump($_POST);?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
