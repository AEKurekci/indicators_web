<?php
include 'env.php';
error_reporting(0);
session_start();
$server = $host_;
$username = $user_;
$password = $passwd_;
$db = $db_;
$port = $port_;
$user = $_SESSION['user_name'];

try {
    $connect = new PDO("mysql:host=$server;port=$port;dbname=$db;charset=utf8", $username, $password);
    $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $sql_md5 = "Select id from ind_conditions";
        $md5_result = $connect->query($sql_md5);
        $md5_rows = $md5_result->fetchAll(PDO::FETCH_COLUMN);
        $sql_md5_strs = "Select id from strategies";
        $md5_result_strs = $connect->query($sql_md5_strs);
        $md5_rows_strs = $md5_result_strs->fetchAll(PDO::FETCH_COLUMN);

        $bigAndOr = 'and';
        $bigSellBuy = 'buy';
        $andOr = 'and';
        $sellBuy = $_POST['sellBuy'];
        $period1 = $_POST['period1'];
        $ind1 = $_POST['ind1'];
        $ind2 = $_POST['ind2'];
        $val2 = $_POST['val2'];
        $comperator = $_POST['comperator'];
        $input_type = $_POST['input_type'];
        $max_val = $_POST['max_val'];
        $min_val = $_POST['min_val'];
        $str_name = $_POST['str_name'];
        $stop = $_POST['stop'];
        $error = "";

        if($str_name == ""){
            $error = "Kayıt Başarısız. Lütfen stratejiye bir isim verin";
        }

        $strategies = [
            "str_type" => $bigSellBuy,
            "comb_type" => $bigAndOr,
            "str_name" => $str_name,
            "stop" => $stop,
            "rule_group" => array()
        ];

        foreach ($period1 as $i => $p1) {

            $str_obj = [
                "str_type" => $sellBuy[$i],
                "cond_type" => 'and',
                "cond_ids" => array()
            ];

            foreach ($p1 as $j => $per) {
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
                    if($ind2[$i][$j] == ""){
                        $error = "Hata! Değer karşılaştırması seçiliyse bir değer girilmesi gerekir!";
                        break 2;
                    }
                }
                if ($comperator[$i][$j] == 'arasında') {
                    if ($min_val[$i][$j] == '' || $max_val[$i][$j] == '') {
                        $error = "Hata! Tüm değerlerin doğru girildiğininden emin olun!";
                        break 2;
                    }
                    $ind2[$i][$j] = $min_val[$i][$j];
                    $value3 = $max_val[$i][$j];
                    $ind2_type = 'value';
                }
                $text_for_md5 = $ind1[$i][$j] . '.' . $ind1_type . '.' . $ind2[$i][$j] . '.' . $ind2_type . '.' . $value3 . '.' . $comperator[$i][$j] . '.' . $period1[$i][$j];
                $cond_md = md5($text_for_md5);
                if (in_array($cond_md, $md5_rows) == false) {
                    array_push($str_obj["cond_ids"], $cond_md);
                    write_cond_db($cond_md, $ind1[$i][$j], $ind1_type, $ind2[$i][$j], $ind2_type, $value3, $comperator[$i][$j], $period1[$i][$j], $connect);
                    array_push($md5_rows, $cond_md);
                } else {
                    array_push($str_obj["cond_ids"], $cond_md);
                }
            }
            array_push($strategies["rule_group"], $str_obj);
        }
        $extractedStrategyTypes = array_map("extractStrType", $strategies["rule_group"]);
        if(in_array("sell", $extractedStrategyTypes) == 0 || in_array("buy", $extractedStrategyTypes) == 0){
            $error = "Hata! Alış ve Satış stratejisinin ikisi de eklenmelidir, iki stratejiden biri eksik.";
        }
        if($error == ""){
            $str_json = json_encode($strategies);
            $str_md = md5($str_json);
            if (in_array($str_md, $md5_rows_strs) == false) {
                write_str_db($connect, $str_md, $str_json, $str_name, $stop, $user);
            } else {
                echo "<script type='text/javascript'>alert('Zaten böyle bir strateji kaydı var!');</script>";
            }   
        }
    }
} catch (PDOException $ex) {
    print "Connection failed" . $ex->getMessage();
}

function write_cond_db($md_val, $ind1, $ind1_type, $ind2, $ind2_type, $val3, $oper, $per, $conn)
{
    $sqCond = "INSERT INTO ind_conditions (id, ind1, first_column_type, ind2, second_column_type, value3, operator, period) VALUES
    ('$md_val', '$ind1','$ind1_type','$ind2','$ind2_type','$val3','$oper','$per')";
    $conn->exec($sqCond);
}

function write_str_db($conn, $id, $strs, $str_name, $stop, $user)
{
    $sqlStr = "INSERT INTO strategies (id, strategy, stop, username, durum, strategy_name) VALUES ('$id', '$strs', NULLIF('$stop',''), '$user', '1', '$str_name')";
    $conn->exec($sqlStr);
    echo "<script type='text/javascript'>alert('Kayıt başarılı');</script>";
}

function extractStrType($strArray){
    return $strArray["str_type"];
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/style.css?version=2">
    <link rel="stylesheet" href="styles/index.css?version=1">
    <link rel="stylesheet" href="styles/rule.css?version=3">
    <link rel="icon" href="assets/icon.png" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="placeConditions.js"></script>
    <script src="ruleController.js"></script>
    <link rel="icon" href="assets/icon.png" />
<!--
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
    <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css">
-->

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

        <div class="userText"><?php echo $user ?></div>
    </div>
    
    <div class="bodyContainer">
        <div class="leftMenuContainer">
            <ul>
                <li>
                    <a class="menuItem" href="index.php">
                        <i class="fa fa-home"></i>
                        Ana Sayfa
                    </a>
                </li>  
                <li>
                    <a class="menuItem menuItemSelected" href="rule.php">
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
            <div>
                <span id="errorText" class="star">
                    <?php echo $error ?>
                </span>
            </div>
            <form id="form" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" class="ruleContainer topMargin">
                <!--    
                    <div class="row-space-between">
                        <select class="dropDown halfWidth" name="bigAndOr">
                            <option value="and">
                                VE
                            </option>
                            <option value="or">
                                VEYE
                            </option>
                        </select>

                        <select class="dropDown halfWidth" name="bigSellBuy">
                            <option value="sell">
                                SAT
                            </option>
                            <option value="buy">
                                AL
                            </option>
                        </select>
                    </div>
                -->

                <div id="str-container">
                    <div id="strategy-0" class="ruleGroupBoxContainer col-start strategy shadow">
                        <div class="regularFont blackText">Kural Grubu</div>

                        <div id="ruleGroup">
                            <div class="bigTopMargin row-space-between">
                                <!--
                                <select id="andOr" class="dropDown halfWidth marginRight" name="andOr[0]">
                                    <option value="and">
                                        VE
                                    </option>
                                    <option value="or">
                                        VEYE
                                    </option>
                                </select>
                                -->

                                <select id="sellBuy" class="dropDown halfWidth marginRight" name="sellBuy[0]">
                                    <option value="sell">
                                        SAT
                                    </option>
                                    <option value="buy">
                                        AL
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

                            <div id="rule-0" class="rule row-space-between ruleGroupBoxContainer blueBackground">
                                <div class="flex2 col-start ">
                                    <select name="period1[0][0]" id="period1" class="dropDown fullWidth">
                                        <option value="15m">
                                            15 Dakika
                                        </option>
                                        <option value="1h">
                                            1 Saat
                                        </option>
                                        <option value="4h">
                                            4 Saat
                                        </option>
                                        <option value="8h">
                                            8 Saat
                                        </option>
                                        <option value="1d">
                                            1 Gün
                                        </option>
                                    </select>
                                    <select name="ind1[0][0]" id="ind1" class="dropDown fullWidth">
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
                                    <select name="comperator[0][0]" id="comperator" class="dropDown fullWidth">
                                        <option value="büyük">Büyük</option>
                                        <option value="küçük">Küçük</option>
                                        <option value="aşağı keser">Aşağı Keser</option>
                                        <option value="yukarı keser">Yukarı Keser</option>
                                        <option value="arasında">Arasında</option>
                                    </select>
                                    <a id="removeBtn" class="formButton outBtn deleteButton topMargin">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </div>

                                <div class="flex2 col-start">
                                    <select name="ind2[0][0]" id="ind2" class="dropDown fullWidth">
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
                                        <input id="val2" name="val2[0][0]" class="dropDown" placeholder="Value" type="number">
                                    </div>
                                    <div id="bet_div" class="row-space-evenly" hidden>
                                        <input id="min_val" name="min_val[0][0]" class="dropDown" placeholder="Minimum">
                                        <input id="max_val" name="max_val[0][0]" class="dropDown" placeholder="Maximum">
                                    </div>
                                    <div id="valueController" class="row-space-evenly padding">
                                        <div>
                                            <input type="radio" name="input_type[0][0]" id="rad_ind-0-0" value="rad_ind" checked>
                                            <label for="rad_ind-0-0">Gösterge</label>
                                        </div>
                                        <div>
                                            <input type="radio" name="input_type[0][0]" id="rad_val-0-0" value="rad_val">
                                            <label for="rad_val-0-0">Değer</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="bottomLine">
                    
                </div>

                <div class="bigTopMargin bottomContainer">
                    <div>
                        <a id="addStrBtn" class="formButton blueBtn">
                            <i class="fa fa-plus"></i> Kural Grubu Ekle
                        </a>
                    </div>
                    <div>
                        <input id="stop" type="number" class="dropDown" placeholder="Stop" name="stop" /> <span class="lightFont font18">%</span>
                    </div>
                    <input id="str_name" class="dropDown" placeholder="Stratejiye isim ver" name="str_name" />
                </div>

                <div class="topMargin">
                    <a id="applyButton" class="applyButton formButton greenBtn">
                        Stratejiyi Ekle
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>
<script>
    $(document).ready(() => {
        setListeners();
    });
    const isValid = () => {
        if($('#comperator').val() == 'arasında' && ($('#min_val').val() == "" || $('#max_val').val() == "")){
            $('#errorText').text("Lütfen küçük ve büyük değerlerin doğru girildiğinden emin olun.")
            return false;
        }else if($('#str_name').val() == ""){
            $('#errorText').text("Lütfen stratejinize bir isim verin.")
            return false;
        }else{
            $('#errorText').text("")
            return true;
        }
    }
    const submitForm = () => {
        if(isValid()){
            $('#form').submit();
        }
    }
    $('#applyButton').click(submitForm)
    //let md = <?php echo "'" . md5("macd_seviyesi.indicator.macd_sinyali.indicator..aşağı keser.4h") . "';\n"; ?>
    //console.log(md);
</script>

</html>