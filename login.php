<?php
include 'env.php';
error_reporting(0);
session_start();
$server = "localhost";
$username = $user_;
$password = $passwd_;
$db = $db_;

try {
    $connect = new PDO("mysql:host=$server", $username, $password);
    $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sqlIsDBExist = "SHOW DATABASES LIKE '$db'";

    $isExist = $connect->query($sqlIsDBExist);
    $row = $isExist->fetch();
    if ($row > 0) {
        $connect = null;
        $connect = new PDO("mysql:host=$server;dbname=$db", $username, $password);
        $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        if ($_POST['loginBtn']) {
            $user = $_POST['user_name'];
            $pass = md5($_POST['password']);
            $sql = "SELECT user_name, name, password, user_id FROM users WHERE user_name='$user' and password='$pass'";
            $result = $connect->query($sql);
            $row = $result->fetch();
            if ($row['user_name'] != "") {
                $_SESSION['user_name'] = $row['user_name'];
                $_SESSION['user_id'] = $row['user_id'];
                header('Location:index.php');
            } else {
                $message = "Hatalı Giriş";
            }
        }
    } else {
        echo "$db diye bir DB yok!!";
    }
} catch (PDOException $ex) {
    print "Connection failed" . $ex->getMessage();
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/style.css?version=2">
    <script src="placeConditions.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.rawgit.com/ricmoo/aes-js/e27b99df/index.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.2/rollups/aes.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Indicators</title>
</head>

<body>
    <div id="loginForm" class="loginContainer">
        <div class="halfWidth">
            <img class="fullWidth" src="assets/chart.jpg" alt="login" />
        </div>
        <div class="halfWidth centeredContainer">
            <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" class="formContainer">
                <table>
                    <tr>
                        <td style="color:red" colspan="3"><?php echo $message ?></td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <input type="text" name="user_name" class="formInput" placeholder="Kullanıcı Adı">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <input type="password" name="password" class="formInput" placeholder="Şifre">
                        </td>
                    </tr>
                    <tr>
                        <td><input type="submit" name="loginBtn" class='greenBtn formButton' value="Giriş Yap"></td>
                        <td><a class='blueBtn formButton' href="register.php">Üye Ol</a></td>
                        <td><a class='blueBtn formButton' href="/phpmyadmin">DB</a></td>
                    </tr>
                </table>
            </form>
        </div>
    </div>

    <script>

    </script>
</body>

</html>