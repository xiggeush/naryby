<?php
// login.php
require_once __DIR__ . '/config.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!empty($username) && !empty($password)) {
        if ($action === 'register') {
            $stmt = $db->prepare("SELECT id FROM users WHERE username = ?");
            $stmt->execute([$username]);
            if ($stmt->fetch()) {
                $error = "Ta nazwa użytkownika jest już zajęta!";
            } else {
                $hash = password_hash($password, PASSWORD_BCRYPT);
                $stmt = $db->prepare("INSERT INTO users (username, password_hash) VALUES (?, ?)");
                $stmt->execute([$username, $hash]);
                $userId = $db->lastInsertId();

                $stmt = $db->prepare("INSERT INTO player_stats (user_id) VALUES (?)");
                $stmt->execute([$userId]);

                $success = "Konto utworzone! Możesz się teraz zalogować.";
            }
        } elseif ($action === 'login') {
            $stmt = $db->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password_hash'])) {
                $_SESSION['user_id'] = $user['id'];
                header('Location: index.php');
                exit;
            } else {
                $error = "Nieprawidłowy login lub hasło!";
            }
        }
    } else {
        $error = "Uzupełnij wszystkie pola!";
    }
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>NaRyby - Logowanie</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body class="d-flex align-items-center justify-content-center vh-100 bg-dark text-white">

<div class="card bg-secondary bg-opacity-20 border-secondary p-4 text-white" style="width: 380px; backdrop-filter: blur(10px);">
    <h2 class="text-center fw-bold text-warning mb-4">🎣 NaRyby Sim</h2>

    <?php if ($error): ?>
        <div class="alert alert-danger py-2 small"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
        <div class="alert alert-success py-2 small"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label class="form-label small text-muted">Nazwa gracza</label>
            <input type="text" name="username" class="form-control bg-dark text-white border-secondary" required>
        </div>
        <div class="mb-3">
            <label class="form-label small text-muted">Hasło</label>
            <input type="password" name="password" class="form-control bg-dark text-white border-secondary" required>
        </div>
        <div class="d-grid gap-2 mt-4">
            <button type="submit" name="action" value="login" class="btn btn-warning fw-bold">ZALOGUJ SIĘ</button>
            <button type="submit" name="action" value="register" class="btn btn-outline-light btn-sm">ZAREJESTRUJ SIĘ</button>
        </div>
    </form>
</div>

</body>
</html>