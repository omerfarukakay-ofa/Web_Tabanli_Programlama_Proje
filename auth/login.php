<?php
require_once '../includes/session.php';
require_once '../includes/Database.php';
require_once '../includes/User.php';

if (isLoggedIn()) {
    header("Location: ../players/index.php");
    exit;
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = "Kullanıcı adı ve şifre boş olamaz.";
    } else {
        $db = new Database();
        $user = new User($db->getConnection());
        $userId = $user->login($username, $password);
        if ($userId) {
            $_SESSION['user_id'] = $userId;
            $_SESSION['username'] = $username;
            header("Location: ../players/index.php");
            exit;
        } else {
            $error = "Kullanıcı adı veya şifre hatalı.";
        }
    }
}

$cssPrefix = '../';
$navPrefix = '../';
include '../includes/header.php';
?>

<article style="max-width: 420px; margin: 2rem auto;">
    <hgroup>
        <h2>Giriş Yap</h2>
        <p>Hesabına giriş yap</p>
    </hgroup>

    <?php if ($error): ?>
        <p style="color: var(--pico-del-color);"><?php echo htmlspecialchars($error); ?></p>
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
        <button type="submit">Giriş Yap</button>
    </form>
    <p>Hesabın yok mu? <a href="register.php">Kayıt ol</a></p>
</article>

<?php include '../includes/footer.php'; ?>
