<?php
session_start();
require('include/connectdb.php');
if (isset($_POST['email'])){
    $mail = filter_var(trim($_POST['email']),FILTER_SANITIZE_STRING);
}else{
    $mail = NULL;
}
if (isset($_POST['email'])){
    $pas = md5(filter_var(trim($_POST['password']),FILTER_SANITIZE_STRING));
} else {
    $pas = NULL;
}
if (($mail != '') && ($pas != 'd41d8cd98f00b204e9800998ecf8427e')){
    $query = "SELECT * FROM users WHERE mail = '{$mail}' && pas = '{$pas}'";
    $query = mysqli_query($connect, $query);
    $result = mysqli_fetch_assoc($query);
    if (($result['name'] != NULL) && ($result['id'] != NULL)){
        $_SESSION['user_name'] = $result['name'];
        $_SESSION['user_id'] = $result['id'];
        header('location: /');
    }
}
mysqli_close($connect);
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
                                <a class="nav-link" href="login.php">Login</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="register.php">Register</a>
                            </li>
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">Login</div>

                            <div class="card-body">
                                <form method="POST" action="">

                                    <div class="form-group row">
                                        <label for="email" class="col-md-4 col-form-label text-md-right">E-Mail Address</label>
<?php
$error_class = 'is-invalid';
$error_mail_text = '<span class="invalid-feedback" role="alert">
                    <strong>Введите amail</strong>
                </span>';
$error_pas_text = '<span class="invalid-feedback" role="alert">
                    <strong>Введите пароль</strong>
                </span>';
$error_text = '<span class="invalid-feedback" role="alert">
                    <strong>Ошибка валидации</strong>
                </span>';
?>
                                        <div class="col-md-6">
                                            <input id="email" type="email" class="form-control <?php if (($mail === '') || (($mail != '') && ($pas != 'd41d8cd98f00b204e9800998ecf8427e'))){
                                                echo $error_class; } ?> " name="email"  autocomplete="email" autofocus >
                                            <?php if ($mail === ''){
                                                echo $error_mail_text; }
                                            if (($mail != '') && ($pas != 'd41d8cd98f00b204e9800998ecf8427e')){
                                                echo $error_text ;
                                            }
                                                ?>

                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>

                                        <div class="col-md-6">
                                            <input id="password" type="password" class="form-control
                                                <?php if (($pas == 'd41d8cd98f00b204e9800998ecf8427e') || (($mail != '') && ($pas != 'd41d8cd98f00b204e9800998ecf8427e'))){
                                                    echo $error_class; } ?>
                                                  " name="password"  autocomplete="current-password">
                                            <?php if ($pas === 'd41d8cd98f00b204e9800998ecf8427e') {
                                                echo $error_pas_text; }
                                                 if (($mail != '') && ($pas != 'd41d8cd98f00b204e9800998ecf8427e')){
                                                     echo $error_text ;
                                                 }?>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-6 offset-md-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="remember" id="remember" >

                                                <label class="form-check-label" for="remember">
                                                    Remember Me
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row mb-0">
                                        <div class="col-md-8 offset-md-4">
                                            <button type="submit" class="btn btn-primary">
                                               Login
                                            </button>
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
