<?php

@include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>página de administração</title>


   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   
   <link rel="stylesheet" href="css/admin_style.css">

</head>
<body>
   
<?php include 'admin_header.php'; ?>

<section class="dashboard">

   <h1 class="title">dashboard</h1>

   <div class="box-container">

      <div class="box">
      <?php
         $total_pendings = 0;
         $select_pendings = $conn->prepare("SELECT * FROM pedidos WHERE status_pagamento = ?");
         $select_pendings->execute(['pendente']);
         while($fetch_pendings = $select_pendings->fetch(PDO::FETCH_ASSOC)){
            $total_pendings += $fetch_pendings['preco_total'];
         };
      ?>
      <h3>R$<?= $total_pendings; ?>/-</h3>
      <p>Total de pendências</p>
      <a href="admin_orders.php" class="btn">ver pedidos</a>
      </div>

      <div class="box">
      <?php
         $total_completed = 0;
         $select_completed = $conn->prepare("SELECT * FROM pedidos WHERE status_pagamento = ?");
         $select_completed->execute(['concluído']);
         while($fetch_completed = $select_completed->fetch(PDO::FETCH_ASSOC)){
            $total_completed += $fetch_completed['preco_total'];
         };
      ?>
      <h3>R$<?= $total_completed; ?>/-</h3>
      <p>Pedidos concluídos</p>
      <a href="admin_orders.php" class="btn">Ver concluídos</a>
      </div>

      <div class="box">
      <?php
         $select_orders = $conn->prepare("SELECT * FROM pedidos");
         $select_orders->execute();
         $number_of_orders = $select_orders->rowCount();
      ?>
      <h3><?= $number_of_orders; ?></h3>
      <p>Pedidos feitos</p>
      <a href="admin_orders.php" class="btn">Ver pedidos feitos</a>
      </div>

      <div class="box">
      <?php
         $select_products = $conn->prepare("SELECT * FROM produtos");
         $select_products->execute();
         $number_of_products = $select_products->rowCount();
      ?>
      <h3><?= $number_of_products; ?></h3>
      <p>Produtos adicionados</p>
      <a href="admin_products.php" class="btn">Ver produtos</a>
      </div>

      <div class="box">
      <?php
         $select_users = $conn->prepare("SELECT * FROM usuarios WHERE tipo_usuario = ?");
         $select_users->execute(['usuario']);
         $number_of_users = $select_users->rowCount();
      ?>
      <h3><?= $number_of_users; ?></h3>
      <p>Total de usuários</p>
      <a href="admin_users.php" class="btn">Ver usuários</a>
      </div>

      <div class="box">
      <?php
         $select_admins = $conn->prepare("SELECT * FROM usuarios WHERE tipo_usuario = ?");
         $select_admins->execute(['admin']);
         $number_of_admins = $select_admins->rowCount();
      ?>
      <h3><?= $number_of_admins; ?></h3>
      <p>Total admins</p>
      <a href="admin_users.php" class="btn">Ver admins</a>
      </div>

      <div class="box">
      <?php
         $select_accounts = $conn->prepare("SELECT * FROM usuarios");
         $select_accounts->execute();
         $number_of_accounts = $select_accounts->rowCount();
      ?>
      <h3><?= $number_of_accounts; ?></h3>
      <p>Todas as contas</p>
      <a href="admin_users.php" class="btn">Ver contas</a>
      </div>

      <div class="box">
      <?php
         $select_messages = $conn->prepare("SELECT * FROM mensagem");
         $select_messages->execute();
         $number_of_messages = $select_messages->rowCount();
      ?>
      <h3><?= $number_of_messages; ?></h3>
      <p>Todas mensagens</p>
      <a href="admin_contato.php" class="btn">Ver mensagens</a>
      </div>

   </div>

</section>

<script src="js/script.js"></script>

</body>
</html>
