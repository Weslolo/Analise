<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
    exit();
}

if (isset($_POST['order'])) {
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $number = filter_var($_POST['number'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $method = filter_var($_POST['method'], FILTER_SANITIZE_STRING);
    $address = 'flat no. ' . filter_var($_POST['flat'], FILTER_SANITIZE_STRING) . ' ' .
                filter_var($_POST['street'], FILTER_SANITIZE_STRING) . ' ' .
                filter_var($_POST['city'], FILTER_SANITIZE_STRING) . ' ' .
                filter_var($_POST['state'], FILTER_SANITIZE_STRING) . ' ' .
                filter_var($_POST['country'], FILTER_SANITIZE_STRING) . ' - ' .
                filter_var($_POST['pin_code'], FILTER_SANITIZE_STRING);
    $placed_on = date('d-M-Y');

    $cart_total = 0;
    $cart_products = [];

    // Consultar o carrinho
    $cart_query = $conn->prepare("SELECT * FROM carrinho WHERE usuario_id = ?");
    $cart_query->execute([$user_id]);
    if ($cart_query->rowCount() > 0) {
        while ($cart_item = $cart_query->fetch(PDO::FETCH_ASSOC)) {
            $cart_products[] = $cart_item['nome'] . ' ( ' . $cart_item['quantidade'] . ' )';
            $sub_total = ($cart_item['preco'] * $cart_item['quantidade']);
            $cart_total += $sub_total;
        }
    }

    $total_products = implode(', ', $cart_products);

    // Verificar se o pedido já foi feito
    $order_query = $conn->prepare("SELECT * FROM pedidos WHERE nome = ? AND telefone = ? AND email = ? AND metodo = ? AND endereco = ? AND total_produtos = ? AND preco_total = ?");
    $order_query->execute([$name, $number, $email, $method, $address, $total_products, $cart_total]);

    if ($cart_total == 0) {
        $message[] = 'Seu carrinho está vazio';
    } elseif ($order_query->rowCount() > 0) {
        $message[] = 'Pedido já feito!';
    } else {
        // Inserir pedido
        $insert_order = $conn->prepare("INSERT INTO pedidos(usuario_id, nome, telefone, email, metodo, endereco, total_produtos, preco_total, data_pedido) VALUES(?,?,?,?,?,?,?,?,?)");
        $insert_order->execute([$user_id, $name, $number, $email, $method, $address, $total_products, $cart_total, $placed_on]);
        // Deletar itens do carrinho
        $delete_cart = $conn->prepare("DELETE FROM carrinho WHERE usuario_id = ?");
        $delete_cart->execute([$user_id]);
        $message[] = 'Pedido feito com sucesso!';
    }
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Confira</title>

   
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   
   <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'header.php'; ?>

<section class="display-orders">
   <?php
      $cart_grand_total = 0;
      $select_cart_items = $conn->prepare("SELECT * FROM carrinho WHERE usuario_id = ?");
      $select_cart_items->execute([$user_id]);
      if ($select_cart_items->rowCount() > 0) {
         while ($fetch_cart_items = $select_cart_items->fetch(PDO::FETCH_ASSOC)) {
            $cart_total_price = ($fetch_cart_items['preco'] * $fetch_cart_items['quantidade']);
            $cart_grand_total += $cart_total_price;
   ?>
   <p> <?= htmlspecialchars($fetch_cart_items['nome']); ?> <span>(<?= 'R$'.$fetch_cart_items['preco'].' x '. $fetch_cart_items['quantidade']; ?>)</span> </p>
   <?php
    }
   } else {
      echo '<p class="empty">Seu carrinho está vazio!</p>';
   }
   ?>
   <div class="grand-total">Total geral : <span>R$<?= number_format($cart_grand_total, 2, ',', '.'); ?>/-</span></div>
</section>

<section class="checkout-orders">

   <form action="" method="POST">

      <h3>Faça seu pedido</h3>

      <div class="flex">
         <div class="inputBox">
            <span>Seu nome :</span>
            <input type="text" name="name" placeholder="Digite seu nome" class="box" required>
         </div>
         <div class="inputBox">
            <span>Seu número :</span>
            <input type="number" name="number" placeholder="Digite seu número" class="box" required>
         </div>
         <div class="inputBox">
            <span>Seu e-mail :</span>
            <input type="email" name="email" placeholder="Digite seu e-mail" class="box" required>
         </div>
         <div class="inputBox">
            <span>Método de pagamento :</span>
            <select name="method" class="box" required>
               <option value="pagamento na entrega">Pagamento na entrega</option>
               <option value="cartão de crédito">Cartão de crédito</option>
               <option value="paytm">Paytm</option>
               <option value="paypal">Paypal</option>
            </select>
         </div>
         <div class="inputBox">
            <span>Linha de endereço 01 :</span>
            <input type="text" name="flat" placeholder="Por exemplo número do apartamento" class="box" required>
         </div>
         <div class="inputBox">
            <span>Linha de endereço 02 :</span>
            <input type="text" name="street" placeholder="Por exemplo nome da rua" class="box" required>
         </div>
         <div class="inputBox">
            <span>Cidade :</span>
            <input type="text" name="city" placeholder="Por exemplo, Cascavel" class="box" required>
         </div>
         <div class="inputBox">
            <span>Estado :</span>
            <input type="text" name="state" placeholder="Por exemplo Paraná" class="box" required>
         </div>
         <div class="inputBox">
            <span>País :</span>
            <input type="text" name="country" placeholder="Por exemplo Brasil" class="box" required>
         </div>
         <div class="inputBox">
            <span>Código PIN :</span>
            <input type="number" min="0" name="pin_code" placeholder="Por exemplo 123456" class="box" required>
         </div>
      </div>

      <input type="submit" name="order" class="btn <?= ($cart_grand_total > 1) ? '' : 'disabled'; ?>" value="Fazer pedido">

   </form>

</section>

<?php include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>
