<!-- includes/hud.php -->
<div class="hud-top">
    <div class="hud-group">
        <div class="hud-pill" onclick="toggleDrawer('drawerProfile')" style="cursor: pointer;" title="Kliknij, aby zobaczyć profil">
            <i class="bi bi-person-circle text-warning"></i>
            <span><?= htmlspecialchars($player['username']) ?> (Lvl <strong><?= $player['level'] ?></strong>)</span>
        </div>
        <div class="hud-pill">
            <i class="bi bi-lightning-charge-fill text-warning"></i>
            <span id="hudEnergy"><?= $player['energy'] ?></span>/100
        </div>
        <div class="hud-pill">
            <i class="bi bi-coin text-warning"></i>
            <span class="text-success" id="hudCoins"><?= number_format($player['coins']) ?></span>
        </div>
    </div>

    <div class="hud-group">
        <button class="hud-icon-btn" onclick="toggleDrawer('drawerLeaderboard')" title="Ranking Graczy">
            <i class="bi bi-trophy-fill text-warning"></i>
        </button>
        <button class="hud-icon-btn" onclick="toggleDrawer('drawerShop')" title="Sklep">
            <i class="bi bi-shop"></i>
        </button>
        <button class="hud-icon-btn" onclick="toggleDrawer('drawerBasket')" title="Siatka">
            <i class="bi bi-basket2-fill"></i>
        </button>
        <a href="logout.php" class="hud-icon-btn text-danger" title="Wyloguj się">
            <i class="bi bi-box-arrow-right"></i>
        </a>
    </div>
</div>