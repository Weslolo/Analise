<?php

@include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:login.php');
    exit;
}

if (isset($_GET['delete'])) {
    $delete_id = filter_var($_GET['delete'], FILTER_SANITIZE_NUMBER_INT);
    if ($delete_id && $delete_id != $admin_id) { // Evita a exclusão do próprio admin
        try {
            $delete_users = $conn->prepare("DELETE FROM `usuarios` WHERE id = ?");
            $delete_users->execute([$delete_id]);
            header('location:admin_users.php');
            exit;
        } catch (PDOException $e) {
            echo 'Erro ao excluir o usuário: ' . htmlspecialchars($e->getMessage());
        }
    } else {
        echo 'ID de usuário inválido ou tentativa de exclusão do próprio admin.';
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuários</title>

    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    
    <link rel="stylesheet" href="css/admin_style.css">

</head>
<body>
   
<?php include 'admin_header.php'; ?>

<section class="user-accounts">

    <h1 class="title">Contas de Usuário</h1>

    <div class="box-container">

    <?php
        try {
            $select_users = $conn->prepare("SELECT * FROM `usuarios`");
            $select_users->execute();
            while ($fetch_users = $select_users->fetch(PDO::FETCH_ASSOC)) {
    ?>
    <div class="box" style="<?php if ($fetch_users['id'] == $admin_id) { echo 'display:none'; } ?>">
        <img src="uploaded_img/<?= htmlspecialchars($fetch_users['imagem']); ?>" alt="Imagem do usuário">
        <p> Usuário ID: <span><?= htmlspecialchars($fetch_users['id']); ?></span></p>
        <p> Nome: <span><?= htmlspecialchars($fetch_users['nome']); ?></span></p>
        <p> Email: <span><?= htmlspecialchars($fetch_users['email']); ?></span></p>
        <p> Tipo de usuário: <span style="color:<?= ($fetch_users['tipo_usuario'] == 'admin') ? 'orange' : 'black'; ?>"><?= htmlspecialchars($fetch_users['tipo_usuario']); ?></span></p>
        <a href="admin_users.php?delete=<?= htmlspecialchars($fetch_users['id']); ?>" onclick="return confirm('Tem certeza de que deseja excluir este usuário?');" class="delete-btn">Excluir</a>
    </div>
    <?php
            }
        } catch (PDOException $e) {
            echo 'Erro ao carregar usuários: ' . htmlspecialchars($e->getMessage());
        }
    ?>

    </div>

</section>

<script src="js/script.js"></script>

</body>
</html>
