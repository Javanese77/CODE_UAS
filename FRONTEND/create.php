<?php
session_start();
if (!isset($_SESSION['token'])) {
    header("Location: login.php");
    exit;
}

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postData = [
        'nama_barang' => $_POST['nama_barang'],
        'harga'       => $_POST['harga'],
        'stok'        => $_POST['stok'],
        'deskripsi'   => $_POST['deskripsi'] ?? ''
    ];

    $ch = curl_init('http://localhost:8000/api/barang');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $_SESSION['token'],
        'Accept: application/json'
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode == 201) {
        header("Location: index.php");
        exit;
    } else {
        $data = json_decode($response, true);
        $error = $data['message'] ?? "Gagal menambah barang. Code: $httpCode";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Tambah Barang</title>
</head>
<body>
    <h2>Tambah Barang</h2>
    <?php if(isset($error)) echo "<p style='color:red'>$error</p>"; ?>
    <form method="POST">
        <div>
            <label>Nama Barang:</label><br>
            <input type="text" name="nama_barang" required>
        </div>
        <div>
            <label>Harga:</label><br>
            <input type="number" name="harga" required>
        </div>
        <div>
            <label>Stok:</label><br>
            <input type="number" name="stok" required>
        </div>
        <div>
            <label>Deskripsi:</label><br>
            <textarea name="deskripsi"></textarea>
        </div>
        <br>
        <button type="submit">Simpan</button>
        <a href="index.php">Batal</a>
    </form>
</body>
</html>
