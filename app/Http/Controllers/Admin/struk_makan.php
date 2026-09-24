<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pembelian</title>
    <style>
        @page {
            size: 80mm auto;
            margin: 0;
        }

        body {
            font-family: Menlo, Monaco, monospace;
            font-size: 16px;
            margin: 0;
            padding: 5px;
            width: 80mm;
            font-weight: bold;
        }

        .text-center {
            text-align: center;
        }

        .store-name {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .store-info {
            margin-bottom: 10px;
        }

        .store-info .store-text {
            font-size: 28px;
            font-weight: bold;
        }

        .price-text {
            font-size: 28px;
            font-weight: bold;
        }

        .receipt-info {
            margin-bottom: 10px;
        }

        .items {
            width: 100%;
            margin-bottom: 10px;
        }

        .items td {
            padding: 2px 0;
        }

        .separator {
            border-top: 2px dotted #000;
            margin: 8px 0;
        }

        .total-section {
            margin: 10px 0;
        }

        .footer {
            text-align: center;
            margin-top: 10px;
            font-size: 17px;
        }
    </style>
    <script>
        window.onload = function() {
            window.print();
        };
    </script>
    <link rel="stylesheet" href="css/theme.css">
</head>
<body>
    <div class="text-center store-name">
        Krezza Wafa Fried Chicken
    </div>
    <div class="text-center store-info">
        Jerukgamping<br>
        <span class="store-text">Store</span>
    </div>

    <div class="receipt-info">
        Minggu, 16 Februari 2025 17.34
    </div>

    <table class="items">
        <tr>
            <td colspan="3">Paha Bawah + Nasi Sambal Bawang</td>
        </tr>
        <tr>
            <td>@10.000</td>
            <td>x6</td>
            <td style="text-align: right;">
                60.000
            </td>
        </tr>
        <tr>
            <td colspan="3">Prima</td>
        </tr>
        <tr>
            <td>@5.000</td>
            <td>x6</td>
            <td style="text-align: right;">
                30.000
            </td>
        </tr>
    </table>

    <div class="">
        ----------------------------------
    </div>

    <div>Total Tagihan 2</div>
    <div style="margin-bottom: 5px;"><span class="price-text" style="display: block; text-align: right;">Rp90.000</span></div>

    <div>Bayar Tunai</div>
    <div style="text-align: right;">Rp100.000</div>

    <div>Kembali</div>
    <div style="text-align: right;">Rp10.000</div>
    <br>

    <!-- <div class="separator"></div> -->

    <div class="text-center">
        Dahlan (Pagi (8 Jam))
    </div>

    <div class="footer">
        <div>Terima kasih</div>
        <div>Lebih nikmat diawali berdoa</div>
        <div>Telp 08222033053</div>
        <div>IG @KrezzaWafafriedchicken</div>
    </div>
</body>
</html>
