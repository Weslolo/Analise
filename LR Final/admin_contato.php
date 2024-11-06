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
    if ($delete_id) {
        try {
            $delete_message = $conn->prepare("DELETE FROM `mensagem` WHERE id = ?");
            $delete_message->execute([$delete_id]);
            header('location:admin_contato.php');
            exit;
        } catch (PDOException $e) {
            echo 'Erro ao excluir a mensagem: ' . htmlspecialchars($e->getMessage());
        }
    } else {
        echo 'ID de mensagem inválido.';
    }
}

?>

<!DOCTYPE html>
<html lang="en">
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
        try {
            $select_message = $conn->prepare("SELECT * FROM `mensagem`");
            $select_message->execute();
            if ($select_message->rowCount() > 0) {
                while ($fetch_message = $select_message->fetch(PDO::FETCH_ASSOC)) {
    ?>
    <div class="box">
        <p> User ID: <span><?= htmlspecialchars($fetch_message['usuario_id']); ?></span> </p>
        <p> Nome: <span><?= htmlspecialchars($fetch_message['nome']); ?></span> </p>
        <p> Telefone: <span><?= htmlspecialchars($fetch_message['telefone']); ?></span> </p>
        <p> Email: <span><?= htmlspecialchars($fetch_message['email']); ?></span> </p>
        <p> Mensagem: <span><?= htmlspecialchars($fetch_message['mensagem']); ?></span> </p>
        <a href="admin_contato.php?delete=<?= htmlspecialchars($fetch_message['id']); ?>" onclick="return confirm('Tem certeza de que deseja excluir esta mensagem?');" class="delete-btn">Excluir</a>
    </div>
    <?php
                }
            } else {
                echo '<p class="empty">Você não tem mensagens!</p>';
            }
        } catch (PDOException $e) {
            echo 'Erro ao carregar mensagens: ' . htmlspecialchars($e->getMessage());
        }
    ?>

    </div>

</section>

<script src="js/script.js"></script>

</body>
</html>
