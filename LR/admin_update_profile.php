<?php

@include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:login.php');
    exit;
}

if (isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']); // Sanitiza a entrada para garantir que é um número

    if ($delete_id) {
        $delete_message = $conn->prepare("DELETE FROM message WHERE id = ?");
        $delete_message->execute([$delete_id]);
        header('location:admin_contato.php');
        exit;
    } else {
        echo '<p class="error">ID de mensagem inválido!</p>';
    }
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mensagens</title>

   
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   
    <link rel="stylesheet" href="css/admin_style.css">

</head>
<body>
   
<?php include 'admin_header.php'; ?>

<section class="messages">
    <h1 class="title">Mensagens</h1>

    <div class="box-container">
        <?php
        $select_message = $conn->prepare("SELECT * FROM message");
        $select_message->execute();

        if ($select_message->rowCount() > 0) {
            while ($fetch_message = $select_message->fetch(PDO::FETCH_ASSOC)) {
        ?>
        <div class="box">
            <p>ID do usuário: <span><?= htmlspecialchars($fetch_message['user_id']); ?></span></p>
            <p>Nome: <span><?= htmlspecialchars($fetch_message['name']); ?></span></p>
            <p>Número: <span><?= htmlspecialchars($fetch_message['number']); ?></span></p>
            <p>Email: <span><?= htmlspecialchars($fetch_message['email']); ?></span></p>
            <p>Mensagens: <span><?= htmlspecialchars($fetch_message['message']); ?></span></p>
            <a href="admin_contato.php?delete=<?= htmlspecialchars($fetch_message['id']); ?>" onclick="return confirm('Tem certeza de que deseja apagar esta mensagem?');" class="delete-btn">Excluir</a>
        </div>
        <?php
            }
        } else {
            echo '<p class="empty">Você não tem mensagens!</p>';
        }
        ?>
    </div>
</section>

<script src="js/script.js"></script>

</body>
</html>
