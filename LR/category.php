<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
   header('location:login.php');
   exit();
}

if (isset($_POST['add_to_wishlist'])) {
   $pid = filter_var($_POST['pid'], FILTER_SANITIZE_STRING);
   $p_name = filter_var($_POST['p_name'], FILTER_SANITIZE_STRING);
   $p_preco = filter_var($_POST['p_preco'], FILTER_SANITIZE_STRING); // Alterado para p_preco
   $p_image = filter_var($_POST['p_image'], FILTER_SANITIZE_STRING);

   try {
       $check_wishlist = $conn->prepare("SELECT * FROM lista_desejos WHERE produto_id = ? AND usuario_id = ?");
       $check_wishlist->execute([$pid, $user_id]);

       $check_cart = $conn->prepare("SELECT * FROM carrinho WHERE produto_id = ? AND usuario_id = ?");
       $check_cart->execute([$pid, $user_id]);

       if ($check_wishlist->rowCount() > 0) {
          $message[] = 'Já adicionado à lista de desejos.';
       } elseif ($check_cart->rowCount() > 0) {
          $message[] = 'Já adicionado ao carrinho.';
       } else {
          $insert_wishlist = $conn->prepare("INSERT INTO lista_desejos(usuario_id, produto_id, nome, preco, imagem) VALUES(?,?,?,?,?)");
          $insert_wishlist->execute([$user_id, $pid, $p_name, $p_preco, $p_image]); // Alterado para p_preco
          $message[] = 'Adicionado à lista de desejos.';
       }
   } catch (PDOException $e) {
       echo 'Erro: ' . htmlspecialchars($e->getMessage());
   }
}

if (isset($_POST['add_to_cart'])) {
   $pid = filter_var($_POST['pid'], FILTER_SANITIZE_STRING);
   $p_name = filter_var($_POST['p_name'], FILTER_SANITIZE_STRING);
   $p_preco = filter_var($_POST['p_preco'], FILTER_SANITIZE_STRING); // Alterado para p_preco
   $p_image = filter_var($_POST['p_image'], FILTER_SANITIZE_STRING);
   $p_qty = filter_var($_POST['p_qty'], FILTER_SANITIZE_STRING);

   try {
       $check_cart = $conn->prepare("SELECT * FROM carrinho WHERE produto_id = ? AND usuario_id = ?");
       $check_cart->execute([$pid, $user_id]);

       if ($check_cart->rowCount() > 0) {
          $message[] = 'Já adicionado ao carrinho.';
       } else {
          $check_wishlist = $conn->prepare("SELECT * FROM lista_desejos WHERE produto_id = ? AND usuario_id = ?");
          $check_wishlist->execute([$pid, $user_id]);

          if ($check_wishlist->rowCount() > 0) {
             $delete_wishlist = $conn->prepare("DELETE FROM lista_desejos WHERE produto_id = ? AND usuario_id = ?");
             $delete_wishlist->execute([$pid, $user_id]);
          }

          $insert_cart = $conn->prepare("INSERT INTO carrinho(usuario_id, produto_id, nome, preco, quantidade, imagem) VALUES(?,?,?,?,?,?)");
          $insert_cart->execute([$user_id, $pid, $p_name, $p_preco, $p_qty, $p_image]); // Alterado para p_preco
          $message[] = 'Adicionado ao carrinho.';
       }
   } catch (PDOException $e) {
       echo 'Erro: ' . htmlspecialchars($e->getMessage());
   }
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Categoria</title>

   
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'header.php'; ?>

<section class="products">
   <h1 class="title">Categorias de produtos</h1>

   <div class="box-container">

   <?php
      $category_name = filter_var($_GET['category'], FILTER_SANITIZE_STRING);
      try {
          $select_products = $conn->prepare("SELECT * FROM produtos WHERE categoria = ?");
          $select_products->execute([$category_name]);

          if ($select_products->rowCount() > 0) {
             while ($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)) {
                $preco = isset($fetch_products['preco']) ? htmlspecialchars($fetch_products['preco']) : 'Não disponível'; // Alterado para preco
   ?>
   <form action="" method="POST" class="box">
      <div class="price">R$<span><?= $preco; ?></span>/-</div>
      <a href="view_page.php?pid=<?= htmlspecialchars($fetch_products['id']); ?>" class="fas fa-eye"></a>
      <img src="uploaded_img/<?= htmlspecialchars($fetch_products['imagem']); ?>" alt="">
      <div class="name"><?= htmlspecialchars($fetch_products['nome']); ?></div>
      <input type="hidden" name="pid" value="<?= htmlspecialchars($fetch_products['id']); ?>">
      <input type="hidden" name="p_name" value="<?= htmlspecialchars($fetch_products['nome']); ?>">
      <input type="hidden" name="p_preco" value="<?= htmlspecialchars($fetch_products['preco']); ?>"> <!-- Alterado para p_preco -->
      <input type="hidden" name="p_image" value="<?= htmlspecialchars($fetch_products['imagem']); ?>">
      <input type="number" min="1" value="1" name="p_qty" class="qty">
      <input type="submit" value="Adicionar à lista de desejos" class="option-btn" name="add_to_wishlist">
      <input type="submit" value="Adicionar ao carrinho" class="btn" name="add_to_cart">
   </form>
   <?php
             }
          } else {
             echo '<p class="empty">Nenhum produto disponível.</p>';
          }
      } catch (PDOException $e) {
          echo '<p class="error">Erro: ' . htmlspecialchars($e->getMessage()) . '</p>';
      }
   ?>

   </div>

</section>

<?php include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>
