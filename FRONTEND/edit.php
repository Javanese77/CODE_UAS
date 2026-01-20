<?php
session_start();
if (!isset($_SESSION['token'])) {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'] ?? null;
if (!$id) {
    echo "ID tidak ditemukan.";
    exit;
}

// Fetch Barang Detail
$ch = curl_init("http://localhost:8000/api/barang/$id");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . $_SESSION['token'],
    'Accept: application/json'
]);
$response_barang = curl_exec($ch);
curl_close($ch);

$barangData = json_decode($response_barang, true)['data'] ?? null;

if (!$barangData) {
    echo "Data barang tidak ditemukan.";
    exit;
}

// Handle Update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postData = [
        'nama_barang' => $_POST['nama_barang'],
        'harga'       => $_POST['harga'],
        'stok'        => $_POST['stok'],
        'deskripsi'   => $_POST['deskripsi'] ?? ''
    ];

    $ch = curl_init("http://localhost:8000/api/barang/$id");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $_SESSION['token'],
        'Accept: application/json'
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode == 200) {
        header("Location: index.php");
        exit;
    } else {
        $error = "Gagal update barang. Code: $httpCode Response: $response";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Barang</title>
</head>
<body>
    <h2>Edit Barang</h2>
    <?php if(isset($error)) echo "<p style='color:red'>$error</p>"; ?>
    <form method="POST">
        <div>
            <label>Nama Barang:</label><br>
            <input type="text" name="nama_barang" value="<?php echo htmlspecialchars($barangData['nama_barang']); ?>" required>
        </div>
        <div>
            <label>Harga:</label><br>
            <input type="number" name="harga" value="<?php echo htmlspecialchars($barangData['harga']); ?>" required>
        </div>
        <div>
            <label>Stok:</label><br>
            <input type="number" name="stok" value="<?php echo htmlspecialchars($barangData['stok']); ?>" required>
        </div>
        <div>
            <label>Deskripsi:</label><br>
            <textarea name="deskripsi"><?php echo htmlspecialchars($barangData['deskripsi']); ?></textarea>
        </div>
        <br>
        <button type="submit">Update</button>
        <a href="index.php">Batal</a>
    </form>
</body>
</html>
