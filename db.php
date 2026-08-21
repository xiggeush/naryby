<?php
// db.php
try {
    $db = new PDO('sqlite:' . __DIR__ . '/database.sqlite');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    // Tabela użytkowników
    $db->exec("CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username VARCHAR(50) UNIQUE NOT NULL,
        password_hash VARCHAR(255) NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");

    // Tabela statystyk i progresu gracza
    $db->exec("CREATE TABLE IF NOT EXISTS player_stats (
        user_id INTEGER PRIMARY KEY,
        level INTEGER DEFAULT 1,
        exp INTEGER DEFAULT 0,
        coins INTEGER DEFAULT 100,
        energy INTEGER DEFAULT 100,
        max_energy INTEGER DEFAULT 100,
        rod_id INTEGER DEFAULT 1,
        bait_id INTEGER DEFAULT 1,
        total_fishes_caught INTEGER DEFAULT 0,
        total_weight_caught REAL DEFAULT 0.0,
        biggest_fish_weight REAL DEFAULT 0.0,
        biggest_fish_name VARCHAR(50) DEFAULT '-',
        FOREIGN KEY (user_id) REFERENCES users(id)
    )");

    // Tabela ryb
    $db->exec("CREATE TABLE IF NOT EXISTS fish (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name VARCHAR(50),
        min_weight REAL,
        max_weight REAL,
        value_per_kg INTEGER,
        exp_per_kg INTEGER,
        difficulty INTEGER
    )");

    // Tabela przedmiotów
    $db->exec("CREATE TABLE IF NOT EXISTS items (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name VARCHAR(50),
        type VARCHAR(20),
        power INTEGER,
        price INTEGER
    )");

    // Tabela ekwipunku
    $db->exec("CREATE TABLE IF NOT EXISTS inventory (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER,
        fish_name VARCHAR(50),
        weight REAL,
        value INTEGER,
        FOREIGN KEY (user_id) REFERENCES users(id)
    )");

    // Tabela rang
    $db->exec("CREATE TABLE IF NOT EXISTS ranks (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name VARCHAR(50) NOT NULL,
        min_level INTEGER NOT NULL
    )");

    // Domyślne zasiewanie danych
    if ($db->query("SELECT COUNT(*) FROM fish")->fetchColumn() == 0) {
        $db->exec("INSERT INTO fish (name, min_weight, max_weight, value_per_kg, exp_per_kg, difficulty) VALUES 
            ('Płoć', 0.1, 0.8, 15, 20, 10),
            ('Okoń', 0.2, 1.5, 25, 30, 20),
            ('Leszcz', 0.5, 3.5, 20, 25, 30),
            ('Szczupak', 1.0, 8.0, 45, 60, 50),
            ('Karp', 2.0, 15.0, 35, 50, 60),
            ('Sum', 5.0, 30.0, 60, 90, 80)");
    }

    if ($db->query("SELECT COUNT(*) FROM items")->fetchColumn() == 0) {
        $db->exec("INSERT INTO items (name, type, power, price) VALUES 
            ('Leszczowa Wędka', 'rod', 5, 0),
            ('Wędka Carbon Pro', 'rod', 20, 150),
            ('Tytanowy Kij', 'rod', 50, 500),
            ('Chleb', 'bait', 2, 0),
            ('Kukurydza', 'bait', 8, 50),
            ('Kulki Proteinowe', 'bait', 25, 200)");
    }

    if ($db->query("SELECT COUNT(*) FROM ranks")->fetchColumn() == 0) {
        $db->exec("INSERT INTO ranks (name, min_level) VALUES 
            ('Adept Wędkarstwa', 1),
            ('Młodszy Rybak', 5),
            ('Łowca Okazów', 10),
            ('Mistrz Haczyka', 20),
            ('Legenda Jezior', 50)");
    }

} catch (PDOException $e) {
    die("Błąd bazy danych: " . $e->getMessage());
}