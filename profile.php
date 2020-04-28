<?php
session_start();
require('include/connectdb.php');
$user_id = $_SESSION['user_id'];
$name = $_POST['name'] ?? '';
$mail = $_POST['email'] ?? '';

$name = filter_var(trim($name),FILTER_SANITIZE_STRING);
$mail = filter_var(trim($mail),FILTER_SANITIZE_STRING);

if (!empty($name)){
    $query = mysqli_query($connect, "UPDATE users SET name = '{$name}' WHERE id = '{$user_id}'");
    $query_success = TRUE;
}
if (!empty($mail)){
    $query = mysqli_query($connect, "UPDATE users SET mail = '{$mail}' WHERE id = '{$user_id}'");
    $query_success = TRUE;
}
if(!empty($_FILES['image']['size'])){
    $img_name = $_FILES['image']['name'];
    $img_tmp_name = $_FILES['image']['tmp_name'];
    $img_type = $_FILES['image']['type'];
    $img_type = substr($img_type, 6);
    $url_img = 'img/'. $user_id . '.' . $img_type;
    if (($img_type == 'jpg') || ($img_type == 'jpeg') || ($img_type == 'png')){
        $query = mysqli_query($connect, "UPDATE users SET img = '{$url_img}' WHERE id = '{$user_id}'");
        move_uploaded_file($img_tmp_name, $url_img);
        $query_success = TRUE;
    }else{
        $img_type_error = TRUE;
    }
}

$img_query = mysqli_query($connect, "SELECT img FROM users WHERE id = '{$user_id}'");
$img_query_result = mysqli_fetch_assoc($img_query);
if ($img_query_result['img'] != NULL){
    $url_img = $img_query_result['img'];
}else{
    $url_img = 'img/no-user.jpg';
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
                        <li class="nav-item">
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
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
          <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header"><h3>Профиль пользователя</h3></div>

                        <div class="card-body">
<?php
$text = '<div class="alert alert-success" role="alert">
        Профиль успешно обновлен
      </div>';
$img_type_error_text = '<div class="alert alert-danger" role="alert">
             Не допустимый формат изображения
        </div>';
    if(isset($query_success)){
        echo $text;
    }
    if(isset($img_type_error)){
        echo $img_type_error_text;
    }
?>
                            <form action="" method="post" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="exampleFormControlInput1">Name</label>
                                            <input type="text" class="form-control" name="name" id="exampleFormControlInput1">
                                           
                                        </div>

                                        <div class="form-group">
                                            <label for="exampleFormControlInput1">Email</label>
                                            <input type="email" class="form-control " name="email" id="exampleFormControlInput1">
                                        </div>

                                        <div class="form-group">
                                            <label for="exampleFormControlInput1">Аватар</label>
                                            <input type="file" class="form-control" name="image" id="exampleFormControlInput1">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <img src="<?php echo $url_img; ?>" alt="" class="img-fluid">
                                    </div>

                                    <div class="col-md-12">
                                        <button class="btn btn-warning">Edit profile</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-md-12" style="margin-top: 20px;">
                    <div class="card">
                        <div class="card-header"><h3>Безопасность</h3></div>

                        <div class="card-body">
<?php
$message_pas_ok = '<div class="alert alert-success" role="alert">
                    Пароль успешно обновлен
                </div>';
$message_pas_error = '<div class="alert alert-danger" role="alert">
                        Неверный пароль
                    </div>';
$message_pas_duble_error = '<div class="alert alert-danger" role="alert">
                            Новый пароль не совпадает
                        </div>';
if (empty($_SESSION['pas'])){
    $_SESSION['pas'] = '';
}
if (empty($_SESSION['pas_duble'])){
    $_SESSION['pas_duble'] = '';
}
if ($_SESSION['pas'] == 'ok'){
    echo $message_pas_ok;
}
if ($_SESSION['pas'] == 'error'){
    echo $message_pas_error;
}
if ($_SESSION['pas_duble'] == 'error_duble'){
    echo $message_pas_duble_error;
}
?>
                            <form action="/include/password.php" method="post">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="exampleFormControlInput1">Current password</label>
                                            <input type="password" name="current" class="form-control" id="exampleFormControlInput1">
                                        </div>

                                        <div class="form-group">
                                            <label for="exampleFormControlInput1">New password</label>
                                            <input type="password" name="password" class="form-control" id="exampleFormControlInput1">
                                        </div>

                                        <div class="form-group">
                                            <label for="exampleFormControlInput1">Password confirmation</label>
                                            <input type="password" name="password_confirmation" class="form-control" id="exampleFormControlInput1">
                                        </div>

                                        <button class="btn btn-success">Submit</button>
                                    </div>
                                </div>
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
$_SESSION['pas'] = '';
$_SESSION['pas_duble'] = '';
mysqli_close($connect);
?>