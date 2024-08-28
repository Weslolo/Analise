<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
};

if(isset($_POST['add_to_wishlist'])){

   $pid = $_POST['pid'];
   $pid = filter_var($pid, FILTER_SANITIZE_STRING);
   $p_name = $_POST['p_name'];
   $p_name = filter_var($p_name, FILTER_SANITIZE_STRING);
   $p_price = $_POST['p_price'];
   $p_price = filter_var($p_price, FILTER_SANITIZE_STRING);
   $p_image = $_POST['p_image'];
   $p_image = filter_var($p_image, FILTER_SANITIZE_STRING);

   $verificar_lista_desejos = $conn->prepare("SELECT * FROM lista_desejos WHERE nome = ? AND usuario_id = ?");
   $verificar_lista_desejos->execute([$p_name, $user_id]);

   $verificar_carrinho = $conn->prepare("SELECT * FROM carrinho WHERE nome = ? AND usuario_id = ?");
   $verificar_carrinho->execute([$p_name, $user_id]);

   if($verificar_lista_desejos->rowCount() > 0){
      $mensagem[] = 'Já adicionado à lista de desejos.';
   }elseif($verificar_carrinho->rowCount() > 0){
      $mensagem[] = 'Já adicionado ao carrinho.';
   }else{
      $adicionar_lista_desejos = $conn->prepare("INSERT INTO lista_desejos(usuario_id, pid, nome, preco, imagem) VALUES(?,?,?,?,?)");
      $adicionar_lista_desejos->execute([$user_id, $pid, $p_name, $p_price, $p_image]);
      $mensagem[] = 'Adicionado à lista de desejos.';
   }

}

if(isset($_POST['add_to_cart'])){

   $pid = $_POST['pid'];
   $pid = filter_var($pid, FILTER_SANITIZE_STRING);
   $p_name = $_POST['p_name'];
   $p_name = filter_var($p_name, FILTER_SANITIZE_STRING);
   $p_price = $_POST['p_price'];
   $p_price = filter_var($p_price, FILTER_SANITIZE_STRING);
   $p_image = $_POST['p_image'];
   $p_image = filter_var($p_image, FILTER_SANITIZE_STRING);
   $p_qty = $_POST['p_qty'];
   $p_qty = filter_var($p_qty, FILTER_SANITIZE_STRING);

   $verificar_carrinho = $conn->prepare("SELECT * FROM carrinho WHERE nome = ? AND usuario_id = ?");
   $verificar_carrinho->execute([$p_name, $user_id]);

   if($verificar_carrinho->rowCount() > 0){
      $mensagem[] = 'Já adicionado ao carrinho.';
   }else{

      $verificar_lista_desejos = $conn->prepare("SELECT * FROM lista_desejos WHERE nome = ? AND usuario_id = ?");
      $verificar_lista_desejos->execute([$p_name, $user_id]);

      if($verificar_lista_desejos->rowCount() > 0){
         $remover_lista_desejos = $conn->prepare("DELETE FROM lista_desejos WHERE nome = ? AND usuario_id = ?");
         $remover_lista_desejos->execute([$p_name, $user_id]);
      }

      $adicionar_carrinho = $conn->prepare("INSERT INTO carrinho(usuario_id, pid, nome, preco, quantidade, imagem) VALUES(?,?,?,?,?,?)");
      $adicionar_carrinho->execute([$user_id, $pid, $p_name, $p_price, $p_qty, $p_image]);
      $mensagem[] = 'Adicionado ao carrinho.';
   }

}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Visão rápida</title>

   
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'header.php'; ?>

<section class="quick-view">

   <h1 class="title">Visão rápida</h1>

   <?php
      $pid = $_GET['pid'];
      $selecionar_produtos = $conn->prepare("SELECT * FROM produtos WHERE id = ?");
      $selecionar_produtos->execute([$pid]);
      if($selecionar_produtos->rowCount() > 0){
         while($buscar_produtos = $selecionar_produtos->fetch(PDO::FETCH_ASSOC)){ 
   ?>
   <form action="" class="box" method="POST">
      <div class="price">R$<span><?= $buscar_produtos['preco']; ?></span>/-</div>
      <img src="uploaded_img/<?= $buscar_produtos['imagem']; ?>" alt="">
      <div class="name"><?= $buscar_produtos['nome']; ?></div>
      <div class="details"><?= $buscar_produtos['detalhes']; ?></div>
      <input type="hidden" name="pid" value="<?= $buscar_produtos['id']; ?>">
      <input type="hidden" name="p_name" value="<?= $buscar_produtos['nome']; ?>">
      <input type="hidden" name="p_price" value="<?= $buscar_produtos['preco']; ?>">
      <input type="hidden" name="p_image" value="<?= $buscar_produtos['imagem']; ?>">
      <input type="number" min="1" value="1" name="p_qty" class="qty">
      <input type="submit" value="Adicionar à lista de desejos." class="option-btn" name="add_to_wishlist">
      <input type="submit" value="Adicionar ao carrinho." class="btn" name="add_to_cart">
   </form>
   <?php
         }
      }else{
         echo '<p class="empty">Nenhum produto adicionado ainda.</p>';
      }
   ?>

</section>

<?php include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>
