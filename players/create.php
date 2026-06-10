<?php
require_once '../includes/session.php';
requireLogin();
require_once '../includes/Database.php';
require_once '../includes/Player.php';

$roles = ['Top', 'Jungle', 'Mid', 'ADC', 'Support'];
$ranks = ['Iron', 'Bronze', 'Silver', 'Gold', 'Platinum', 'Emerald', 'Diamond', 'Master', 'Grandmaster', 'Challenger'];

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['summoner_name'] ?? '');
    $role = $_POST['role'] ?? '';
    $rank = $_POST['rank_tier'] ?? '';

    if ($name === '' || !in_array($role, $roles) || !in_array($rank, $ranks)) {
        $error = "Lütfen tüm alanları doğru şekilde doldur.";
    } else {
        $db = new Database();
        $player = new Player($db->getConnection());
        if ($player->create($_SESSION['user_id'], $name, $role, $rank)) {
            header("Location: index.php");
            exit;
        } else {
            $error = "Oyuncu eklenirken bir hata oluştu.";
        }
    }
}

$cssPrefix = '../';
$navPrefix = '../';
include '../includes/header.php';
?>

<article style="max-width: 480px; margin: 2rem auto;">
    <h2>Yeni Oyuncu Ekle</h2>

    <?php if ($error): ?>
        <p style="color: var(--pico-del-color);"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <form method="POST">
        <label>
            Summoner Adı
            <input type="text" name="summoner_name" required>
        </label>
        <label>
            Rol
            <select name="role" required>
                <option value="">Seçiniz</option>
                <?php foreach ($roles as $r): ?>
                    <option value="<?php echo $r; ?>"><?php echo $r; ?></option>
                <?php endforeach; ?>
            </select>
        </label>
        <label>
            Rank
            <select name="rank_tier" required>
                <option value="">Seçiniz</option>
                <?php foreach ($ranks as $r): ?>
                    <option value="<?php echo $r; ?>"><?php echo $r; ?></option>
                <?php endforeach; ?>
            </select>
        </label>
        <button type="submit">Kaydet</button>
        <a href="index.php" role="button" class="secondary">İptal</a>
    </form>
</article>

<?php include '../includes/footer.php'; ?>
