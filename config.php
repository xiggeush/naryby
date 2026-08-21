<?php
// config.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/db.php';

function getPlayerData($db, $userId) {
    $stmt = $db->prepare("
        SELECT u.username, s.*, 
               r.name as rod_name, r.power as rod_power, 
               b.name as bait_name, b.power as bait_power 
        FROM users u
        JOIN player_stats s ON u.id = s.user_id
        LEFT JOIN items r ON s.rod_id = r.id 
        LEFT JOIN items b ON s.bait_id = b.id 
        WHERE u.id = ?
    ");
    $stmt->execute([$userId]);
    return $stmt->fetch();
}

function getPlayerRank($db, $level) {
    $stmt = $db->prepare("SELECT name FROM ranks WHERE min_level <= ? ORDER BY min_level DESC LIMIT 1");
    $stmt->execute([$level]);
    $rank = $stmt->fetch();
    return $rank ? $rank['name'] : 'Nowicjusz';
}

function getLeaderboard($db) {
    return $db->query("
        SELECT u.username, s.level, s.total_weight_caught, s.biggest_fish_name, s.biggest_fish_weight
        FROM player_stats s
        JOIN users u ON u.id = s.user_id
        ORDER BY s.level DESC, s.total_weight_caught DESC
        LIMIT 10
    ")->fetchAll();
}