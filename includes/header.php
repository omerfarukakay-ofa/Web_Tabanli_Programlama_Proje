<!DOCTYPE html>
<html lang="tr" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LoL Maç Takip</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">
    <link rel="stylesheet" href="<?php echo $cssPrefix ?? ''; ?>style.css">
</head>
<body>
    <nav class="container">
        <ul>
            <li><strong>🎮 LoL Maç Takip</strong></li>
        </ul>
        <ul>
            <?php if (isLoggedIn()): ?>
                <li><a href="<?php echo $navPrefix ?? ''; ?>players/index.php">Oyuncular</a></li>
                <li><a href="<?php echo $navPrefix ?? ''; ?>matches/index.php">Maçlar</a></li>
                <li><a href="<?php echo $navPrefix ?? ''; ?>auth/logout.php" role="button" class="secondary">Çıkış</a></li>
            <?php endif; ?>
        </ul>
    </nav>
    <main class="container">
