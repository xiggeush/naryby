<!-- includes/drawers.php -->

<!-- DRAWER 1: SIATKA -->
<div class="side-drawer right" id="drawerBasket">
    <div class="drawer-header">
        <h4 class="m-0 fw-bold"><i class="bi bi-basket2-fill text-warning me-2"></i>Siatka na ryby</h4>
        <button class="btn-close btn-close-white" onclick="toggleDrawer('drawerBasket')"></button>
    </div>
    <div class="drawer-body">
        <div id="basketList">
            <?php if (empty($inventory)): ?>
                <p class="text-center text-muted py-5">Siatka jest pusta.<br>Zarzuć wędkę!</p>
            <?php else: ?>
                <?php foreach ($inventory as $f): ?>
                    <div class="item-row">
                        <div>
                            <div class="fw-bold">🐟 <?= htmlspecialchars($f['fish_name']) ?></div>
                            <small class="text-muted"><?= $f['weight'] ?> kg</small>
                        </div>
                        <span class="badge bg-success bg-opacity-20 text-success border border-success border-opacity-25 fs-6">
                            +<?= $f['value'] ?> 💰
                        </span>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    <div class="pt-3 border-top border-secondary">
        <button class="btn btn-success btn-lg w-100 fw-bold rounded-3" onclick="sellAllFish()">
            SPRZEDAJ WSZYSTKO 💰
        </button>
    </div>
</div>

<!-- DRAWER 2: SKLEP -->
<div class="side-drawer left" id="drawerShop">
    <div class="drawer-header">
        <h4 class="m-0 fw-bold"><i class="bi bi-shop text-info me-2"></i>Sklep ze sprzętem</h4>
        <button class="btn-close btn-close-white" onclick="toggleDrawer('drawerShop')"></button>
    </div>
    <div class="drawer-body">
        <div class="mb-3 p-3 rounded-3 bg-white bg-opacity-10 border border-white border-opacity-10">
            <small class="text-muted d-block mb-1">Wyposażony sprzęt:</small>
            <div><strong>Wędka:</strong> <?= htmlspecialchars($player['rod_name']) ?></div>
            <div><strong>Przynęta:</strong> <?= htmlspecialchars($player['bait_name']) ?></div>
        </div>

        <h6 class="text-muted uppercase small fw-bold mb-3">Dostępny sprzęt</h6>
        <?php foreach ($shopItems as $item): ?>
            <div class="item-row">
                <div>
                    <div class="fw-bold"><?= htmlspecialchars($item['name']) ?></div>
                    <small class="text-info">+<?= $item['power'] ?> Mocy</small>
                </div>
                <button class="btn btn-outline-warning btn-sm fw-bold px-3 rounded-pill" onclick="buyItem(<?= $item['id'] ?>)">
                    Kup (<?= $item['price'] ?> 💰)
                </button>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- DRAWER 3: RANKING TOP 10 -->
<div class="side-drawer right" id="drawerLeaderboard">
    <div class="drawer-header">
        <h4 class="m-0 fw-bold"><i class="bi bi-trophy-fill text-warning me-2"></i>Ranking Graczy</h4>
        <button class="btn-close btn-close-white" onclick="toggleDrawer('drawerLeaderboard')"></button>
    </div>
    <div class="drawer-body">
        <table class="table table-dark table-hover align-middle small">
            <thead>
                <tr class="text-muted">
                    <th>#</th>
                    <th>Gracz</th>
                    <th>Lvl</th>
                    <th>Złowiono (kg)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($leaderboard as $idx => $top): ?>
                    <tr class="<?= ($top['username'] === $player['username']) ? 'table-warning text-dark fw-bold' : '' ?>">
                        <td><?= $idx + 1 ?></td>
                        <td><?= htmlspecialchars($top['username']) ?></td>
                        <td><span class="badge bg-primary"><?= $top['level'] ?></span></td>
                        <td><?= number_format($top['total_weight_caught'], 1) ?> kg</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- DRAWER 4: PROFIL GRACZA -->
<div class="side-drawer left" id="drawerProfile">
    <div class="drawer-header">
        <h4 class="m-0 fw-bold"><i class="bi bi-person-badge-fill text-warning me-2"></i>Profil Gracza</h4>
        <button class="btn-close btn-close-white" onclick="toggleDrawer('drawerProfile')"></button>
    </div>
    <div class="drawer-body">
        <div class="text-center p-3 mb-3 bg-white bg-opacity-10 rounded-4 border border-white border-opacity-10">
            <h3 class="fw-bold text-warning mb-0"><?= htmlspecialchars($player['username']) ?></h3>
            <span class="badge bg-info mt-1"><?= getPlayerRank($db, $player['level']) ?></span>
        </div>

        <h6 class="text-muted uppercase small fw-bold mb-2">Statystyki kariery</h6>
        <div class="item-row">
            <span>Złowione ryby</span>
            <strong class="text-white"><?= $player['total_fishes_caught'] ?> szt.</strong>
        </div>
        <div class="item-row">
            <span>Łączna waga</span>
            <strong class="text-white"><?= number_format($player['total_weight_caught'], 2) ?> kg</strong>
        </div>
        <div class="item-row">
            <span>Największy okaz</span>
            <strong class="text-warning"><?= htmlspecialchars($player['biggest_fish_name']) ?> (<?= $player['biggest_fish_weight'] ?> kg)</strong>
        </div>
    </div>
</div>