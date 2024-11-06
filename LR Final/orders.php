<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'] ?? null; // Usa null como padrão se a variável não estiver definida

if (!isset($user_id)) {
    header('Location: login.php');
    exit(); // Adiciona exit para garantir que o script pare após o redirecionamento
}

?>

<!DOCTYPE html>
<html lang="pt-BR"> <!-- Altere o idioma para português do Brasil -->
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Pedidos</title>

   
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'header.php'; ?>

<section class="placed-orders">

   <h1 class="title">Pedidos Feitos</h1>

   <div class="box-container">

   <?php
      // Ajuste o nome da coluna se for diferente de `user_id`
      $select_orders = $conn->prepare("SELECT * FROM pedidos WHERE usuario_id = ?"); // Ajuste para o nome correto da coluna
      $select_orders->execute([$user_id]);
      if ($select_orders->rowCount() > 0) {
         while ($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)) { 
   ?>
   <div class="box">
      <p> Data do Pedido: <span><?= htmlspecialchars($fetch_orders['data_pedido']); ?></span> </p>
      <p> Nome: <span><?= htmlspecialchars($fetch_orders['nome']); ?></span> </p>
      <p> Número: <span><?= htmlspecialchars($fetch_orders['telefone']); ?></span> </p>
      <p> Email: <span><?= htmlspecialchars($fetch_orders['email']); ?></span> </p>
      <p> Endereço: <span><?= htmlspecialchars($fetch_orders['endereco']); ?></span> </p>
      <p> Método de Pagamento: <span><?= htmlspecialchars($fetch_orders['metodo']); ?></span> </p>
      <p> Produtos: <span><?= htmlspecialchars($fetch_orders['total_produtos']); ?></span> </p>
      <p> Preço Total: <span>R$<?= number_format($fetch_orders['preco_total'], 2, ',', '.'); ?>/-</span> </p>
      <p> Status do Pagamento: 
        <span style="color: <?= $fetch_orders['status_pagamento'] == 'pendente' ? 'red' : 'green'; ?>;">
          <?= htmlspecialchars($fetch_orders['status_pagamento']); ?>
        </span>
      </p>
   </div>
   <?php
         }
      } else {
         echo '<p class="empty">Nenhum pedido feito ainda!</p>';
      }
   ?>

   </div>

</section>

<?php include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>
