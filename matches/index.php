<?php
require_once '../includes/session.php';
requireLogin();
require_once '../includes/Database.php';
require_once '../includes/Match.php';

$db = new Database();
$match = new GameMatch($db->getConnection());
$matches = $match->getAll($_SESSION['user_id']);

$wins = 0;
$total = count($matches);
foreach ($matches as $m) {
    if ($m['result'] === 'Win') {
        $wins++;
    }
}
$winrate = $total > 0 ? round(($wins / $total) * 100) : 0;

$cssPrefix = '../';
$navPrefix = '../';
include '../includes/header.php';
?>

<div style="display: flex; justify-content: space-between; align-items: center;">
    <h2>Maçlarım</h2>
    <a href="create.php" role="button">+ Yeni Maç</a>
</div>

<?php if ($total > 0): ?>
    <div class="grid">
        <article><strong><?php echo $total; ?></strong><br><small>Toplam Maç</small></article>
        <article><strong><?php echo $wins; ?></strong><br><small>Galibiyet</small></article>
        <article><strong>%<?php echo $winrate; ?></strong><br><small>Kazanma Oranı</small></article>
    </div>
<?php endif; ?>

<?php if ($total === 0): ?>
    <p>Henüz maç kaydı yok. Yeni bir maç ekleyebilirsin.</p>
<?php else: ?>
    <table>
        <thead>
            <tr>
                <th>Oyuncu</th>
                <th>Şampiyon</th>
                <th>Sonuç</th>
                <th>KDA</th>
                <th>Tarih</th>
                <th>İşlemler</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($matches as $m): ?>
                <tr>
                    <td><?php echo htmlspecialchars($m['summoner_name']); ?></td>
                    <td><?php echo htmlspecialchars($m['champion']); ?></td>
                    <td>
                        <?php if ($m['result'] === 'Win'): ?>
                            <ins>Win</ins>
                        <?php else: ?>
                            <del>Lose</del>
                        <?php endif; ?>
                    </td>
                    <td><?php echo $m['kills']; ?> / <?php echo $m['deaths']; ?> / <?php echo $m['assists']; ?></td>
                    <td><?php echo htmlspecialchars($m['match_date']); ?></td>
                    <td>
                        <a href="edit.php?id=<?php echo $m['id']; ?>" role="button" class="outline">Düzenle</a>
                        <a href="delete.php?id=<?php echo $m['id']; ?>" role="button" class="secondary" onclick="return confirm('Bu maçı silmek istediğine emin misin?');">Sil</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<?php include '../includes/footer.php'; ?>
