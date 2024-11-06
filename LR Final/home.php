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

   $check_wishlist_numbers = $conn->prepare("SELECT * FROM lista_desejos WHERE nome = ? AND usuario_id = ?");
   $check_wishlist_numbers->execute([$p_name, $user_id]);

   $check_cart_numbers = $conn->prepare("SELECT * FROM carrinho WHERE nome = ? AND usuario_id = ?");
   $check_cart_numbers->execute([$p_name, $user_id]);

   if($check_wishlist_numbers->rowCount() > 0){
      $message[] = 'Já adicionado à lista de desejos.';
   }elseif($check_cart_numbers->rowCount() > 0){
      $message[] = 'Já adicionado ao carrinho.';
   }else{
      $insert_wishlist = $conn->prepare("INSERT INTO lista_desejos(usuario_id, produto_id, nome, preco, imagem) VALUES(?,?,?,?,?)");
      $insert_wishlist->execute([$user_id, $pid, $p_name, $p_price, $p_image]);
      $message[] = 'Adicionado à lista de desejos.';
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

   $check_cart_numbers = $conn->prepare("SELECT * FROM carrinho WHERE nome = ? AND usuario_id = ?");
   $check_cart_numbers->execute([$p_name, $user_id]);

   if($check_cart_numbers->rowCount() > 0){
      $message[] = 'Já adicionado ao carrinho.';
   }else{

      $check_wishlist_numbers = $conn->prepare("SELECT * FROM lista_desejos WHERE nome = ? AND usuario_id = ?");
      $check_wishlist_numbers->execute([$p_name, $user_id]);

      if($check_wishlist_numbers->rowCount() > 0){
         $delete_wishlist = $conn->prepare("DELETE FROM lista_desejos WHERE nome = ? AND usuario_id = ?");
         $delete_wishlist->execute([$p_name, $user_id]);
      }

      $insert_cart = $conn->prepare("INSERT INTO carrinho(usuario_id, produto_id, nome, preco, quantidade, imagem) VALUES(?,?,?,?,?,?)");
      $insert_cart->execute([$user_id, $pid, $p_name, $p_price, $p_qty, $p_image]);
      $message[] = 'Adicionado ao carrinho.';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>home page</title>

   
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'header.php'; ?>

<div class="home-bg">

   <section class="home">

      <div class="content">
         <span>Escolha viver mais e melhor, escolha La-resistência</span>
         <h3>Nutrição de Verdade, para uma Vida Mais Saudável</h3>
         <p>Oferecemos uma seleção rigorosa de produtos frescos, do campo e do mar direto para sua mesa. Porque sua saúde é a nossa prioridade.</p>
         <a href="sobre.php" class="btn">Sobre nós</a>
      </div>

   </section>

</div>

<section class="home-category">

   <h1 class="title">Comprar por categoria</h1>

   <div class="box-container">

      <div class="box">
         <img src="images/cat-1.png" alt="">
         <h3>Frutas</h3>
         <p>Sabor e frescor em cada mordida. Nossas frutas são cuidadosamente selecionadas para garantir o melhor para você.</p>
         <a href="category.php?category=fruits" class="btn">Ver Frutas</a>
         
      </div>

      <div class="box">
         <img src="images/cat-2.png" alt="">
         <h3>Carne</h3>
         <p>Carnes de qualidade, direto dos melhores fornecedores. Perfeitas para uma refeição nutritiva e deliciosa.</p>
         <a href="category.php?category=meat" class="btn">Ver Carnes</a>
     
      </div>

      <div class="box">
         <img src="images/cat-3.png" alt="">
         <h3>Vegetais</h3>
         <p>Cultivados com cuidado, nossos vegetais trazem o melhor da natureza para a sua mesa.</p>
         <a href="category.php?category=vegitables" class="btn">Ver Vegetais</a>
      </div>

      <div class="box">
         <img src="images/cat-4.png" alt="">
         <h3>Peixe</h3>
         <p>Peixes frescos, capturados com sustentabilidade. Uma opção saudável e rica em nutrientes.</p>
         <a href="category.php?category=fish" class="btn">Ver Peixes</a>
      </div>

   </div>

</section>

<section class="products">

   <h1 class="title">Últimos produtos</h1>

   <div class="box-container">

   <?php
      $select_products = $conn->prepare("SELECT * FROM produtos LIMIT 6");
      $select_products->execute();
      if($select_products->rowCount() > 0){
         while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){ 
   ?>
   <form action="" class="box" method="POST">
      <div class="price">R$<span><?= $fetch_products['preco']; ?></span>/-</div>
      <a href="view_page.php?pid=<?= $fetch_products['id']; ?>" class="fas fa-eye"></a>
      <img src="uploaded_img/<?= $fetch_products['imagem']; ?>" alt="">
      <div class="name"><?= $fetch_products['nome']; ?></div>
      <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
      <input type="hidden" name="p_name" value="<?= $fetch_products['nome']; ?>">
      <input type="hidden" name="p_price" value="<?= $fetch_products['preco']; ?>">
      <input type="hidden" name="p_image" value="<?= $fetch_products['imagem']; ?>">
      <input type="number" min="1" value="1" name="p_qty" class="qty">
      <input type="submit" value="Adicionar à lista de desejos" class="option-btn" name="add_to_wishlist">
      <input type="submit" value="Adicionar ao carrinho" class="btn" name="add_to_cart">
   </form>
   <?php
      }
   }else{
      echo '<p class="empty">Nenhum produto adicionado ainda!</p>';
   }
   ?>

   </div>

</section>

<?php include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>
