<?php 
session_start();

//atur koneksi ke database
$host_db    = "localhost";
$user_db    = "root";
$pass_db    = "";
$nama_db    = "akademik";
$koneksi    = mysqli_connect($host_db,$user_db,$pass_db,$nama_db);
//atur variabel
$err        = "";
$username   = "";
$ingataku   = "";

if(isset($_COOKIE['cookie_username'])){
    $cookie_username = $_COOKIE['cookie_username'];
    $cookie_password = $_COOKIE['cookie_password'];

    $sql1 = "select * from login where username = '$cookie_username'";
    $q1   = mysqli_query($koneksi,$sql1);
    $r1   = mysqli_fetch_array($q1);
    if($r1['password'] == $cookie_password){
        $_SESSION['session_username'] = $cookie_username;
        $_SESSION['session_password'] = $cookie_password;
    }
}

if(isset($_SESSION['session_username'])){
    header("location:index.php");
    exit();
}

if(isset($_POST['login'])){
    $username   = $_POST['username'];
    $password   = $_POST['password'];
    $ingataku   = $_POST['ingataku'];

    if($username == '' or $password == ''){
        $err .= "<li>Silakan masukkan username dan juga password.</li>";
    }else{
        $sql1 = "select * from login where username = '$username'";
        $q1   = mysqli_query($koneksi,$sql1);
        $r1   = mysqli_fetch_array($q1);

        if($r1['username'] == ''){
            $err .= "<li>Username <b>$username</b> tidak tersedia.</li>";
        }elseif($r1['password'] != md5($password)){
            $err .= "<li>Password yang dimasukkan tidak sesuai.</li>";
        }       
        
        if(empty($err)){
            $_SESSION['session_username'] = $username; //server
            $_SESSION['session_password'] = md5($password);

            if($ingataku == 1){
                $cookie_name = "cookie_username";
                $cookie_value = $username;
                $cookie_time = time() + (60 * 60 * 24 * 30);
                setcookie($cookie_name,$cookie_value,$cookie_time,"/");

                $cookie_name = "cookie_password";
                $cookie_value = md5($password);
                $cookie_time = time() + (60 * 60 * 24 * 30);
                setcookie($cookie_name,$cookie_value,$cookie_time,"/");
            }
            header("location:index.php");
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
    <!-- My CSS -->
    <link rel="stylesheet" href="assets/style.css">
</head>
<body class="bodyLogin">
    <div class="container my-5">
        <div class="row d-flex justify-content-center align-items-center">
            <div class="col colLogin">
                <div class="card cardLogin">
                    <div class="card-header">
                    Masuk ke Sistem
                    </div>
                    <div class="card-body">
                        <?php if($err){ ?>
                            <div id="login-alert" class="alert alert-danger col-sm-12">
                                <ul><?php echo $err ?></ul>
                            </div>
                        <?php } ?>      
                        <form id="loginform" class="form-horizontal" action="" method="post" role="form">
                            <div class="mb-3">
                                <input id="login-username" type="text" class="form-control" name="username" value="<?php echo $username ?>" placeholder="username" autofocus>
                            </div>
                            <div class="mb-3">
                                <input id="login-password" type="password" class="form-control" name="password" placeholder="password">
                            </div>                    
                            <div class="mb-3 form-check">
                                <input id="login-remember" type="checkbox" name="ingataku" value="1" <?php if($ingataku == '1') echo "checked"?>> Ingat aku
                            </div>
                            <button type="submit" name="login" class="btn btn-primary" value="Login"/>Login</button>
                         </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>