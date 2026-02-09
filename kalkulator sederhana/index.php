<?php
session_start(); 
    if (!isset($_SESSION['history'])) {
        $_SESSION['history'] = [];
    }
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Andcode Kalkulator Sederhana PHP</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1 style="text-align: center;">Kalkulator Sederhana</h1>
    <form action="" method="post">
        Angka 1: <input type="text" name="angka1" ><br>
        Angka 2: <input type="text" name="angka2" ><br>
        Operator: <select name="operator">
            <option value="+">+</option>
            <option value="-">-</option>
            <option value="x">x</option>
            <option value="/">/</option>
            <option value="%">%</option>
        </select><br>
        <button type="submit" name="eksekusi">Hitung</button>
        <button type="submit" name="reset">Reset</button>
    </form>

    <?php

    if (isset($_POST['reset'])) {
        // Reset form dan riwayat
        unset($_SESSION['history']);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }

    if (isset($_POST['eksekusi'])) {
        $angka1 = trim($_POST['angka1']);
        $angka2 = trim($_POST['angka2']);
        $operator = $_POST['operator'];
        
        // Validasi
        $errors = [];
        if (empty($angka1)) $errors[] = "Angka 1 tidak boleh kosong.";
        if (empty($angka2)) $errors[] = "Angka 2 tidak boleh kosong.";
        if (!is_numeric($angka1)) $errors[] = "Angka 1 harus berupa angka.";
        if (!is_numeric($angka2)) $errors[] = "Angka 2 harus berupa angka.";

        if (!empty($errors)) {
            echo "<div class='error'>" . implode("<br>", $errors) . "</div>";
        } else {
            $angka1 = (float)$angka1;
            $angka2 = (float)$angka2;

            switch ($operator) {
                case "+":
                    $hasil = $angka1 + $angka2;
                    break;
                case "-":
                    $hasil = $angka1 - $angka2;
                    break;
                case "x":
                    $hasil = $angka1 * $angka2;
                    break;
                case "/":
                    if ($angka2 == 0) {
                        echo "<div class='error'>Error: Pembagian oleh nol tidak diperbolehkan.</div>";
                        exit;
                    }
                    $hasil = $angka1 / $angka2;
                    break;
                case "%":
                    if ($angka2 == 0) {
                        echo "<div class='error'>Error: Modulus oleh nol tidak diperbolehkan.</div>";
                        exit;
                    }
                    $hasil = $angka1 % $angka2;
                    break;
            }

            $entry = htmlspecialchars("$angka1 $operator $angka2 = $hasil");
            array_push($_SESSION['history'], $entry);
            if (count($_SESSION['history']) > 10) array_shift($_SESSION['history']); // Batasi 10 entri

            echo "<div class='result'>";
            echo "<strong>Hasil:</strong><br>$entry";
            echo "</div>";
        }
    }

    if (!empty($_SESSION['history'])) {
        echo "<div class='history'><strong>Riwayat Kalkulasi:</strong><br>" . implode("<br>", $_SESSION['history']) . "</div>";
    }
    ?>
</body>
</html>