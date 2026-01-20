<?php
session_start();
if (!isset($_SESSION['token'])) {
    header("Location: login.php");
    exit;
}

$start = microtime(true);
// Fetch Data from API
$ch = curl_init('http://localhost:8000/api/barang');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);
$end = microtime(true);
$duration = $end - $start;

$data = json_decode($response, true);
$kotaList = $data['data'] ?? [];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Barang</title>
    <!-- Simple CSS for Table -->
    <style>
        body { font-family: sans-serif; padding: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .btn { padding: 5px 10px; text-decoration: none; color: white; border-radius: 3px; }
        .btn-add { background-color: green; }
        .btn-edit { background-color: orange; }
        .btn-delete { background-color: red; }
    </style>
</head>
<body>
    <h2>Daftar Barang</h2>
    <a href="create.php" class="btn btn-add">Tambah Barang</a>
    <p>Selamat datang, <?php echo htmlspecialchars($_SESSION['user']['name'] ?? 'User'); ?></p>
    <a href="logout.php" class="btn" style="background-color: #333; float:right;">Logout</a>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Barang</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Deskripsi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($kotaList)): ?>
                <?php foreach ($kotaList as $barang): ?>
                <tr>
                    <td><?php echo $barang['id']; ?></td>
                    <td><?php echo htmlspecialchars($barang['nama_barang']); ?></td>
                    <td><?php echo htmlspecialchars($barang['harga']); ?></td>
                    <td><?php echo $barang['stok']; ?></td>
                    <td><?php echo htmlspecialchars($barang['deskripsi']); ?></td>
                    <td>
                        <a href="edit.php?id=<?php echo $barang['id']; ?>" class="btn btn-edit">Edit</a>
                        <a href="delete.php?id=<?php echo $barang['id']; ?>" class="btn btn-delete" onclick="return confirm('Yakin hapus?');">Hapus</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="4">Tidak ada data barang.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
    
    <p><small>Load time: <?php echo number_format($duration, 4); ?> sec</small></p>
</body>
</html>
