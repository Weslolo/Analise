<?php

include 'config.php';

if(isset($_POST['submit'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $pass = md5($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);
   $cpass = md5($_POST['cpass']);
   $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = 'uploaded_img/'.$image;

   // Atualizando a tabela para `usuarios` e a coluna da senha para `senha`
   $select = $conn->prepare("SELECT * FROM usuarios WHERE email = ?");
   $select->execute([$email]);

   if($select->rowCount() > 0){
      $message[] = 'E-mail já cadastrado!';
   }else{
      if($pass != $cpass){
         $message[] = 'A confirmação da senha não confere!';
      }else{
         $insert = $conn->prepare("INSERT INTO usuarios(nome, email, senha, imagem) VALUES(?,?,?,?)");
         $insert->execute([$name, $email, $pass, $image]);

         if($insert){
            if($image_size > 2000000){
               $message[] = 'O tamanho da imagem é muito grande!';
            }else{
               move_uploaded_file($image_tmp_name, $image_folder);
               $message[] = 'Registrado com sucesso!';
               header('location:login.php');
            }
         }

      }
   }

}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Registro</title>

   
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

  
   <link rel="stylesheet" href="css/components.css">

</head>
<body>

<?php

if(isset($message)){
   foreach($message as $message){
      echo '
      <div class="message">
         <span>'.$message.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}

?>
   
<section class="form-container">

   <form action="" enctype="multipart/form-data" method="POST">
      <h3>Registre-se agora</h3>
      <input type="text" name="name" class="box" placeholder="Digite seu nome" required>
      <input type="email" name="email" class="box" placeholder="Digite seu e-mail" required>
      <input type="password" name="pass" class="box" placeholder="Digite sua senha" required>
      <input type="password" name="cpass" class="box" placeholder="Confirme sua senha" required>
      <input type="file" name="image" class="box" required accept="image/jpg, image/jpeg, image/png">
      <input type="submit" value="Registre-se agora" class="btn" name="submit">
      <p>Já tem uma conta? <a href="login.php">Faça login agora</a></p>
   </form>

</section>


</body>
</html>
