<?php
require_once '../includes/session.php';
require_once '../includes/Database.php';
require_once '../includes/User.php';

if (isLoggedIn()) {
    header("Location: ../players/index.php");
    exit;
}

$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $password2 = $_POST['password2'] ?? '';

    if ($username === '' || $password === '') {
        $error = "Kullanıcı adı ve şifre boş olamaz.";
    } elseif (strlen($password) < 4) {
        $error = "Şifre en az 4 karakter olmalı.";
    } elseif ($password !== $password2) {
        $error = "Şifreler eşleşmiyor.";
    } else {
        $db = new Database();
        $user = new User($db->getConnection());
        $result = $user->register($username, $password);
        if ($result === true) {
            $success = "Kayıt başarılı! Giriş yapabilirsiniz.";
        } else {
            $error = $result;
        }
    }
}

$cssPrefix = '../';
$navPrefix = '../';
include '../includes/header.php';
?>

<article style="max-width: 420px; margin: 2rem auto;">
    <hgroup>
        <h2>Kayıt Ol</h2>
        <p>Yeni bir hesap oluştur</p>
    </hgroup>

    <?php if ($error): ?>
        <p style="color: var(--pico-del-color);"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
    <?php if ($success): ?>
        <p style="color: var(--pico-ins-color);"><?php echo htmlspecialchars($success); ?></p>
    <?php endif; ?>

    <form method="POST">
        <label>
            Kullanıcı Adı
            <input type="text" name="username" required>
        </label>
        <label>
            Şifre
            <input type="password" name="password" required>
        </label>
        <label>
            Şifre (Tekrar)
            <input type="password" name="password2" required>
        </label>
        <button type="submit">Kayıt Ol</button>
    </form>
    <p>Zaten hesabın var mı? <a href="login.php">Giriş yap</a></p>
</article>

<?php include '../includes/footer.php'; ?>
