<?php
// Inicie a sessão se ainda não estiver iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_id'])) {
    die('Usuário não está logado.');
}

$admin_id = $_SESSION['admin_id'];

// Supondo que você já tenha uma conexão com o banco de dados em `$conn`
$select_profile = $conn->prepare("SELECT * FROM usuarios WHERE id = ?");
$select_profile->execute([$admin_id]);
$fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css"> <!-- Ajuste o caminho para o seu CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <title>Painel Administrativo</title>
</head>
<body>

<?php
// Exibir mensagens se existirem
if (isset($message)) {
    foreach ($message as $msg) {
        echo '
        <div class="message">
            <span>' . htmlspecialchars($msg, ENT_QUOTES, 'UTF-8') . '</span>
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
        </div>
        ';
    }
}
?>

<header class="header">

    <div class="flex">

        <a href="admin_page.php" class="logo">Admin<span>Painel</span></a>

        <nav class="navbar">
            <a href="admin_page.php">Home</a>
            <a href="admin_products.php">Produtos</a>
            <a href="admin_orders.php">Pedidos</a>
            <a href="admin_users.php">Usuários</a>
            <a href="admin_contato.php">Mensagens</a>
        </nav>

        <div class="icons">
            <div id="menu-btn" class="fas fa-bars"></div>
            <div id="user-btn" class="fas fa-user"></div>
        </div>

        <div class="profile">
            <?php if ($fetch_profile): ?>
                <img src="uploaded_img/<?= htmlspecialchars($fetch_profile['imagem'], ENT_QUOTES, 'UTF-8'); ?>" alt="">
                <p><?= htmlspecialchars($fetch_profile['nome'], ENT_QUOTES, 'UTF-8'); ?></p>
                <a href="admin_update_profile.php" class="btn">Atualizar Perfil</a>
                <a href="logout.php" class="delete-btn">Sair</a>
            <?php else: ?>
                <p>Perfil não encontrado.</p>
            <?php endif; ?>
            <div class="flex-btn">
                <a href="login.php" class="option-btn">Login</a>
                <a href="register.php" class="option-btn">Registrar</a>
            </div>
        </div>

    </div>

</header>

</body>
</html>
