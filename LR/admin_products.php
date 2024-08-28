<?php

@include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
};

if(isset($_POST['add_product'])){

   $nome = $_POST['name'];
   $nome = filter_var($nome, FILTER_SANITIZE_STRING);
   $preco = $_POST['price'];
   $preco = filter_var($preco, FILTER_SANITIZE_STRING);
   $categoria = $_POST['category'];
   $categoria = filter_var($categoria, FILTER_SANITIZE_STRING);
   $detalhes = $_POST['details'];
   $detalhes = filter_var($detalhes, FILTER_SANITIZE_STRING);

   $imagem = $_FILES['image']['name'];
   $imagem = filter_var($imagem, FILTER_SANITIZE_STRING);
   $imagem_size = $_FILES['image']['size'];
   $imagem_tmp_name = $_FILES['image']['tmp_name'];
   $imagem_folder = 'uploaded_img/'.$imagem;

   $select_products = $conn->prepare("SELECT * FROM produtos WHERE nome = ?");
   $select_products->execute([$nome]);

   if($select_products->rowCount() > 0){
      $message[] = 'Nome do produto já existe.';
   }else{

      $insert_products = $conn->prepare("INSERT INTO produtos(nome, categoria, detalhes, preco, imagem) VALUES(?,?,?,?,?)");
      $insert_products->execute([$nome, $categoria, $detalhes, $preco, $imagem]);

      if($insert_products){
         if($imagem_size > 2000000){
            $message[] = 'O tamanho da imagem é muito grande.';
         }else{
            move_uploaded_file($imagem_tmp_name, $imagem_folder);
            $message[] = 'Novo produto adicionado.';
         }

      }

   }

};

if(isset($_GET['delete'])){

   $delete_id = $_GET['delete'];
   $select_delete_image = $conn->prepare("SELECT imagem FROM produtos WHERE id = ?");
   $select_delete_image->execute([$delete_id]);
   $fetch_delete_image = $select_delete_image->fetch(PDO::FETCH_ASSOC);
   unlink('uploaded_img/'.$fetch_delete_image['imagem']);
   $delete_products = $conn->prepare("DELETE FROM produtos WHERE id = ?");
   $delete_products->execute([$delete_id]);
   $delete_wishlist = $conn->prepare("DELETE FROM lista_desejos WHERE produto_id = ?");
   $delete_wishlist->execute([$delete_id]);
   $delete_cart = $conn->prepare("DELETE FROM carrinho WHERE produto_id = ?");
   $delete_cart->execute([$delete_id]);
   header('location:admin_products.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Produtos</title>

   
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   
   <link rel="stylesheet" href="css/admin_style.css">

</head>
<body>
   
<?php include 'admin_header.php'; ?>

<section class="add-products">

   <h1 class="title">Adicionar novo produto</h1>

   <form action="" method="POST" enctype="multipart/form-data">
      <div class="flex">
         <div class="inputBox">
         <input type="text" name="name" class="box" required placeholder="Digite o nome do produto">
         <select name="category" class="box" required>
            <option value="" selected disabled>Selecione a categoria</option>
               <option value="vegitables">Vegetais</option>
               <option value="fruits">Frutas</option>
               <option value="meat">Carne</option>
               <option value="fish">Peixe</option>
         </select>
         </div>
         <div class="inputBox">
         <input type="number" min="0" name="price" class="box" required placeholder="Insira o preço do produto.">
         <input type="file" name="image" required class="box" accept="image/jpg, image/jpeg, image/png">
         </div>
      </div>
      <textarea name="details" class="box" required placeholder="Insira os detalhes do produto." cols="30" rows="10"></textarea>
      <input type="submit" class="btn" value="Adicionar produto" name="add_product">
   </form>

</section>

<section class="show-products">

   <h1 class="title">Produtos adicionados</h1>

   <div class="box-container">

   <?php
      $show_products = $conn->prepare("SELECT * FROM produtos");
      $show_products->execute();
      if($show_products->rowCount() > 0){
         while($fetch_products = $show_products->fetch(PDO::FETCH_ASSOC)){  
   ?>
   <div class="box">
      <div class="price">$<?= $fetch_products['preco']; ?>/-</div>
      <img src="uploaded_img/<?= $fetch_products['imagem']; ?>" alt="">
      <div class="name"><?= $fetch_products['nome']; ?></div>
      <div class="cat"><?= $fetch_products['categoria']; ?></div>
      <div class="details"><?= $fetch_products['detalhes']; ?></div>
      <div class="flex-btn">
         <a href="admin_update_product.php?update=<?= $fetch_products['id']; ?>" class="option-btn">Atualizar</a>
         <a href="admin_products.php?delete=<?= $fetch_products['id']; ?>" class="delete-btn" onclick="return confirm('delete this product?');">delete</a>
      </div>
   </div>
   <?php
      }
   }else{
      echo '<p class="empty">Nenhum produto adicionado ainda!</p>';
   }
   ?>

   </div>

</section>

<script src="js/script.js"></script>

</body>
</html>
