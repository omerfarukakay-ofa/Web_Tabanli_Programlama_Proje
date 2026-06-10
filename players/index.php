<?php
require_once '../includes/session.php';
requireLogin();
require_once '../includes/Database.php';
require_once '../includes/Player.php';

$db = new Database();
$player = new Player($db->getConnection());
$players = $player->getAll($_SESSION['user_id']);

$cssPrefix = '../';
$navPrefix = '../';
include '../includes/header.php';
?>

<div style="display: flex; justify-content: space-between; align-items: center;">
    <h2>Oyuncularım</h2>
    <a href="create.php" role="button">+ Yeni Oyuncu</a>
</div>

<?php if (count($players) === 0): ?>
    <p>Henüz oyuncu eklemedin. Maç kaydı yapmadan önce bir oyuncu eklemelisin.</p>
<?php else: ?>
    <table>
        <thead>
            <tr>
                <th>Summoner Adı</th>
                <th>Rol</th>
                <th>Rank</th>
                <th>İşlemler</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($players as $p): ?>
                <tr>
                    <td><?php echo htmlspecialchars($p['summoner_name']); ?></td>
                    <td><?php echo htmlspecialchars($p['role']); ?></td>
                    <td><?php echo htmlspecialchars($p['rank_tier']); ?></td>
                    <td>
                        <a href="edit.php?id=<?php echo $p['id']; ?>" role="button" class="outline">Düzenle</a>
                        <a href="delete.php?id=<?php echo $p['id']; ?>" role="button" class="secondary" onclick="return confirm('Bu oyuncuyu silmek istediğine emin misin? Oyuncuya ait maçlar da silinecek.');">Sil</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<?php include '../includes/footer.php'; ?>
