<?php
include 'env.php';
error_reporting(0);
session_start();
$server = $host_;
$username = $user_;
$password = $passwd_;
$db = $db_;
$user_id = $_SESSION['user_id'];
$user = $_SESSION['user_name'];
$success = null;
$isAll = 'false';

if ($_SESSION['user_id'] == '') {
    header('Location:login.php');
}
try {
    $connect = new PDO("mysql:host=$server", $username, $password);
    $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sqlIsDBExist = "SHOW DATABASES LIKE '$db'";

    $isExist = $connect->query($sqlIsDBExist);
    $row = $isExist->fetch();
    if ($row > 0) {
        $connect = null;
        $connect = new PDO("mysql:host=$server;dbname=$db;charset=utf8", $username, $password);
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
                $user_id = $row['user_id'];
                $success = "Giriş Başarılı";
            } else {
                $message = "Hatalı Giriş";
            }
        }
    } else {
        echo "$db diye bir DB yok!!";
    }
    if($_GET['isAll']){
        $isAll = $_GET['isAll'];
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
    <link rel="stylesheet" href="styles/index.css?version=1">
    <link rel="icon" href="assets/icon.png" />
    <script src="placeStrategy.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.rawgit.com/ricmoo/aes-js/e27b99df/index.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.2/rollups/aes.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Indicators</title>
</head>

<body>
    <div class="logoutCollider">
        <div class="heading">
            <a href="index.php">
                Sinyalci
            </a>
        </div>

        <div class="userText"><?php echo $_SESSION['user_name'] ?></div>
    </div>
    
    <div class="bodyContainer">
        <div class="leftMenuContainer">
            <ul>
                <li>
                    <a class="menuItem menuItemSelected" href="index.php">
                        <i class="fa fa-home"></i>
                        Ana Sayfa
                    </a>
                </li>    
                <li>
                    <a class="menuItem" href="rule.php">
                        <i class="fa fa-plus"></i>
                        Strateji Ekle
                    </a>
                </li>
                <li>
                    <a class="menuItem" href="logout.php">
                        <i class="fa fa-sign-out"></i>
                        Çıkış
                    </a>
                </li>
            </ul>
        </div>
        <div class="tableDiv">
            <div class="horizontalContainer tabContainer">
                <div id="myStrategies" class="tab tabSelected">
                    Benim Stratejilerim
                </div>
                <div id="allStrategies" class="tab">
                    Tüm Stratejiler
                </div>
            </div>

            <div class="tabContainer">
                <div id="condsTab">
                    
                </div>
            </div>
            <div class="paginatorContainer">

            </div>
        </div>
    </div>

    <script>
        var conditions = []
        var perPaginator = 15;
        var user_name = ""
        var activeTab = 0;
        var isAll = false;

        <?php
        $sql = "Select * from strategies";
        $result = $connect->query($sql);
        $conditions = array();
        while ($row = $result->fetch()) {
            array_push($conditions, $row);
        }
        echo "var conditions = " . json_encode($conditions) . ";\n";
        echo "var user_name = '" . $user . "';\n";
        echo "var isAll = " . $isAll . ";\n";
        if ($_SESSION['user_name']) {
            echo "var user_name = '" . $_SESSION['user_name'] . "';\n";
        }
        $connect = null;
        ?>
        if(isAll){
            setAllStrategies();
        }else{
            setMyStrategies();
        }
        setListeners();
    </script>
</body>

</html>