<?php
session_start();

// If already logged in, redirect
if (isset($_SESSION['token'])) {
    header("Location: index.php");
    exit;
}

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Siapkan data untuk dikirim ke API
    $postData = [
        'email' => $email,
        'password' => $password
    ];

    // Inisialisasi cURL
    $ch = curl_init('http://localhost:8000/api/login');
    // Setel opsi-opsi cURL
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
    
    // Eksekusi permintaan dan dapatkan hasil
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // Tampilkan hasil
    if ($httpCode == 200) {
        $data = json_decode($response, true);
        if(isset($data['success']) && $data['success']) {
            $_SESSION['token'] = $data['token'];
            $_SESSION['user'] = $data['data'];
            header("Location: index.php");
            exit;
        } else {
            $error = $data['message'] ?? 'Login failed unknown error';
        }
    } else {
        $data = json_decode($response, true);
        $error = $data['message'] ?? "Login gagal. HTTP Code: $httpCode";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Storage Management</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body class="center-screen">

    <div class="card">
        <div style="text-align: center; margin-bottom: 2rem;">
            <h2 style="color: var(--primary-color); margin-bottom: 0.5rem;">Welcome</h2>
            <?php if ($error): ?>
                <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>
        </div>

        <form action="login.php" method="POST">
            <div class="form-group">
                <label for="email" class="form-label">Email Address</label>
                <input type="text" id="email" name="email" class="form-control" placeholder="Enter your email" required value="<?php echo htmlspecialchars($email ?? ''); ?>">
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <input type="password" id="password" name="password" class="form-control"
                    placeholder="Enter your password" required>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%;">Masuk</button>
        </form>
    </div>

</body>
</html>