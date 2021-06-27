<?php
include 'env.php';
error_reporting(0);
session_start();
$server = "localhost";
$username = $user_;
$password = $passwd_;
$db = $db_;
$user_id = $_SESSION['user_id'];
$success = null;

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
    <script src="placeConditions.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.rawgit.com/ricmoo/aes-js/e27b99df/index.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.2/rollups/aes.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Indicators</title>
</head>

<body>
    <div id="loginForm">
        <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>">
            <table>
                <tr>
                    <td><input type="text" name="user_name" class="formInput" placeholder="Kullanıcı Adı"></td>
                    <td><input type="password" name="password" class="formInput" placeholder="Şifre"></td>
                    <td><input type="submit" name="loginBtn" class='greenBtn formButton' value="Giriş Yap"></td>
                    <td><a class='blueBtn formButton' href="register.php">Üye Ol</a></td>
                    <td style="color:red"><?php echo $message ?></td>
                    <td id="successMess" style="color:rgb(60, 216, 81)"><?php echo $success ?></td>
		    <td><a class='blueBtn formButton' href="/phpmyadmin">DB</a></td>
                </tr>
            </table>
        </form>
    </div>
    <div class="logoutCollider">
        <div class="userText"><?php echo $_SESSION['user_name'] ?></div>
        <a class="formButton blueBtn" href="rule.php">Kural Ekle</a>
        <a class="formButton outBtn" href="logout.php">Çıkış</a>
    </div>
    <div class="tableDiv">
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
                    </tr>
                </thead>
                <tbody id="condsTab">

                </tbody>
            </table>
        </div>
        <div class="paginatorContainer">

        </div>
    </div>
    <script>
        var conditions = []
        var perPaginator = 15;
        var user_name = ""

        <?php
        $sql = "Select * from conditions where user_id='$user_id'";
        $result = $connect->query($sql);
        $conditions = array();
        while ($row = $result->fetch()) {
            array_push($conditions, $row);
        }
        echo "var conditions = " . json_encode($conditions) . ";";
        if ($_SESSION['user_name']) {
            echo "var user_name = '" . $_SESSION['user_name'] . "';\n";
        }
        $connect = null;
        ?>
        if (user_name == "") {
            //eğer giriş yapılmamışsa
            setDisableness("#historyTable", true);
            setDisableness("#loginForm", false);
            $('#historyTable').hide();
            $('#loginForm').show();
            $('.logoutCollider').hide();
            $('.paginatorContainer').hide();
        } else {
            $('#historyTable').show();
            $('#loginForm').hide();
            $('.logoutCollider').show();
            $('.paginatorContainer').show();
            placed(conditions, perPaginator, 1);
            disseppearContent('#successMess', 2000);
        }
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
