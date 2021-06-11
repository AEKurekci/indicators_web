<?php
session_start();
error_reporting(0);
$server = "localhost";
$username = "root";
$password = "";
$db = "indicator_web";

$name = "";
$surname = "";
$email = "";
$userName = "";
$userPassword = "";
$apiKey = "";
$secretKey = "";
$nameErr = $surnameErr = $emailErr = $userNameErr = $passwordErr = "";
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (empty($_POST['name'])) {
        $nameErr = "Name is required";
    } else {
        $name = cleanProcess($_POST['name']);
        if (!preg_match("/^[a-zA-Z üğÜĞİışŞçÇöÖ]*$/", $name)) {
            $nameErr = "Only characters and space";
        } else {
            $nameErr = "";
        }
    }
    if (empty($_POST['surname'])) {
        $surnameErr = "Surname is required";
    } else {
        $surname = cleanProcess($_POST['surname']);
        if (!preg_match("/^[a-zA-Z üğÜĞİışŞçÇöÖ]*$/", $surname)) {
            $surnameErr = "Only characters and space";
        } else {
            $surnameErr = "";
        }
    }
    if (empty($_POST['email'])) {
        $emailErr = "Email is required";
    } else {
        $email = cleanProcess($_POST['email']);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format";
        } else {
            $emailErr = "";
        }
    }
    if (empty($_POST['userName'])) {
        $userNameErr = "User Name is required";
    } else {
        $userName = cleanProcess($_POST['userName']);
        if (!preg_match("/^[a-zA-Z üğÜĞİışŞçÇöÖ]*$/", $userName)) {
            $userNameErr = "Only characters and space";
        } else {
            $userNameErr = "";
        }
    }
    if (empty($_POST['password'])) {
        $passwordErr = "Password is required";
    } else {
        $userPassword = cleanProcess($_POST['password']);
        $passwordErr = "";
    }
    if (!empty($_POST['api_key']) && !empty($_POST['password'])) {
        $apiKey = cleanProcess($_POST['api_key']);
        $apiKey = encryptText($apiKey, $userPassword);
        $apiKey = bin2hex($apiKey); //pack("H*", bin2hex($apiKey))
        //$apiKey = decryptText($apiKey, $userPassword);
    }
    if (!empty($_POST['secret_key']) && !empty($_POST['password'])) {
        $secretKey = cleanProcess($_POST['secret_key']);
        $secretKey = encryptText($secretKey, $userPassword);
        $secretKey = bin2hex($secretKey);
        //$secretKey = decryptText($secretKey, $userPassword);
    }
}
try {
    $connect = new PDO("mysql:host=$server;dbname=$db", $username, $password);
    $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    if ($_SERVER['REQUEST_METHOD'] == "POST" && $nameErr == "" && $surnameErr == "" && $emailErr == "" && $userNameErr == "" && $passwordErr == "") {
        if (isUserExist($connect, $userName)) {
            $passwordMD5 = md5($userPassword);
            $uid = uniqid();
            $sqlRegister = "INSERT INTO users (user_id, name, surname, email, user_name, password, api_key, secret_key) VALUES
                    ('$uid', '$name','$surname','$email','$userName','$passwordMD5','$apiKey','$secretKey')";
            $connect->exec($sqlRegister);

            echo "<script type='text/javascript'>alert('Kayıt başarılı');</script>";

            $name = "";
            $surname = "";
            $email = "";
            $userName = "";
            $userPassword = "";
            $userNameErr = "";
            $apiKey = "";
            $secretKey = "";
            header("Location:index.php");
        } else {
            $userNameErr = "Kullanıcı adı kullanılıyor!";
        }
    }
} catch (PDOException $ex) {
    echo $ex;
}

function cleanProcess($input)
{
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input);
    return $input;
}

function isUserExist($connection, $user)
{
    $sqlUsername = "SELECT user_name FROM users WHERE user_name='$user'";
    $resultOfUserName = $connection->query($sqlUsername);
    $row = $resultOfUserName->fetch();
    return $row[0] == "";
}
function encryptText($plaintext, $password)
{
    $method = "AES-256-CBC";
    $key = hash('sha256', $password, true);
    $iv = openssl_random_pseudo_bytes(16);

    $ciphertext = openssl_encrypt($plaintext, $method, $key, OPENSSL_RAW_DATA, $iv);
    $hash = hash_hmac('sha256', $ciphertext . $iv, $key, true);

    return $iv . $hash . $ciphertext;
}

function decryptText($ivHashCiphertext, $password)
{
    $method = "AES-256-CBC";
    $iv = substr($ivHashCiphertext, 0, 16);
    $hash = substr($ivHashCiphertext, 16, 32);
    $ciphertext = substr($ivHashCiphertext, 48);
    $key = hash('sha256', $password, true);

    if (!hash_equals(hash_hmac('sha256', $ciphertext . $iv, $key, true), $hash)) return null;

    return openssl_decrypt($ciphertext, $method, $key, OPENSSL_RAW_DATA, $iv);
}
$connect = null;

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/style.css">
    <script src="placeConditions.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.rawgit.com/ricmoo/aes-js/e27b99df/index.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.2/rollups/aes.js"></script>
    <title>Indicators</title>
</head>

<body>
    <nav class='text'>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>">
            <ul class="register">
                <li><input type="text" placeholder="Ad" name="name" value="<?php echo $name ?>" /><span class="star">*<?php echo $nameErr ?></span></li>
                <li><input type="text" placeholder="Soyad" name="surname" value="<?php echo $surname ?>" /><span class="star">*<?php echo $surnameErr ?></span></li>
                <li><input type="email" placeholder="Email" name="email" value="<?php echo $email ?>" /><span class="star">*<?php echo $emailErr ?></span></li>
                <li><input type="text" placeholder="Kullanıcı Adı" name="userName" value="<?php echo $userName ?>" /><span class="star">* <?php echo $userNameErr ?></span></li>
                <li><input type="password" placeholder="Şifre" name="password" /><span class="star">* <?php echo $passwordErr ?></span></li>
                <li><input type="text" placeholder="Api Key" name="api_key" value="<?php echo $apiKey ?>" /></li>
                <li><input type="text" placeholder="Secret Key" name="secret_key" value="<?php echo $secretKey ?>" /></li>
                <li><button type="submit">Kaydol</button></li>
            </ul>
        </form>
    </nav>

    <script>
        let pass = "Sinyalci575859*";
        let encryptedText = encryptText(pass, "AnApiKey")
        console.log(encryptedText);
        let decryptedText = decryptText(pass, encryptedText)
        console.log(decryptedText);
    </script>
</body>

</html>