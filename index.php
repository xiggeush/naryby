<?php
// index.php
require_once __DIR__ . '/config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$player = getPlayerData($db, $_SESSION['user_id']);

$stmt = $db->prepare("SELECT * FROM inventory WHERE user_id = ? ORDER BY id DESC");
$stmt->execute([$_SESSION['user_id']]);
$inventory = $stmt->fetchAll();

$shopItems = $db->query("SELECT * FROM items")->fetchAll();
$leaderboard = getLeaderboard($db);
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NaRyby - Fullscreen Simulator</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>

<div class="game-viewport" id="viewport">
    <div class="water-ambient"></div>

    <?php include __DIR__ . '/includes/hud.php'; ?>

    <div class="lake-click-area" onclick="triggerCast()"></div>
    <div class="bobber" id="bobber"></div>

    <div class="action-center-bottom">
        <button class="btn-cast-main" id="btnCast" onclick="triggerCast()">
            <i class="bi bi-water me-2"></i> Zarzuć Wędkę (-10⚡)
        </button>
    </div>

    <?php include __DIR__ . '/includes/minigame.php'; ?>
    <?php include __DIR__ . '/includes/drawers.php'; ?>
</div>

<script src="js/game.js"></script>
</body>
</html>