<?php
require_once '../includes/session.php';
requireLogin();
require_once '../includes/Database.php';
require_once '../includes/Player.php';
require_once '../includes/Match.php';

$db = new Database();
$player = new Player($db->getConnection());
$match = new GameMatch($db->getConnection());
$players = $player->getAll($_SESSION['user_id']);

$error = "";

if (count($players) === 0) {
    $cssPrefix = '../';
    $navPrefix = '../';
    include '../includes/header.php';
    echo '<article style="max-width: 480px; margin: 2rem auto;"><h2>Önce Oyuncu Ekle</h2><p>Maç kaydı yapabilmek için önce en az bir oyuncu eklemelisin.</p><a href="../players/create.php" role="button">Oyuncu Ekle</a></article>';
    include '../includes/footer.php';
    exit;
}

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
        if ($match->create($_SESSION['user_id'], $playerId, $champion, $result, $kills, $deaths, $assists, $date)) {
            header("Location: index.php");
            exit;
        } else {
            $error = "Maç eklenirken bir hata oluştu.";
        }
    }
}

$cssPrefix = '../';
$navPrefix = '../';
include '../includes/header.php';
?>

<article style="max-width: 480px; margin: 2rem auto;">
    <h2>Yeni Maç Ekle</h2>

    <?php if ($error): ?>
        <p style="color: var(--pico-del-color);"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <form method="POST">
        <label>
            Oyuncu
            <select name="player_id" required>
                <option value="">Seçiniz</option>
                <?php foreach ($players as $p): ?>
                    <option value="<?php echo $p['id']; ?>"><?php echo htmlspecialchars($p['summoner_name']); ?> (<?php echo $p['role']; ?>)</option>
                <?php endforeach; ?>
            </select>
        </label>
        <label>
            Şampiyon
            <input type="text" name="champion" required>
        </label>
        <label>
            Sonuç
            <select name="result" required>
                <option value="">Seçiniz</option>
                <option value="Win">Win</option>
                <option value="Lose">Lose</option>
            </select>
        </label>
        <div class="grid">
            <label>
                Kill
                <input type="number" name="kills" min="0" value="0" required>
            </label>
            <label>
                Death
                <input type="number" name="deaths" min="0" value="0" required>
            </label>
            <label>
                Assist
                <input type="number" name="assists" min="0" value="0" required>
            </label>
        </div>
        <label>
            Tarih
            <input type="date" name="match_date" required>
        </label>
        <button type="submit">Kaydet</button>
        <a href="index.php" role="button" class="secondary">İptal</a>
    </form>
</article>

<?php include '../includes/footer.php'; ?>
