<?php

@include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:login.php');
    exit();
}

if (isset($_POST['update_order'])) {
    $order_id = $_POST['order_id'];
    $update_payment = $_POST['update_payment'];
    $update_payment = filter_var($update_payment, FILTER_SANITIZE_STRING);
    
    // Atualizar o status do pagamento na tabela apropriada
    $update_orders = $conn->prepare("UPDATE pedidos SET status_pagamento = ? WHERE id = ?");
    $update_orders->execute([$update_payment, $order_id]);
    $message[] = 'O pagamento foi atualizado!';
}

if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    
    // Apagar um pedido da tabela apropriada
    $delete_orders = $conn->prepare("DELETE FROM pedidos WHERE id = ?");
    $delete_orders->execute([$delete_id]);
    header('location:admin_orders.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedidos</title>

   
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    
    <link rel="stylesheet" href="css/admin_style.css">

</head>
<body>
   
<?php include 'admin_header.php'; ?>

<section class="placed-orders">

    <h1 class="title">Pedidos Feitos</h1>

    <div class="box-container">

        <?php
        $select_orders = $conn->prepare("SELECT * FROM pedidos");
        $select_orders->execute();
        if ($select_orders->rowCount() > 0) {
            while ($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)) {
        ?>
        <div class="box">
            <p>ID do Usuário: <span><?= isset($fetch_orders['usuario_id']) ? htmlspecialchars($fetch_orders['usuario_id']) : 'Não disponível'; ?></span></p>
            <p>Colocado em: <span><?= isset($fetch_orders['data_pedido']) ? htmlspecialchars($fetch_orders['data_pedido']) : 'Não disponível'; ?></span></p>
            <p>Nome: <span><?= isset($fetch_orders['nome']) ? htmlspecialchars($fetch_orders['nome']) : 'Não disponível'; ?></span></p>
            <p>Email: <span><?= isset($fetch_orders['email']) ? htmlspecialchars($fetch_orders['email']) : 'Não disponível'; ?></span></p>
            <p>Número: <span><?= isset($fetch_orders['telefone']) ? htmlspecialchars($fetch_orders['telefone']) : 'Não disponível'; ?></span></p>
            <p>Endereço: <span><?= isset($fetch_orders['endereco']) ? htmlspecialchars($fetch_orders['endereco']) : 'Não disponível'; ?></span></p>
            <p>Total Produtos: <span><?= isset($fetch_orders['total_produtos']) ? htmlspecialchars($fetch_orders['total_produtos']) : 'Não disponível'; ?></span></p>
            <p>Preço Total: <span>R$<?= isset($fetch_orders['preco_total']) ? htmlspecialchars($fetch_orders['preco_total']) : 'Não disponível'; ?>/-</span></p>
            <p>Método de Pagamento: <span><?= isset($fetch_orders['metodo']) ? htmlspecialchars($fetch_orders['metodo']) : 'Não disponível'; ?></span></p>
            <form action="" method="POST">
                <input type="hidden" name="order_id" value="<?= htmlspecialchars($fetch_orders['id']); ?>">
                <select name="update_payment" class="drop-down">
                    <option value="" selected disabled><?= htmlspecialchars($fetch_orders['status_pagamento']); ?></option>
                    <option value="pendente">Pendente</option>
                    <option value="concluído">Concluído</option>
                </select>
                <div class="flex-btn">
                    <input type="submit" name="update_order" class="option-btn" value="Atualizar">
                    <a href="admin_orders.php?delete=<?= htmlspecialchars($fetch_orders['id']); ?>" class="delete-btn" onclick="return confirm('Apagar este pedido?');">Deletar</a>
                </div>
            </form>
        </div>
        <?php
            }
        } else {
            echo '<p class="empty">Nenhum pedido feito ainda!</p>';
        }
        ?>

    </div>

</section>

<script src="js/script.js"></script>

</body>
</html>
