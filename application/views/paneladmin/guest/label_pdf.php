<!DOCTYPE html>
<html>

<head>
    <style>
        @page {
            size: 210mm 148mm;
            /* Ukuran kertas label */
            margin: 0;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: sans-serif;
            font-size: 10px;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0mm 0mm;
            /* jarak antar kolom: 4mm, baris: 0mm */
        }

        td {
            width: 64mm;
            height: 32mm;
            padding: 2mm;
            text-align: center;
            vertical-align: middle;
            box-sizing: border-box;
            /* border: 1px dashed #999; */
        }

        img {
            width: 22mm;
            height: 22mm;
            margin-top: 2mm;
        }
    </style>
</head>

<body>

    <table>
        <?php
        $col = 0;
        $max_col = 3;
        foreach ($guests as $i => $guest):
            if ($col == 0) echo '<tr>';
        ?>
            <td>
                <img src="<?= base_url('public/uploads/qrcode/' . $guest->qr_code . '.png') ?>"><br>
                <?= htmlspecialchars($guest->name) ?>
            </td>
        <?php
            $col++;
            if ($col == $max_col) {
                echo '</tr>';
                $col = 0;
            }
        endforeach;

        // isi sel kosong jika jumlah tamu tidak habis dibagi 3
        if ($col != 0) {
            for ($j = $col; $j < $max_col; $j++) echo '<td></td>';
            echo '</tr>';
        }
        ?>
    </table>

</body>

</html>