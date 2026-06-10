<?php
require_once '../includes/session.php';
requireLogin();
require_once '../includes/Database.php';
require_once '../includes/Player.php';

$id = (int)($_GET['id'] ?? 0);
$db = new Database();
$player = new Player($db->getConnection());
$player->delete($id, $_SESSION['user_id']);

header("Location: index.php");
exit;
