<?php
error_reporting(0);
session_start();
$server = "localhost";
$username = $user_;
$password = $passwd_;
$db = $db_;

try {
    $connect = new PDO("mysql:host=$server;dbname=$db", $username, $password);
    $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
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

<body class="ruleBody">
    <div class="logoutCollider">
        <div class="userText"><?php echo $_SESSION['user_name'] ?></div>
        <a class="formButton greenBtn" href="index.php">Geri</a>
        <a class="formButton outBtn" href="logout.php">Çıkış</a>
    </div>
    <div class="ruleContainer topMargin">
        <div>
            <select class="dropDown">
                <option>
                    VE
                </option>
                <option>
                    VEYE
                </option>
            </select>
        </div>

        <div class="ruleGroupBoxContainer col-start">
            <div class="row-space-between">
                <div class="regularFont blackText">- Kural Grubu</div>
                <div class="row-start">
                    <a class="formButton blueBtn">
                        <i class="fa fa-plus"></i>
                    </a>
                    <a class="formButton blueBtn">
                        <i class="fa fa-copy"></i>
                    </a>
                    <a class="formButton outBtn">
                        <i class="fa fa-trash"></i>
                    </a>
                </div>
            </div>
            <div class="bigTopMargin">
                <select class="dropDown">
                    <option>
                        VE
                    </option>
                    <option>
                        VEYE
                    </option>
                </select>
            </div>

            <div class="row-space-between ruleGroupBoxContainer topMargin blueBackground">
                <div class="flex2 col-start ">
                    <select name="period1" id="period1" class="dropDown">
                        <option value="5m">
                            5 Dakika
                        </option>
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
                    </select>
                    <select class="dropDown">
                        <optgroup label="Fiyat">
                            <option value="ask">Ask - Alış Fiyatı</option>
                            <option value="bid">Bid - Satış Fiyatı</option>
                        </optgroup>
                        <optgroup label="Fiyat Mumu">
                            <option value="open">Open - Açılış</option>
                            <option value="high">High - Yüksek</option>
                            <option value="low">Low - Düşük</option>
                            <option value="close">Close - Kapanış</option>
                            <option value="volume">Volume </option>
                            <option value="Maturation">Mum Olgunluğu (Yüzde)</option>
                        </optgroup>
                        <optgroup label="Anlık Değişim (Yüzde)">
                            <option value="change_from_open">Mum Değişimi (Yüzde)</option>
                            <option value="change_from_open_abs">Mum Değişimi (Fiyat)</option>
                            <option value="ChangeInTime.5">5 Dakika</option>
                            <option value="ChangeInTime.10">10 Dakika</option>
                            <option value="ChangeInTime.15">15 Dakika</option>
                            <option value="ChangeInTime.20">20 Dakika</option>
                            <option value="ChangeInTime.25">25 Dakika</option>
                            <option value="ChangeInTime.30">30 Dakika</option>
                            <option value="ChangeInTime.45">45 Dakika</option>
                            <option value="ChangeInTime.60">60 Dakika</option>
                            <option value="ChangeInTime.90">90 Dakika</option>
                            <option value="ChangeInTime.120">2 Saat</option>
                            <option value="ChangeInTime.180">3 Saat</option>
                            <option value="ChangeInTime.240">4 Saat</option>
                            <option value="ChangeInTime.360">6 Saat</option>
                            <option value="ChangeInTime.480">8 Saat</option>
                            <option value="ChangeInTime.600">10 Saat</option>
                            <option value="ChangeInTime.720">12 Saat</option>
                            <option value="ChangeInTime.960">16 Saat</option>
                            <option value="ChangeInTime.1200">20 Saat</option>
                            <option value="ChangeInTime.1440">24 Saat</option>
                            <option value="ChangeInTime.1920">32 Saat</option>
                            <option value="ChangeInTime.2400">40 Saat</option>
                            <option value="ChangeInTime.2880">48 Saat</option>
                            <option value="ChangeInTime.3600">60 Saat</option>
                            <option value="ChangeInTime.4320">72 Saat - 3 Gün</option>
                            <option value="ChangeInTime.5760">96 Saat - 4 Gün</option>
                            <option value="ChangeInTime.7200">120 Saat - 5 Gün</option>
                            <option value="ChangeInTime.8640">144 Saat - 6 Gün</option>
                            <option value="ChangeInTime.10080">168 Saat - 7 Gün</option>
                            <option value="ChangeInTime.11520">192 Saat - 8 Gün</option>
                            <option value="ChangeInTime.12960">216 Saat - 9 Gün</option>
                            <option value="ChangeInTime.14400">240 Saat - 10 Gün</option>
                        </optgroup>
                        <optgroup label="Fiyat Limitleri">
                            <option value="High.1M">En Yüksek (1 Aylık)</option>
                            <option value="High.3M">En Yüksek (3 Aylık)</option>
                            <option value="High.6M">En Yüksek (6 Aylık)</option>
                            <option value="price_52_week_high">En Yüksek (1 Yıllık)</option>
                            <option value="High.All">En Yüksek (Tüm Zamanlar)</option>
                            <option value="Low.1M">En Düşük (1 Aylık)</option>
                            <option value="Low.3M">En Düşük (3 Aylık)</option>
                            <option value="Low.6M">En Düşük (6 Aylık)</option>
                            <option value="price_52_week_low">En Düşük (1 Yıllık)</option>
                            <option value="Low.All">En Düşük (Tüm Zamanlar)</option>
                        </optgroup>
                        <optgroup label="Piyasa Değeri (Market Cap)">
                            <option value="BTC.Dominance">BTC Baskınlığı</option>
                            <option value="ETH.Dominance">ETH Baskınlığı</option>
                        </optgroup>
                        <optgroup label="Aroon">
                            <option value="Aroon.Up">Aroon (14) Üst</option>
                            <option value="Aroon.Down">Aroon (14) Alt</option>
                        </optgroup>
                        <optgroup label="Boğa Ayı gücü">
                            <option value="BBPower">BB Power</option>
                        </optgroup>
                        <optgroup label="Bollinger Bantları">
                            <option value="BB.upper">BB (20, 2) Üst Bant</option>
                            <option value="BB.lower">BB (20, 2) Alt Bant</option>
                            <option value="BB3.upper">BB (20, 3) Üst Bant</option>
                            <option value="BB3.lower">BB (20, 3) Alt Bant</option>
                        </optgroup>
                        <optgroup label="Değişim Hızı">
                            <option value="ROC">ROC (9)</option>
                        </optgroup>
                        <optgroup label="Donchian Kanalları">
                            <option value="DonchCh20.Upper">Donchian (20) Üst Bant</option>
                            <option value="DonchCh20.Lower">Donchian (20) Alt Band</option>
                        </optgroup>
                        <optgroup label="EMA SMA Volume">
                            <option value="EmaSmaVol.Oscilator">EmaSmaVol (9, 17) Osilatör</option>
                            <option value="EmaSmaVol.Signal">EmaSmaVol (9, 17) Sinyal</option>
                        </optgroup>
                        <optgroup label="Emtia Kanal Endeksi">
                            <option value="CCI20">CCI (20)</option>
                        </optgroup>
                        <optgroup label="Göreceli Güç Endeksi">
                            <option value="RSI">RSI (14)</option>
                            <option value="RSI7">RSI (7)</option>
                        </optgroup>
                        <optgroup label="Göreceli Güç Endeksi Hareketli Ortalamaları">
                            <option value="RSI14.SMA5">MA (RSI, 5)</option>
                            <option value="RSI14.SMA7">MA (RSI, 7)</option>
                            <option value="RSI14.SMA10">MA (RSI, 10)</option>
                            <option value="RSI14.SMA14">MA (RSI, 14)</option>
                            <option value="RSI14.SMA21">MA (RSI, 21)</option>
                            <option value="RSI14.EMA5">EMA (RSI, 5)</option>
                            <option value="RSI14.EMA7">EMA (RSI, 7)</option>
                            <option value="RSI14.EMA10">EMA (RSI, 10)</option>
                            <option value="RSI14.EMA14">EMA (RSI, 14)</option>
                            <option value="RSI14.EMA21">EMA (RSI, 21)</option>
                        </optgroup>
                        <optgroup label="Hareketli Ortalamalar">
                            <option value="SMA7">MA (7)</option>
                            <option value="SMA10">MA (10)</option>
                            <option value="SMA14">MA (14)</option>
                            <option value="SMA20">MA (20)</option>
                            <option value="SMA30">MA (30)</option>
                            <option value="SMA34">MA (34)</option>
                            <option value="SMA50">MA (50)</option>
                            <option value="SMA55">MA (55)</option>
                            <option value="SMA89">MA (89)</option>
                            <option value="SMA100">MA (100)</option>
                            <option value="SMA144">MA (144)</option>
                            <option value="SMA200">MA (200)</option>
                            <option value="SMA233">MA (233)</option>
                            <option value="EMA7">EMA (7)</option>
                            <option value="EMA10">EMA (10)</option>
                            <option value="EMA14">EMA (14)</option>
                            <option value="EMA20">EMA (20)</option>
                            <option value="EMA30">EMA (30)</option>
                            <option value="EMA34">EMA (34)</option>
                            <option value="EMA50">EMA (50)</option>
                            <option value="EMA55">EMA (55)</option>
                            <option value="EMA89">EMA (89)</option>
                            <option value="EMA100">EMA (100)</option>
                            <option value="EMA144">EMA (144)</option>
                            <option value="EMA200">EMA (200)</option>
                            <option value="EMA233">EMA (233)</option>
                            <option value="HullMA9">HullMA (9)</option>
                            <option value="VWMA">VWMA (20)</option>
                        </optgroup>
                        <optgroup label="Ichimoku Kinko Hyo">
                            <option value="Ichimoku.BLine">Ichimoku Baz Çizgisi / Kijun-sen</option>
                            <option value="Ichimoku.CLine">Ichimoku Dönüş Çizgisi / Tenkan-sen</option>
                            <option value="Ichimoku.Lead1">Ichimoku Span A</option>
                            <option value="Ichimoku.Lead2">Ichimoku Span B</option>
                        </optgroup>
                        <optgroup label="Keltner Kanalları">
                            <option value="KltChnl.upper">KC (20) Üst Bant</option>
                            <option value="KltChnl.lower">KC (20) Alt Bant</option>
                        </optgroup>
                        <optgroup label="MACD">
                            <option value="MACD.macd">MACD (12, 26) Seviyesi</option>
                            <option value="MACD.signal">MACD (12, 26) Sinyali</option>
                        </optgroup>
                        <optgroup label="Momentum">
                            <option value="Mom">Momentum (10)</option>
                        </optgroup>
                        <optgroup label="Most">
                            <option value="Most_5_1.Most">Most (5, 1) Most</option>
                            <option value="Most_5_1.ExMov">Most (5, 1) ExMov</option>
                        </optgroup>
                        <optgroup label="Müthiş Osilatör">
                            <option value="AO">AO</option>
                        </optgroup>
                        <optgroup label="Ortalama Gerçek Aralık">
                            <option value="ATR">ATR (14)</option>
                        </optgroup>
                        <optgroup label="Ortalama Gün Aralığı">
                            <option value="ADR">ADR (14)</option>
                        </optgroup>
                        <optgroup label="Ortalama Yönsel Endeks">
                            <option value="ADX">ADX (14) (Ortalama Yönsel Endeks)</option>
                            <option value="ADX-DI">ADX (14) -DI (Negatif Yönsel Gösterge)</option>
                            <option value="ADX+DI">ADX (14) +DI (Pozitif Yönsel Gösterge)</option>
                        </optgroup>
                        <optgroup label="Parabolik SAR">
                            <option value="P.SAR">PSAR</option>
                        </optgroup>
                        <optgroup label="Performans">
                            <option value="Perf.W">Performans (Hafta)</option>
                            <option value="Perf.1M">Performans (1 Ay)</option>
                            <option value="Perf.3M">Performans (3 Ay)</option>
                            <option value="Perf.6M">Performans (6 Ay)</option>
                            <option value="Perf.Y">Performans (1 Yıl)</option>
                            <option value="Perf.YTD">Performans (SBB)</option>
                        </optgroup>
                        <optgroup label="Pivot Noktaları">
                            <option value="Pivot.Camarilla.Middle">Camarilla Orta</option>
                            <option value="Pivot.Camarilla.R1">Camarilla R1</option>
                            <option value="Pivot.Camarilla.R2">Camarilla R2</option>
                            <option value="Pivot.Camarilla.R3">Camarilla R3</option>
                            <option value="Pivot.Camarilla.S1">Camarilla S1</option>
                            <option value="Pivot.Camarilla.S2">Camarilla S2</option>
                            <option value="Pivot.Camarilla.S3">Camarilla S3</option>
                            <option value="Pivot.Classic.Middle">Klasik Orta</option>
                            <option value="Pivot.Classic.R1">Klasik R1</option>
                            <option value="Pivot.Classic.R2">Klasik R2</option>
                            <option value="Pivot.Classic.R3">Klasik R3</option>
                            <option value="Pivot.Classic.S1">Klasik S1</option>
                            <option value="Pivot.Classic.S2">Klasik S2</option>
                            <option value="Pivot.Classic.S3">Klasik S3</option>
                            <option value="Pivot.DeMark.Middle">DeMark Orta</option>
                            <option value="Pivot.DeMark.R1">DeMark R1</option>
                            <option value="Pivot.DeMark.S1">DeMark S1</option>
                            <option value="Pivot.Fibonacci.Middle">Fibonacci Orta</option>
                            <option value="Pivot.Fibonacci.R1">Fibonacci R1</option>
                            <option value="Pivot.Fibonacci.R2">Fibonacci R2</option>
                            <option value="Pivot.Fibonacci.R3">Fibonacci R3</option>
                            <option value="Pivot.Fibonacci.S1">Fibonacci S1</option>
                            <option value="Pivot.Fibonacci.S2">Fibonacci S2</option>
                            <option value="Pivot.Fibonacci.S3">Fibonacci S3</option>
                            <option value="Pivot.Woodie.Middle">Woodie Orta</option>
                            <option value="Pivot.Woodie.R1">Woodie R1</option>
                            <option value="Pivot.Woodie.R2">Woodie R2</option>
                            <option value="Pivot.Woodie.R3">Woodie R3</option>
                            <option value="Pivot.Woodie.S1">Woodie S1</option>
                            <option value="Pivot.Woodie.S2">Woodie S2</option>
                            <option value="Pivot.Woodie.S3">Woodie S3</option>
                        </optgroup>
                        <optgroup label="Stochastic">
                            <option value="Stoch.D">Stochastic (14, 3, 3) %D</option>
                            <option value="Stoch.K">Stochastic (14, 3, 3) %K</option>
                        </optgroup>
                        <optgroup label="Stochastic RSI">
                            <option value="Stoch.RSI.K">StochRSI (3, 3, 14, 14) Fast</option>
                            <option value="Stoch.RSI.D">StochRSI (3, 3, 14, 14) Slow</option>
                        </optgroup>
                        <optgroup label="TD Sequential">
                            <option value="TDS">TDS</option>
                        </optgroup>
                        <optgroup label="Trend Yoğunluk Endeksi">
                            <option value="TII">TII</option>
                        </optgroup>
                        <optgroup label="Ultimate Oscillator">
                            <option value="UO">UO (7, 14, 28)</option>
                        </optgroup>
                        <optgroup label="Volatilite">
                            <option value="Volatility.D">Volatilite (Günlük)</option>
                            <option value="Volatility.W">Volatilite (Haftalık)</option>
                            <option value="Volatility.M">Volatilite (Aylık)</option>
                        </optgroup>
                        <optgroup label="Volume ">
                            <option value="Volume1440InBTC">İşlem Hacmi (24S) (BTC)</option>
                            <option value="relative_volume_10d_calc">Göreceli Hacim</option>
                            <option value="average_volume_10d_calc">Average Volume (10 Days)</option>
                            <option value="average_volume_30d_calc">Average Volume (30 Days)</option>
                            <option value="average_volume_60d_calc">Average Volume (60 Days)</option>
                            <option value="average_volume_90d_calc">Average Volume (90 Days)</option>
                        </optgroup>
                        <optgroup label="Williams Percent Range">
                            <option value="W.R">WR (14)</option>
                        </optgroup>
                    </select>
                </div>

                <div class="flex1 col-start ">
                    <select class="dropDown">
                        <optgroup label="Gösterge">
                            <option value="11">=</option>
                            <option value="12">!=</option>
                            <option value="13">&gt;</option>
                            <option value="14">&gt;=</option>
                            <option value="15">&lt;</option>
                            <option value="16">&lt;=</option>
                        </optgroup>
                        <optgroup label="Değer">
                            <option value="21">=</option>
                            <option value="22">!=</option>
                            <option value="23">&gt;</option>
                            <option value="24">&gt;=</option>
                            <option value="25">&lt;</option>
                            <option value="26">&lt;=</option>
                        </optgroup>
                        <optgroup label="Aralık">
                            <option value="31">Arasında</option>
                            <option value="32">Arasında Değil</option>
                            <option value="33">Arasında veya Eşit</option>
                            <option value="34">Arasında veya Eşit Değil</option>
                        </optgroup>
                    </select>
                    <a class="formButton outBtn deleteButton">
                        <i class="fa fa-trash"></i>
                    </a>
                </div>

                <div class="flex2 col-start">
                    <select name="period2" id="period2" class="dropDown">
                        <option value="5m">
                            5 Dakika
                        </option>
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
                    </select>
                    <select class="dropDown">
                        <optgroup label="Fiyat">
                            <option value="ask">Ask - Alış Fiyatı</option>
                            <option value="bid">Bid - Satış Fiyatı</option>
                        </optgroup>
                        <optgroup label="Fiyat Mumu">
                            <option value="open">Open - Açılış</option>
                            <option value="high">High - Yüksek</option>
                            <option value="low">Low - Düşük</option>
                            <option value="close">Close - Kapanış</option>
                            <option value="volume">Volume </option>
                            <option value="Maturation">Mum Olgunluğu (Yüzde)</option>
                        </optgroup>
                        <optgroup label="Anlık Değişim (Yüzde)">
                            <option value="change_from_open">Mum Değişimi (Yüzde)</option>
                            <option value="change_from_open_abs">Mum Değişimi (Fiyat)</option>
                            <option value="ChangeInTime.5">5 Dakika</option>
                            <option value="ChangeInTime.10">10 Dakika</option>
                            <option value="ChangeInTime.15">15 Dakika</option>
                            <option value="ChangeInTime.20">20 Dakika</option>
                            <option value="ChangeInTime.25">25 Dakika</option>
                            <option value="ChangeInTime.30">30 Dakika</option>
                            <option value="ChangeInTime.45">45 Dakika</option>
                            <option value="ChangeInTime.60">60 Dakika</option>
                            <option value="ChangeInTime.90">90 Dakika</option>
                            <option value="ChangeInTime.120">2 Saat</option>
                            <option value="ChangeInTime.180">3 Saat</option>
                            <option value="ChangeInTime.240">4 Saat</option>
                            <option value="ChangeInTime.360">6 Saat</option>
                            <option value="ChangeInTime.480">8 Saat</option>
                            <option value="ChangeInTime.600">10 Saat</option>
                            <option value="ChangeInTime.720">12 Saat</option>
                            <option value="ChangeInTime.960">16 Saat</option>
                            <option value="ChangeInTime.1200">20 Saat</option>
                            <option value="ChangeInTime.1440">24 Saat</option>
                            <option value="ChangeInTime.1920">32 Saat</option>
                            <option value="ChangeInTime.2400">40 Saat</option>
                            <option value="ChangeInTime.2880">48 Saat</option>
                            <option value="ChangeInTime.3600">60 Saat</option>
                            <option value="ChangeInTime.4320">72 Saat - 3 Gün</option>
                            <option value="ChangeInTime.5760">96 Saat - 4 Gün</option>
                            <option value="ChangeInTime.7200">120 Saat - 5 Gün</option>
                            <option value="ChangeInTime.8640">144 Saat - 6 Gün</option>
                            <option value="ChangeInTime.10080">168 Saat - 7 Gün</option>
                            <option value="ChangeInTime.11520">192 Saat - 8 Gün</option>
                            <option value="ChangeInTime.12960">216 Saat - 9 Gün</option>
                            <option value="ChangeInTime.14400">240 Saat - 10 Gün</option>
                        </optgroup>
                        <optgroup label="Fiyat Limitleri">
                            <option value="High.1M">En Yüksek (1 Aylık)</option>
                            <option value="High.3M">En Yüksek (3 Aylık)</option>
                            <option value="High.6M">En Yüksek (6 Aylık)</option>
                            <option value="price_52_week_high">En Yüksek (1 Yıllık)</option>
                            <option value="High.All">En Yüksek (Tüm Zamanlar)</option>
                            <option value="Low.1M">En Düşük (1 Aylık)</option>
                            <option value="Low.3M">En Düşük (3 Aylık)</option>
                            <option value="Low.6M">En Düşük (6 Aylık)</option>
                            <option value="price_52_week_low">En Düşük (1 Yıllık)</option>
                            <option value="Low.All">En Düşük (Tüm Zamanlar)</option>
                        </optgroup>
                        <optgroup label="Piyasa Değeri (Market Cap)">
                            <option value="BTC.Dominance">BTC Baskınlığı</option>
                            <option value="ETH.Dominance">ETH Baskınlığı</option>
                        </optgroup>
                        <optgroup label="Aroon">
                            <option value="Aroon.Up">Aroon (14) Üst</option>
                            <option value="Aroon.Down">Aroon (14) Alt</option>
                        </optgroup>
                        <optgroup label="Boğa Ayı gücü">
                            <option value="BBPower">BB Power</option>
                        </optgroup>
                        <optgroup label="Bollinger Bantları">
                            <option value="BB.upper">BB (20, 2) Üst Bant</option>
                            <option value="BB.lower">BB (20, 2) Alt Bant</option>
                            <option value="BB3.upper">BB (20, 3) Üst Bant</option>
                            <option value="BB3.lower">BB (20, 3) Alt Bant</option>
                        </optgroup>
                        <optgroup label="Değişim Hızı">
                            <option value="ROC">ROC (9)</option>
                        </optgroup>
                        <optgroup label="Donchian Kanalları">
                            <option value="DonchCh20.Upper">Donchian (20) Üst Bant</option>
                            <option value="DonchCh20.Lower">Donchian (20) Alt Band</option>
                        </optgroup>
                        <optgroup label="EMA SMA Volume">
                            <option value="EmaSmaVol.Oscilator">EmaSmaVol (9, 17) Osilatör</option>
                            <option value="EmaSmaVol.Signal">EmaSmaVol (9, 17) Sinyal</option>
                        </optgroup>
                        <optgroup label="Emtia Kanal Endeksi">
                            <option value="CCI20">CCI (20)</option>
                        </optgroup>
                        <optgroup label="Göreceli Güç Endeksi">
                            <option value="RSI">RSI (14)</option>
                            <option value="RSI7">RSI (7)</option>
                        </optgroup>
                        <optgroup label="Göreceli Güç Endeksi Hareketli Ortalamaları">
                            <option value="RSI14.SMA5">MA (RSI, 5)</option>
                            <option value="RSI14.SMA7">MA (RSI, 7)</option>
                            <option value="RSI14.SMA10">MA (RSI, 10)</option>
                            <option value="RSI14.SMA14">MA (RSI, 14)</option>
                            <option value="RSI14.SMA21">MA (RSI, 21)</option>
                            <option value="RSI14.EMA5">EMA (RSI, 5)</option>
                            <option value="RSI14.EMA7">EMA (RSI, 7)</option>
                            <option value="RSI14.EMA10">EMA (RSI, 10)</option>
                            <option value="RSI14.EMA14">EMA (RSI, 14)</option>
                            <option value="RSI14.EMA21">EMA (RSI, 21)</option>
                        </optgroup>
                        <optgroup label="Hareketli Ortalamalar">
                            <option value="SMA7">MA (7)</option>
                            <option value="SMA10">MA (10)</option>
                            <option value="SMA14">MA (14)</option>
                            <option value="SMA20">MA (20)</option>
                            <option value="SMA30">MA (30)</option>
                            <option value="SMA34">MA (34)</option>
                            <option value="SMA50">MA (50)</option>
                            <option value="SMA55">MA (55)</option>
                            <option value="SMA89">MA (89)</option>
                            <option value="SMA100">MA (100)</option>
                            <option value="SMA144">MA (144)</option>
                            <option value="SMA200">MA (200)</option>
                            <option value="SMA233">MA (233)</option>
                            <option value="EMA7">EMA (7)</option>
                            <option value="EMA10">EMA (10)</option>
                            <option value="EMA14">EMA (14)</option>
                            <option value="EMA20">EMA (20)</option>
                            <option value="EMA30">EMA (30)</option>
                            <option value="EMA34">EMA (34)</option>
                            <option value="EMA50">EMA (50)</option>
                            <option value="EMA55">EMA (55)</option>
                            <option value="EMA89">EMA (89)</option>
                            <option value="EMA100">EMA (100)</option>
                            <option value="EMA144">EMA (144)</option>
                            <option value="EMA200">EMA (200)</option>
                            <option value="EMA233">EMA (233)</option>
                            <option value="HullMA9">HullMA (9)</option>
                            <option value="VWMA">VWMA (20)</option>
                        </optgroup>
                        <optgroup label="Ichimoku Kinko Hyo">
                            <option value="Ichimoku.BLine">Ichimoku Baz Çizgisi / Kijun-sen</option>
                            <option value="Ichimoku.CLine">Ichimoku Dönüş Çizgisi / Tenkan-sen</option>
                            <option value="Ichimoku.Lead1">Ichimoku Span A</option>
                            <option value="Ichimoku.Lead2">Ichimoku Span B</option>
                        </optgroup>
                        <optgroup label="Keltner Kanalları">
                            <option value="KltChnl.upper">KC (20) Üst Bant</option>
                            <option value="KltChnl.lower">KC (20) Alt Bant</option>
                        </optgroup>
                        <optgroup label="MACD">
                            <option value="MACD.macd">MACD (12, 26) Seviyesi</option>
                            <option value="MACD.signal">MACD (12, 26) Sinyali</option>
                        </optgroup>
                        <optgroup label="Momentum">
                            <option value="Mom">Momentum (10)</option>
                        </optgroup>
                        <optgroup label="Most">
                            <option value="Most_5_1.Most">Most (5, 1) Most</option>
                            <option value="Most_5_1.ExMov">Most (5, 1) ExMov</option>
                        </optgroup>
                        <optgroup label="Müthiş Osilatör">
                            <option value="AO">AO</option>
                        </optgroup>
                        <optgroup label="Ortalama Gerçek Aralık">
                            <option value="ATR">ATR (14)</option>
                        </optgroup>
                        <optgroup label="Ortalama Gün Aralığı">
                            <option value="ADR">ADR (14)</option>
                        </optgroup>
                        <optgroup label="Ortalama Yönsel Endeks">
                            <option value="ADX">ADX (14) (Ortalama Yönsel Endeks)</option>
                            <option value="ADX-DI">ADX (14) -DI (Negatif Yönsel Gösterge)</option>
                            <option value="ADX+DI">ADX (14) +DI (Pozitif Yönsel Gösterge)</option>
                        </optgroup>
                        <optgroup label="Parabolik SAR">
                            <option value="P.SAR">PSAR</option>
                        </optgroup>
                        <optgroup label="Performans">
                            <option value="Perf.W">Performans (Hafta)</option>
                            <option value="Perf.1M">Performans (1 Ay)</option>
                            <option value="Perf.3M">Performans (3 Ay)</option>
                            <option value="Perf.6M">Performans (6 Ay)</option>
                            <option value="Perf.Y">Performans (1 Yıl)</option>
                            <option value="Perf.YTD">Performans (SBB)</option>
                        </optgroup>
                        <optgroup label="Pivot Noktaları">
                            <option value="Pivot.Camarilla.Middle">Camarilla Orta</option>
                            <option value="Pivot.Camarilla.R1">Camarilla R1</option>
                            <option value="Pivot.Camarilla.R2">Camarilla R2</option>
                            <option value="Pivot.Camarilla.R3">Camarilla R3</option>
                            <option value="Pivot.Camarilla.S1">Camarilla S1</option>
                            <option value="Pivot.Camarilla.S2">Camarilla S2</option>
                            <option value="Pivot.Camarilla.S3">Camarilla S3</option>
                            <option value="Pivot.Classic.Middle">Klasik Orta</option>
                            <option value="Pivot.Classic.R1">Klasik R1</option>
                            <option value="Pivot.Classic.R2">Klasik R2</option>
                            <option value="Pivot.Classic.R3">Klasik R3</option>
                            <option value="Pivot.Classic.S1">Klasik S1</option>
                            <option value="Pivot.Classic.S2">Klasik S2</option>
                            <option value="Pivot.Classic.S3">Klasik S3</option>
                            <option value="Pivot.DeMark.Middle">DeMark Orta</option>
                            <option value="Pivot.DeMark.R1">DeMark R1</option>
                            <option value="Pivot.DeMark.S1">DeMark S1</option>
                            <option value="Pivot.Fibonacci.Middle">Fibonacci Orta</option>
                            <option value="Pivot.Fibonacci.R1">Fibonacci R1</option>
                            <option value="Pivot.Fibonacci.R2">Fibonacci R2</option>
                            <option value="Pivot.Fibonacci.R3">Fibonacci R3</option>
                            <option value="Pivot.Fibonacci.S1">Fibonacci S1</option>
                            <option value="Pivot.Fibonacci.S2">Fibonacci S2</option>
                            <option value="Pivot.Fibonacci.S3">Fibonacci S3</option>
                            <option value="Pivot.Woodie.Middle">Woodie Orta</option>
                            <option value="Pivot.Woodie.R1">Woodie R1</option>
                            <option value="Pivot.Woodie.R2">Woodie R2</option>
                            <option value="Pivot.Woodie.R3">Woodie R3</option>
                            <option value="Pivot.Woodie.S1">Woodie S1</option>
                            <option value="Pivot.Woodie.S2">Woodie S2</option>
                            <option value="Pivot.Woodie.S3">Woodie S3</option>
                        </optgroup>
                        <optgroup label="Stochastic">
                            <option value="Stoch.D">Stochastic (14, 3, 3) %D</option>
                            <option value="Stoch.K">Stochastic (14, 3, 3) %K</option>
                        </optgroup>
                        <optgroup label="Stochastic RSI">
                            <option value="Stoch.RSI.K">StochRSI (3, 3, 14, 14) Fast</option>
                            <option value="Stoch.RSI.D">StochRSI (3, 3, 14, 14) Slow</option>
                        </optgroup>
                        <optgroup label="TD Sequential">
                            <option value="TDS">TDS</option>
                        </optgroup>
                        <optgroup label="Trend Yoğunluk Endeksi">
                            <option value="TII">TII</option>
                        </optgroup>
                        <optgroup label="Ultimate Oscillator">
                            <option value="UO">UO (7, 14, 28)</option>
                        </optgroup>
                        <optgroup label="Volatilite">
                            <option value="Volatility.D">Volatilite (Günlük)</option>
                            <option value="Volatility.W">Volatilite (Haftalık)</option>
                            <option value="Volatility.M">Volatilite (Aylık)</option>
                        </optgroup>
                        <optgroup label="Volume ">
                            <option value="Volume1440InBTC">İşlem Hacmi (24S) (BTC)</option>
                            <option value="relative_volume_10d_calc">Göreceli Hacim</option>
                            <option value="average_volume_10d_calc">Average Volume (10 Days)</option>
                            <option value="average_volume_30d_calc">Average Volume (30 Days)</option>
                            <option value="average_volume_60d_calc">Average Volume (60 Days)</option>
                            <option value="average_volume_90d_calc">Average Volume (90 Days)</option>
                        </optgroup>
                        <optgroup label="Williams Percent Range">
                            <option value="W.R">WR (14)</option>
                        </optgroup>
                    </select>
                </div>
            </div>
        </div>

        <div class="bigTopMargin regularFont bottomLine">
            Tasarımcı İşlemleri
        </div>

        <div class="bigTopMargin row-space-between">
            <div class="row-start">
                <a class="formButton blueBtn">
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
            <select class="dropDown">
                <option>
                    Değişiklikleri Seçilen Paritelere Uygula
                </option>
                <option>
                    Değişiklikleri Seçilen Paritelere Uygula
                </option>
            </select>
        </div>

        <div class="topMargin">
            <a class="applyButton formButton outBtn">
                Değişiklikleri Paritelere Uygula
            </a>
        </div>
    </div>
</body>

</html>
