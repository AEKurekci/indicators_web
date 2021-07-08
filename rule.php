<?php
include 'env.php';
error_reporting(0);
session_start();
$server = "localhost";
$username = $user_;
$password = $passwd_;
$db = $db_;

try {
    $connect = new PDO("mysql:host=$server;dbname=$db", $username, $password);
    $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $sql_md5 = "Select id from ind_conditions";
        $md5_result = $connect->query($sql_md5);
        $md5_rows = $md5_result->fetchAll(PDO::FETCH_COLUMN);

        $bigAndOr = $_POST['bigAndOr'];
        $sellBuy = $_POST['sellBuy'];
        $period1 = $_POST['period1'];
        $ind1 = $_POST['ind1'];
        $ind2 = $_POST['ind2'];
        $val2 = $_POST['val2'];
        $comperator = $_POST['comperator'];
        $input_type = $_POST['input_type'];
        $max_val = $_POST['max_val'];
        $min_val = $_POST['min_val'];
        $error = "";

        foreach ($period1 as $i => $p1) {
            foreach ($p1 as $j => $per) {
                print $val2[$i][$j];
                $ind1_type = "indicator";
                $ind2_type = "indicator";
                $value3 = "";
                if (in_array($ind1[$i][$j], array("open", "high", "low", "close")) == true) {
                    $ind1_type = "candle";
                }
                if ($input_type[$i][$j] == 'rad_ind' && in_array($ind2[$i][$j], array("open", "high", "low", "close")) == true && $comperator[$i][$j] != 'arasında') {
                    $ind2_type = "candle";
                } else if ($input_type[$i][$j] == 'rad_val') {
                    $ind2_type = "value";
                    $ind2[$i][$j] = $val2[$i][$j];
                }
                if ($comperator[$i][$j] == 'arasında') {
                    if ($min_val[$i][$j] == '' || $max_val[$i][$j] == '') {
                        $error = "Tüm değerlerin doğru girildiğininden emin olun!";
                        break;
                    }
                    $ind2[$i][$j] = $min_val[$i][$j];
                    $value3 = $max_val[$i][$j];
                    $ind2_type = 'value';
                }
                echo "<br>";
                $text_for_md5 = $ind1[$i][$j] . '.' . $ind1_type . '.' . $ind2[$i][$j] . '.' . $ind2_type . '.' . $value3 . '.' . $comperator[$i][$j] . '.' . $period1[$i][$j];
                print $text_for_md5;
                $cond_md = md5($text_for_md5);
                echo "<br>";
                print $cond_md;
                if (in_array($cond_md, $md5_rows) == false) {
                    add_db($cond_md, $ind1[$i][$j], $ind1_type, $ind2[$i][$j], $ind2_type, $value3, $comperator[$i][$j], $period1[$i][$j], $connect);
                }
            }
        }
    }
} catch (PDOException $ex) {
    print "Connection failed" . $ex->getMessage();
}

function add_db($md_val, $ind1, $ind1_type, $ind2, $ind2_type, $val3, $oper, $per, $conn)
{
    echo "<br>";
    print "writing db";
    $sqCond = "INSERT INTO ind_conditions (id, ind1, first_column_type, ind2, second_column_type, value3, operator, period) VALUES
    ('$md_val', '$ind1','$ind1_type','$ind2','$ind2_type','$val3','$oper','$per')";
    $conn->exec($sqCond);

    echo "<script type='text/javascript'>alert('Kayıt başarılı');</script>";
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/style.css?version=2">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="placeConditions.js"></script>
    <script src="ruleController.js"></script>
    <script src="dropdown.js"></script>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
    <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css">

    <script type="text/javascript" src="https://cdn.rawgit.com/ricmoo/aes-js/e27b99df/index.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.2/rollups/aes.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Indicators</title>
</head>

<body class="ruleBody">
    <div class="logoutCollider">
        <div class="userText"><?php echo $_SESSION['user_name'] ?></div>
        <a class="formButton greenBtn" href="index.php">Geri</a>
        <a class="formButton outBtn" href="logout.php">Çıkış</a>
    </div>
    <form id="form" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" class="ruleContainer topMargin">
        <div class="row-space-between">
            <select class="dropDown halfWidth" name="bigAndOr">
                <option value="and">
                    VE
                </option>
                <option value="or">
                    VEYE
                </option>
            </select>

            <select class="dropDown halfWidth" name="sellBuy">
                <option value="sell">
                    SAT
                </option>
                <option value="buy">
                    AL
                </option>
            </select>
        </div>

        <div id="str-container">
            <div id="strategy-0" class="ruleGroupBoxContainer col-start strategy">
                <div class="regularFont blackText">- Kural Grubu</div>

                <div id="ruleGroup">
                    <div class="bigTopMargin row-space-between">
                        <select id="andOr" class="dropDown halfWidth" name="andOr[0][]">
                            <option value="and">
                                VE
                            </option>
                            <option value="or">
                                VEYE
                            </option>
                        </select>

                        <div class="row-end">
                            <a id="addBtn" class="formButton blueBtn">
                                <i class="fa fa-plus"></i>
                            </a>
                            <a id="copyBtn" class="formButton blueBtn">
                                <i class="fa fa-copy"></i>
                            </a>
                            <a id="strRemoveBtn" class="formButton outBtn">
                                <i class="fa fa-trash"></i>
                            </a>
                        </div>
                    </div>

                    <div id="rule-0" class="rule row-space-between ruleGroupBoxContainer topMargin blueBackground">
                        <div class="flex2 col-start ">
                            <select name="period1[0][]" id="period1" class="dropDown fullWidth">
                                <option value="15m">
                                    15 Dakika
                                </option>
                                <option value="1h">
                                    1 Saat
                                </option>
                                <option value="4h">
                                    4 Saat
                                </option>
                                <option value="1d">
                                    1 Gün
                                </option>
                                <option value="3d">
                                    3 Gün
                                </option>
                            </select>
                            <select name="ind1[0][]" id="ind1" class="dropDown fullWidth">
                                <optgroup label="Fiyat Mumu">
                                    <option value="open">Open - Açılış</option>
                                    <option value="high">High - Yüksek</option>
                                    <option value="low">Low - Düşük</option>
                                    <option value="close">Close - Kapanış</option>
                                </optgroup>
                                <optgroup label="Aroon">
                                    <option value="aroon_14_up">Aroon (14) Üst</option>
                                    <option value="aroon_14_down">Aroon (14) Alt</option>
                                </optgroup>
                                <optgroup label="Bollinger Bantları">
                                    <option value="bbands_20_2_upper">BB (20, 2) Üst Bant</option>
                                    <option value="bbands_20_2_lower">BB (20, 2) Alt Bant</option>
                                    <option value="bbands_20_3_upper">BB (20, 3) Üst Bant</option>
                                    <option value="bbands_20_3_lower">BB (20, 3) Alt Bant</option>
                                </optgroup>
                                <optgroup label="Değişim Hızı">
                                    <option value="roc_9">ROC (9)</option>
                                </optgroup>
                                <optgroup label="Donchian Kanalları">
                                    <option value="don_ch_hband">Donchian (20) Üst Bant</option>
                                    <option value="don_ch_lband">Donchian (20) Alt Band</option>
                                </optgroup>
                                <optgroup label="Emtia Kanal Endeksi">
                                    <option value="cci_20">CCI (20)</option>
                                </optgroup>
                                <optgroup label="Göreceli Güç Endeksi">
                                    <option value="rsi14">RSI (14)</option>
                                    <option value="rsi7">RSI (7)</option>
                                </optgroup>
                                <optgroup label="Hareketli Ortalamalar">
                                    <option value="ma_7">MA (7)</option>
                                    <option value="ma_10">MA (10)</option>
                                    <option value="ma_14">MA (14)</option>
                                    <option value="ma_20">MA (20)</option>
                                    <option value="ma_30">MA (30)</option>
                                    <option value="ma_34">MA (34)</option>
                                    <option value="ma_50">MA (50)</option>
                                    <option value="ma_55">MA (55)</option>
                                    <option value="ma_89">MA (89)</option>
                                    <option value="ma_100">MA (100)</option>
                                    <option value="ma_144">MA (144)</option>
                                    <option value="ma_200">MA (200)</option>
                                    <option value="ma_233">MA (233)</option>
                                    <option value="ema_7">EMA (7)</option>
                                    <option value="ema_10">EMA (10)</option>
                                    <option value="ema_14">EMA (14)</option>
                                    <option value="ema_20">EMA (20)</option>
                                    <option value="ema_30">EMA (30)</option>
                                    <option value="ema_34">EMA (34)</option>
                                    <option value="ema_50">EMA (50)</option>
                                    <option value="ema_55">EMA (55)</option>
                                    <option value="ema_89">EMA (89)</option>
                                    <option value="ema_100">EMA (100)</option>
                                    <option value="ema_144">EMA (144)</option>
                                    <option value="ema_200">EMA (200)</option>
                                    <option value="ema_233">EMA (233)</option>
                                </optgroup>
                                <optgroup label="Ichimoku Kinko Hyo">
                                    <option value="ichimoku_base">Ichimoku Baz Çizgisi / Kijun-sen</option>
                                    <option value="ichimoku_con">Ichimoku Dönüş Çizgisi / Tenkan-sen</option>
                                    <option value="ichimoku_a">Ichimoku Span A</option>
                                    <option value="ichimoku_b">Ichimoku Span B</option>
                                </optgroup>
                                <optgroup label="Keltner Kanalları">
                                    <option value="kc_hband">KC (20) Üst Bant</option>
                                    <option value="kc_lband">KC (20) Alt Bant</option>
                                </optgroup>
                                <optgroup label="MACD">
                                    <option value="macd_seviyesi">MACD (12, 26) Seviyesi</option>
                                    <option value="macd_sinyali">MACD (12, 26) Sinyali</option>
                                </optgroup>
                                <optgroup label="Momentum">
                                    <option value="mom_10">Momentum (10)</option>
                                </optgroup>
                                <optgroup label="Müthiş Osilatör">
                                    <option value="ao">AO</option>
                                </optgroup>
                                <optgroup label="Ortalama Gerçek Aralık">
                                    <option value="atr_14">ATR (14)</option>
                                </optgroup>
                                <optgroup label="Ortalama Yönsel Endeks">
                                    <option value="adx_14">ADX (14) (Ortalama Yönsel Endeks)</option>
                                </optgroup>
                                <optgroup label="Parabolik SAR">
                                    <option value="sar">PSAR</option>
                                </optgroup>
                                <optgroup label="Stochastic">
                                    <option value="stoch_slowd">Stochastic (14, 3, 3) %D</option>
                                    <option value="stoch_slowk">Stochastic (14, 3, 3) %K</option>
                                </optgroup>
                                <optgroup label="Stochastic RSI">
                                    <option value="stoch_rsi_fastk">StochRSI (3, 3, 14, 14) Fast</option>
                                    <option value="stoch_rsi_fastd">StochRSI (3, 3, 14, 14) Slow</option>
                                </optgroup>
                                <optgroup label="Williams Percent Range">
                                    <option value="wr_14">WR (14)</option>
                                </optgroup>
                            </select>
                        </div>

                        <div class="flex1 col-start ">
                            <select name="comperator[0][]" id="comperator" class="dropDown fullWidth">
                                <option value="büyük">Büyük</option>
                                <option value="küçük">Küçük</option>
                                <option value="aşağı keser">Aşağı Keser</option>
                                <option value="yukarı keser">Yukarı Keser</option>
                                <option value="arasında">Arasında</option>
                            </select>
                            <a id="removeBtn" class="formButton outBtn deleteButton">
                                <i class="fa fa-trash"></i>
                            </a>
                        </div>

                        <div class="flex2 col-start">
                            <select name="ind2[0][]" id="ind2" class="dropDown fullWidth">
                                <optgroup label="Fiyat Mumu">
                                    <option value="open">Open - Açılış</option>
                                    <option value="high">High - Yüksek</option>
                                    <option value="low">Low - Düşük</option>
                                    <option value="close">Close - Kapanış</option>
                                </optgroup>
                                <optgroup label="Aroon">
                                    <option value="aroon_14_up">Aroon (14) Üst</option>
                                    <option value="aroon_14_down">Aroon (14) Alt</option>
                                </optgroup>
                                <optgroup label="Bollinger Bantları">
                                    <option value="bbands_20_2_upper">BB (20, 2) Üst Bant</option>
                                    <option value="bbands_20_2_lower">BB (20, 2) Alt Bant</option>
                                    <option value="bbands_20_3_upper">BB (20, 3) Üst Bant</option>
                                    <option value="bbands_20_3_lower">BB (20, 3) Alt Bant</option>
                                </optgroup>
                                <optgroup label="Değişim Hızı">
                                    <option value="roc_9">ROC (9)</option>
                                </optgroup>
                                <optgroup label="Donchian Kanalları">
                                    <option value="don_ch_hband">Donchian (20) Üst Bant</option>
                                    <option value="don_ch_lband">Donchian (20) Alt Band</option>
                                </optgroup>
                                <optgroup label="Emtia Kanal Endeksi">
                                    <option value="cci_20">CCI (20)</option>
                                </optgroup>
                                <optgroup label="Göreceli Güç Endeksi">
                                    <option value="rsi14">RSI (14)</option>
                                    <option value="rsi7">RSI (7)</option>
                                </optgroup>
                                <optgroup label="Hareketli Ortalamalar">
                                    <option value="ma_7">MA (7)</option>
                                    <option value="ma_10">MA (10)</option>
                                    <option value="ma_14">MA (14)</option>
                                    <option value="ma_20">MA (20)</option>
                                    <option value="ma_30">MA (30)</option>
                                    <option value="ma_34">MA (34)</option>
                                    <option value="ma_50">MA (50)</option>
                                    <option value="ma_55">MA (55)</option>
                                    <option value="ma_89">MA (89)</option>
                                    <option value="ma_100">MA (100)</option>
                                    <option value="ma_144">MA (144)</option>
                                    <option value="ma_200">MA (200)</option>
                                    <option value="ma_233">MA (233)</option>
                                    <option value="ema_7">EMA (7)</option>
                                    <option value="ema_10">EMA (10)</option>
                                    <option value="ema_14">EMA (14)</option>
                                    <option value="ema_20">EMA (20)</option>
                                    <option value="ema_30">EMA (30)</option>
                                    <option value="ema_34">EMA (34)</option>
                                    <option value="ema_50">EMA (50)</option>
                                    <option value="ema_55">EMA (55)</option>
                                    <option value="ema_89">EMA (89)</option>
                                    <option value="ema_100">EMA (100)</option>
                                    <option value="ema_144">EMA (144)</option>
                                    <option value="ema_200">EMA (200)</option>
                                    <option value="ema_233">EMA (233)</option>
                                </optgroup>
                                <optgroup label="Ichimoku Kinko Hyo">
                                    <option value="ichimoku_base">Ichimoku Baz Çizgisi / Kijun-sen</option>
                                    <option value="ichimoku_con">Ichimoku Dönüş Çizgisi / Tenkan-sen</option>
                                    <option value="ichimoku_a">Ichimoku Span A</option>
                                    <option value="ichimoku_b">Ichimoku Span B</option>
                                </optgroup>
                                <optgroup label="Keltner Kanalları">
                                    <option value="kc_hband">KC (20) Üst Bant</option>
                                    <option value="kc_lband">KC (20) Alt Bant</option>
                                </optgroup>
                                <optgroup label="MACD">
                                    <option value="macd_seviyesi">MACD (12, 26) Seviyesi</option>
                                    <option value="macd_sinyali">MACD (12, 26) Sinyali</option>
                                </optgroup>
                                <optgroup label="Momentum">
                                    <option value="mom_10">Momentum (10)</option>
                                </optgroup>
                                <optgroup label="Müthiş Osilatör">
                                    <option value="ao">AO</option>
                                </optgroup>
                                <optgroup label="Ortalama Gerçek Aralık">
                                    <option value="atr_14">ATR (14)</option>
                                </optgroup>
                                <optgroup label="Ortalama Yönsel Endeks">
                                    <option value="adx_14">ADX (14) (Ortalama Yönsel Endeks)</option>
                                </optgroup>
                                <optgroup label="Parabolik SAR">
                                    <option value="sar">PSAR</option>
                                </optgroup>
                                <optgroup label="Stochastic">
                                    <option value="stoch_slowd">Stochastic (14, 3, 3) %D</option>
                                    <option value="stoch_slowk">Stochastic (14, 3, 3) %K</option>
                                </optgroup>
                                <optgroup label="Stochastic RSI">
                                    <option value="stoch_rsi_fastk">StochRSI (3, 3, 14, 14) Fast</option>
                                    <option value="stoch_rsi_fastd">StochRSI (3, 3, 14, 14) Slow</option>
                                </optgroup>
                                <optgroup label="Williams Percent Range">
                                    <option value="wr_14">WR (14)</option>
                                </optgroup>
                            </select>
                            <div id="val_div" hidden>
                                <input id="val2" name="val2[0][]" class="dropDown" placeholder="Value">
                            </div>
                            <div id="bet_div" class="row-space-evenly" hidden>
                                <input id="min_val" name="min_val[0][]" class="dropDown" placeholder="Minimum">
                                <input id="max_val" name="max_val[0][]" class="dropDown" placeholder="Maximum">
                            </div>
                            <div id="valueController" class="row-space-evenly padding">
                                <div>
                                    <input type="radio" name="input_type[0][]" id="rad_ind-0-0" value="rad_ind" checked>
                                    <label for="rad_ind-0-0">Gösterge</label>
                                </div>
                                <div>
                                    <input type="radio" name="input_type[0][]" id="rad_val-0-0" value="rad_val">
                                    <label for="rad_val-0-0">Değer</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="bigTopMargin regularFont bottomLine">
            Tasarımcı İşlemleri
        </div>

        <div class="bigTopMargin row-space-between">
            <div class="row-start">
                <a id="addStrBtn" class="formButton blueBtn">
                    <i class="fa fa-plus"></i> Kural Grubu Ekle
                </a>
                <a class="formButton whiteBtn">
                    Gelişmiş Mod
                </a>
                <a class="formButton whiteBtn">
                    Taslaklar
                </a>
            </div>

            <a class="formButton outBtn">
                <i class="fa fa-info"></i> Yardım
            </a>
        </div>

        <div class="bigTopMargin">
            <select class="dropDown fullWidth">
                <option>
                    Değişiklikleri Seçilen Paritelere Uygula
                </option>
                <option>
                    Değişiklikleri Seçilen Paritelere Uygula
                </option>
            </select>
        </div>
        <!--
        <div class="">
            <strong>Select Language:</strong>
            <select id="multiple-checkboxes" multiple="multiple">
                <option value="php">PHP</option>
                <option value="javascript">JavaScript</option>
                <option value="java">Java</option>
                <option value="sql">SQL</option>
                <option value="jquery">Jquery</option>
                <option value=".net">.Net</option>
            </select>
        </div>
-->
        <div class="topMargin">
            <a id="applyButton" type="submit" class="applyButton formButton outBtn">
                Değişiklikleri Paritelere Uygula
            </a>
        </div>
    </form>
</body>
<script>
    $(document).ready(() => {
        setListeners();
    });
    $('#applyButton').click(() => {
        $('#form').submit();
    })
    let md = <?php echo "'" . md5("macd_seviyesi.indicator.macd_sinyali.indicator..aşağı keser.4h") . "';\n"; ?>
    console.log(md);
</script>

</html>