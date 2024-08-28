<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    header('location:login.php');
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sobre</title>

    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    
    <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'header.php'; ?>

<section class="about">

    <div class="row">
        <div class="box">
            <img src="images/about-img-1.png" alt="Sobre nós">
            <h3>Por que nos escolher?</h3>
            <p>Na La-resistência, garantimos produtos frescos e de alta qualidade. Cada item é selecionado com o maior cuidado para que você e sua família tenham uma alimentação saudável e saborosa. Além disso, priorizamos parcerias com produtores locais, apoiando a economia da nossa comunidade.</p>
            <a href="contact.php" class="btn">Contate-nos</a>
        </div>

        <div class="box">
            <img src="images/about-img-2.png" alt="O que oferecemos">
            <h3>O que oferecemos?</h3>
            <p>Oferecemos uma ampla variedade de produtos, desde frutas e vegetais frescos até carnes e peixes de procedência confiável. Nosso compromisso é trazer o melhor da natureza para a sua mesa, com conveniência e preços justos.</p>
            <a href="shop.php" class="btn">Explore Nossa Loja</a>
        </div>
    </div>

</section>

<section class="reviews">

   <h1 class="title">Avaliações de clientes</h1>

   <div class="box-container">

      <div class="box">
         <img src="images/pic-1.png" alt="">
         <p>Ótimo serviço! A equipe foi muito atenciosa e o produto chegou em perfeito estado. Super recomendo!</p>
         <div class="stars">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star-half-alt"></i>
         </div>
         <h3>João Silva</h3>
      </div>

      <div class="box">
         <img src="images/pic-2.png" alt="">
         <p>Excelente experiência de compra. O atendimento foi rápido e o produto correspondeu às minhas expectativas.</p>
         <div class="stars">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
         </div>
         <h3>Maria Oliveira</h3>
      </div>

      <div class="box">
         <img src="images/pic-3.png" alt="">
         <p>Adorei a compra! A entrega foi pontual e o produto é de ótima qualidade. Certamente farei novas compras.</p>
         <div class="stars">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star-half-alt"></i>
         </div>
         <h3>Pedro Santos</h3>
      </div>

      <div class="box">
         <img src="images/pic-4.png" alt="">
         <p>Bom atendimento, mas o produto demorou um pouco para chegar. No entanto, valeu a pena pela qualidade.</p>
         <div class="stars">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
         </div>
         <h3>Ana Costa</h3>
      </div>

      <div class="box">
         <img src="images/pic-5.png" alt="">
         <p>Atendimento ótimo, mas o produto não era exatamente o que eu esperava. A devolução foi fácil e o atendimento foi muito bom.</p>
         <div class="stars">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star-half-alt"></i>
         </div>
         <h3>Lucas Almeida</h3>
      </div>

      <div class="box">
         <img src="images/pic-6.png" alt="">
         <p>Compra realizada com sucesso. O produto chegou em bom estado e o atendimento foi excelente. Recomendado!</p>
         <div class="stars">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star-half-alt"></i>
         </div>
         <h3>Fernanda Lima</h3>
      </div>

   </div>

</section>

<?php include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>
