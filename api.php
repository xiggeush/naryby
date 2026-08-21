<?php
// api.php
require_once __DIR__ . '/config.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Niezalogowany!']);
    exit;
}

$userId = $_SESSION['user_id'];
$action = $_POST['action'] ?? '';
$player = getPlayerData($db, $userId);

if ($action === 'catch_success') {
    if ($player['energy'] < 10) {
        echo json_encode(['status' => 'error', 'message' => 'Za mało energii!']);
        exit;
    }

    $db->prepare("UPDATE player_stats SET energy = energy - 10 WHERE user_id = ?")->execute([$userId]);
    $totalPower = $player['rod_power'] + $player['bait_power'];
    
    $stmt = $db->prepare("SELECT * FROM fish WHERE difficulty <= ? ORDER BY RANDOM() LIMIT 1");
    $stmt->execute([$totalPower + 10]);
    $caughtFish = $stmt->fetch();

    if ($caughtFish) {
        $weight = round(rand($caughtFish['min_weight'] * 10, $caughtFish['max_weight'] * 10) / 10, 2);
        $value = round($weight * $caughtFish['value_per_kg']);
        $exp = round($weight * $caughtFish['exp_per_kg']);

        // Wstawienie do ekwipunku gracza
        $stmt = $db->prepare("INSERT INTO inventory (user_id, fish_name, weight, value) VALUES (?, ?, ?, ?)");
        $stmt->execute([$userId, $caughtFish['name'], $weight, $value]);

        // Aktualizacja statystyk i rekordu
        $newExp = $player['exp'] + $exp;
        $newLevel = $player['level'];
        if ($newExp >= $player['level'] * 100) { $newLevel++; }

        $biggestWeight = max($player['biggest_fish_weight'], $weight);
        $biggestName = ($weight > $player['biggest_fish_weight']) ? $caughtFish['name'] : $player['biggest_fish_name'];

        $stmt = $db->prepare("
            UPDATE player_stats SET 
                exp = ?, level = ?, 
                total_fishes_caught = total_fishes_caught + 1,
                total_weight_caught = total_weight_caught + ?,
                biggest_fish_weight = ?,
                biggest_fish_name = ?
            WHERE user_id = ?
        ");
        $stmt->execute([$newExp, $newLevel, $weight, $biggestWeight, $biggestName, $userId]);

        echo json_encode([
            'status' => 'success',
            'fish' => $caughtFish['name'],
            'weight' => $weight,
            'value' => $value,
            'exp' => $exp
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Ryba uciekła!']);
    }
    exit;
}

if ($action === 'sell_all') {
    $stmt = $db->prepare("SELECT SUM(value) as total FROM inventory WHERE user_id = ?");
    $stmt->execute([$userId]);
    $totalValue = $stmt->fetch()['total'] ?? 0;

    if ($totalValue > 0) {
        $db->prepare("UPDATE player_stats SET coins = coins + ? WHERE user_id = ?")->execute([$totalValue, $userId]);
        $db->prepare("DELETE FROM inventory WHERE user_id = ?")->execute([$userId]);
    }
    echo json_encode(['status' => 'success', 'earned' => $totalValue]);
    exit;
}

if ($action === 'buy_item') {
    $itemId = (int)$_POST['item_id'];
    $item = $db->query("SELECT * FROM items WHERE id = $itemId")->fetch();

    if ($item && $player['coins'] >= $item['price']) {
        $db->prepare("UPDATE player_stats SET coins = coins - ? WHERE user_id = ?")->execute([$item['price'], $userId]);
        $column = ($item['type'] === 'rod') ? 'rod_id' : 'bait_id';
        $db->prepare("UPDATE player_stats SET $column = ? WHERE user_id = ?")->execute([$item['id'], $userId]);
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Za mało monet!']);
    }
    exit;
}