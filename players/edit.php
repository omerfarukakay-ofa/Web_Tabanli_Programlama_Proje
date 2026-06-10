<?php
require_once '../includes/session.php';
requireLogin();
require_once '../includes/Database.php';
require_once '../includes/Player.php';

$roles = ['Top', 'Jungle', 'Mid', 'ADC', 'Support'];
$ranks = ['Iron', 'Bronze', 'Silver', 'Gold', 'Platinum', 'Emerald', 'Diamond', 'Master', 'Grandmaster', 'Challenger'];

$id = (int)($_GET['id'] ?? 0);
$db = new Database();
$player = new Player($db->getConnection());
$data = $player->getById($id, $_SESSION['user_id']);

if (!$data) {
    header("Location: index.php");
    exit;
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['summoner_name'] ?? '');
    $role = $_POST['role'] ?? '';
    $rank = $_POST['rank_tier'] ?? '';

    if ($name === '' || !in_array($role, $roles) || !in_array($rank, $ranks)) {
        $error = "Lütfen tüm alanları doğru şekilde doldur.";
    } else {
        if ($player->update($id, $_SESSION['user_id'], $name, $role, $rank)) {
            header("Location: index.php");
            exit;
        } else {
            $error = "Güncelleme sırasında bir hata oluştu.";
        }
    }
}

$cssPrefix = '../';
$navPrefix = '../';
include '../includes/header.php';
?>

<article style="max-width: 480px; margin: 2rem auto;">
    <h2>Oyuncu Düzenle</h2>

    <?php if ($error): ?>
        <p style="color: var(--pico-del-color);"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <form method="POST">
        <label>
            Summoner Adı
            <input type="text" name="summoner_name" value="<?php echo htmlspecialchars($data['summoner_name']); ?>" required>
        </label>
        <label>
            Rol
            <select name="role" required>
                <?php foreach ($roles as $r): ?>
                    <option value="<?php echo $r; ?>" <?php echo $data['role'] === $r ? 'selected' : ''; ?>><?php echo $r; ?></option>
                <?php endforeach; ?>
            </select>
        </label>
        <label>
            Rank
            <select name="rank_tier" required>
                <?php foreach ($ranks as $r): ?>
                    <option value="<?php echo $r; ?>" <?php echo $data['rank_tier'] === $r ? 'selected' : ''; ?>><?php echo $r; ?></option>
                <?php endforeach; ?>
            </select>
        </label>
        <button type="submit">Güncelle</button>
        <a href="index.php" role="button" class="secondary">İptal</a>
    </form>
</article>

<?php include '../includes/footer.php'; ?>
