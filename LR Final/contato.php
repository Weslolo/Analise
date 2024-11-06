<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
};

if(isset($_POST['send'])){

   $nome = $_POST['name'];
   $nome = filter_var($nome, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $numero = $_POST['number'];
   $numero = filter_var($numero, FILTER_SANITIZE_STRING);
   $mensagem = $_POST['msg'];
   $mensagem = filter_var($mensagem, FILTER_SANITIZE_STRING);

   $select_message = $conn->prepare("SELECT * FROM mensagem WHERE nome = ? AND email = ? AND telefone = ? AND mensagem = ?");
   $select_message->execute([$nome, $email, $numero, $mensagem]);

   if($select_message->rowCount() > 0){
      $message[] = 'Mensagem já enviada!';
   }else{

      $insert_message = $conn->prepare("INSERT INTO mensagem(usuario_id, nome, email, telefone, mensagem) VALUES(?,?,?,?,?)");
      $insert_message->execute([$user_id, $nome, $email, $numero, $mensagem]);

      $message[] = 'Mensagem enviada com sucesso!';

   }

}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Contato</title>

   
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'header.php'; ?>

<section class="contact">

   <h1 class="title">Entre em Contato</h1>

   <form action="" method="POST">
      <input type="text" name="name" class="box" required placeholder="Digite seu nome">
      <input type="email" name="email" class="box" required placeholder="Digite seu e-mail">
      <input type="number" name="number" min="0" class="box" required placeholder="Digite seu número">
      <textarea name="msg" class="box" required placeholder="Digite sua mensagem" cols="30" rows="10"></textarea>
      <input type="submit" value="Enviar Mensagem" class="btn" name="send">
   </form>

</section>

<?php include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>
