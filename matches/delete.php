<?php
require_once '../includes/session.php';
requireLogin();
require_once '../includes/Database.php';
require_once '../includes/Match.php';

$id = (int)($_GET['id'] ?? 0);
$db = new Database();
$match = new GameMatch($db->getConnection());
$match->delete($id, $_SESSION['user_id']);

header("Location: index.php");
exit;
