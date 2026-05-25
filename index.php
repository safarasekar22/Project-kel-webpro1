<?php

$hasil = null;
$errors = [];
$jenisList = [
    "tertulis"   => "Tes Tertulis",
    "lisan"      => "Tes Lisan",
    "praktik"    => "Tes Praktik",
    "portofolio" => "Portofolio"
];

$nim = $nama = $kelas = $prodi = $kehadiran = $tugas = $proyek = "";
$checked = array_values(array_diff(array_keys($jenisList), ['lisan']));

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    foreach (["nim", "nama", "kelas", "prodi", "kehadiran", "tugas", "proyek"] as $field) {
        $$field = $_POST[$field] ?? "";
    }

    foreach (["kehadiran", "tugas", "proyek"] as $field) {
        if (!is_numeric($$field) || $$field < 0 || $$field > 100) {
            $errors[$field] = "Nilai {$field} salah";
        }
    }

        $nilaiAkhir = $kehadiran * 0.20 + $tugas * 0.25 + $proyek * 0.55;
        if ($nilaiAkhir >= 80) {
            $grade = "A"; $ket = "Sangat Baik";
        } elseif ($nilaiAkhir >= 70) {
            $grade = "B"; $ket = "Baik";
        } elseif ($nilaiAkhir >= 60) {
            $grade = "C"; $ket = "Cukup";
        } elseif ($nilaiAkhir >= 31) {
            $grade = "D"; $ket = "Kurang";
        } else {
            $grade = "E"; $ket = "Sangat Kurang";
        }

        // JENIS ASESMEN: buat ringkasan berdasarkan pilihan yang tersedia
        $dipilih = [];
        foreach ($checked as $k) {
            if (isset($jenisList[$k])) {
                $dipilih[] = $jenisList[$k];
            }
        }

        $jumlah = count($dipilih);
        if ($jumlah == 0) {
            $msgJenis = "Tidak ada asesmen";
        } elseif ($jumlah == 1) {
            $msgJenis = "1 jenis asesmen";
        } elseif ($jumlah == 2) {
            $msgJenis = "2 jenis asesmen";
        } elseif ($jumlah == 3) {
            $msgJenis = "3 jenis asesmen";
        } else {
            $msgJenis = "Semua asesmen digunakan";
        }

        $hasil = [
            'nim' => $nim,
            'nama' => $nama,
            'kelas' => $kelas,
            'prodi' => $prodi,
            'kehadiran' => $kehadiran,
            'tugas' => $tugas,
            'proyek' => $proyek,
            'nilaiAkhir' => $nilaiAkhir,
            'grade' => $grade,
            'ket' => $ket,
            'msgJenis' => $msgJenis,
        ];
    }


?>

<!DOCTYPE html>
<html>
<head>

<meta name="viewport" content="width=device-width, initial-scale=1">

<link rel="stylesheet" href="style1.css">

<title>Kalkulator Penilaian</title>

</head>

<body>

<div style="padding-left:16px">

<center>

<h2>Kalkulator Penilaian</h2>

<form method="POST" class="mb-4">

<table style="width:70%; border-collapse: collapse;" border="1">

<tr>
    <td>Nama</td>
    <td>:</td>
    <td>
        <input
        type="text"
        name="nama"
        value="<?= $nama ?>"
        placeholder="Masukkan nama"
        required>
    </td>
</tr>

<tr>
    <td>NIM</td>
    <td>:</td>
    <td>
        <input
        type="number"
        name="nim"
        value="<?= $nim ?>"
        required>
    </td>
</tr>

<tr>
    <td>Kelas</td>
    <td>:</td>
            <input
        type="text"
        name="kelas"
        value="<?= $kelas ?>"
        placeholder="Masukkan kelas"
        required>
    </td>
</tr>

<tr>
    <td>Prodi</td>
    <td>:</td>
    <td>
        <input
        type="text"
        name="prodi"
        value="<?= $prodi ?>"
        placeholder="Masukkan prodi"
        required>
    </td>
</tr>

<tr>
    <td>Kehadiran (20%)</td>
    <td>:</td>
    <td>

        <input
        type="number"
        name="kehadiran"
        value="<?= $kehadiran ?>"
        required>
        <?= $errors['kehadiran'] ?? '' ?>

    </td>
</tr>

<tr>
    <td>Tugas (25%)</td>
    <td>:</td>
    <td>

        <input
        type="number"
        name="tugas"
        value="<?= $tugas ?>"
        required>
        <?= $errors['tugas'] ?? '' ?>

    </td>
</tr>

<tr>
    <td>Project Akhir (55%)</td>
    <td>:</td>
    <td>

        <input
        type="number"
        name="proyek"
        value="<?= $proyek ?>"
        required>
        <?= $errors['proyek'] ?? '' ?>

    </td>