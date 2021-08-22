<?php
include 'env.php';
error_reporting(0);
session_start();
$server = $host_;
$username = $user_;
$password = $passwd_;
$db = $db_;
$user_id = $_SESSION['user_id'];
$success = null;
$str_id = $_GET['str_id'];

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
                $user_id = $row['user_id'];
                $success = "Giriş Başarılı";
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
    <link rel="stylesheet" href="styles/index.css?version=1">
    <script src="placeConditions.js"></script>
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
                <a id="myStrategies" class="tab tabSelected" href="index.php?isAll=false">
                    Benim Stratejilerim
                </a>
                <a id="allStrategies" class="tab" href="index.php?isAll=true">
                    Tüm Stratejiler
                </a>
            </div>
            <div class="tableContainer">
                <table id="historyTable" class="fullWidth">
                    <thead>
                        <tr>
                            <th class='cell tableTitle'>Parite</th>
                            <th class='cell tableTitle'>Kâr</th>
                            <th class='cell tableTitle'>En Yüksek</th>
                            <th class='cell tableTitle'>En Düşük</th>
                            <th class='cell tableTitle'>Durum</th>
                            <th class='cell tableTitle'>Giriş Tarihi</th>
                            <th class='cell tableTitle'>Çıkış Tarihi</th>
                        </tr>
                    </thead>
                    <tbody id="condsTab">

                    </tbody>
                </table>
            </div>
            <div class="paginatorContainer">

            </div>
        </div>
    </div>

    <script>
        var trades = []
        var perPaginator = 15;
        var user_name = ""

        <?php
        $sql = "Select * from trades where strategy_id='$str_id'";
        $result = $connect->query($sql);
        $trades = array();
        while ($row = $result->fetch()) {
            array_push($trades, $row);
        }
        echo "var trades = " . json_encode($trades) . ";";
        if ($_SESSION['user_name']) {
            echo "var user_name = '" . $_SESSION['user_name'] . "';\n";
        }
        $connect = null;
        ?>
        placed(trades, perPaginator, 1);
        /*let pass = "Sinyalci575859*";
        let encryptedText = encryptText(pass, "AnApiKey")
        let decryptedText = decryptText(pass, encryptedText)*/
        //for login form submit
        /*document.getElementById("login").onclick = function() {
            document.getElementById("loginForm").submit();
        }*/
    </script>
</body>

</html>