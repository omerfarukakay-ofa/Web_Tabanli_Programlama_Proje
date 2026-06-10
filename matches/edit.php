<?php
require_once '../includes/session.php';
requireLogin();
require_once '../includes/Database.php';
require_once '../includes/Player.php';
require_once '../includes/Match.php';

$id = (int)($_GET['id'] ?? 0);
$db = new Database();
$player = new Player($db->getConnection());
$match = new GameMatch($db->getConnection());

$data = $match->getById($id, $_SESSION['user_id']);
if (!$data) {
    header("Location: index.php");
    exit;
}

$players = $player->getAll($_SESSION['user_id']);
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $playerId = (int)($_POST['player_id'] ?? 0);
    $champion = trim($_POST['champion'] ?? '');
    $result = $_POST['result'] ?? '';
    $kills = (int)($_POST['kills'] ?? 0);
    $deaths = (int)($_POST['deaths'] ?? 0);
    $assists = (int)($_POST['assists'] ?? 0);
    $date = $_POST['match_date'] ?? '';

    $validPlayer = $player->getById($playerId, $_SESSION['user_id']);

    if (!$validPlayer || $champion === '' || !in_array($result, ['Win', 'Lose']) || $date === '') {
        $error = "Lütfen tüm alanları doğru şekilde doldur.";
    } else {
        if ($match->update($id, $_SESSION['user_id'], $playerId, $champion, $result, $kills, $deaths, $assists, $date)) {
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
    <h2>Maç Düzenle</h2>

    <?php if ($error): ?>
        <p style="color: var(--pico-del-color);"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <form method="POST">
        <label>
            Oyuncu
            <select name="player_id" required>
                <?php foreach ($players as $p): ?>
                    <option value="<?php echo $p['id']; ?>" <?php echo $data['player_id'] == $p['id'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($p['summoner_name']); ?> (<?php echo $p['role']; ?>)</option>
                <?php endforeach; ?>
            </select>
        </label>
        <label>
            Şampiyon
            <input type="text" name="champion" value="<?php echo htmlspecialchars($data['champion']); ?>" required>
        </label>
        <label>
            Sonuç
            <select name="result" required>
                <option value="Win" <?php echo $data['result'] === 'Win' ? 'selected' : ''; ?>>Win</option>
                <option value="Lose" <?php echo $data['result'] === 'Lose' ? 'selected' : ''; ?>>Lose</option>
            </select>
        </label>
        <div class="grid">
            <label>
                Kill
                <input type="number" name="kills" min="0" value="<?php echo $data['kills']; ?>" required>
            </label>
            <label>
                Death
                <input type="number" name="deaths" min="0" value="<?php echo $data['deaths']; ?>" required>
            </label>
            <label>
                Assist
                <input type="number" name="assists" min="0" value="<?php echo $data['assists']; ?>" required>
            </label>
        </div>
        <label>
            Tarih
            <input type="date" name="match_date" value="<?php echo htmlspecialchars($data['match_date']); ?>" required>
        </label>
        <button type="submit">Güncelle</button>
        <a href="index.php" role="button" class="secondary">İptal</a>
    </form>
</article>

<?php include '../includes/footer.php'; ?>
