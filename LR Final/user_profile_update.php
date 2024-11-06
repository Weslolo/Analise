<?php

@include 'config.php';

session_start();

$usuario_id = $_SESSION['user_id'];

if(!isset($usuario_id)){
   header('location:login.php');
   exit();
}

// Recuperar informações do perfil do usuário
$fetch_perfil = $conn->prepare("SELECT * FROM usuarios WHERE id = ?");
$fetch_perfil->execute([$usuario_id]);
$fetch_perfil = $fetch_perfil->fetch(PDO::FETCH_ASSOC);

if(isset($_POST['atualizar_perfil'])){

   $nome = $_POST['nome'];
   $nome = filter_var($nome, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);

   // Atualizar dados do perfil
   $atualizar_perfil = $conn->prepare("UPDATE usuarios SET nome = ?, email = ? WHERE id = ?");
   $atualizar_perfil->execute([$nome, $email, $usuario_id]);

   $imagem = $_FILES['imagem']['name'];
   $imagem = filter_var($imagem, FILTER_SANITIZE_STRING);
   $imagem_tamanho = $_FILES['imagem']['size'];
   $imagem_tmp_nome = $_FILES['imagem']['tmp_name'];
   $imagem_pasta = 'uploaded_img/' . $imagem;
   $imagem_antiga = $_POST['imagem_antiga'];

   if(!empty($imagem)){
      if($imagem_tamanho > 2000000){
         $mensagem[] = 'O tamanho da imagem é muito grande.';
      }else{
         // Atualizar imagem do perfil
         $atualizar_imagem = $conn->prepare("UPDATE usuarios SET imagem = ? WHERE id = ?");
         $atualizar_imagem->execute([$imagem, $usuario_id]);
         if($atualizar_imagem){
            move_uploaded_file($imagem_tmp_nome, $imagem_pasta);
            if ($imagem_antiga !== 'default.png') {
               unlink('uploaded_img/' . $imagem_antiga);
            }
            $mensagem[] = 'Imagem atualizada com sucesso.';
         }
      }
   }

   $senha_antiga = $_POST['senha_antiga'];
   $senha_antiga = md5($senha_antiga);
   $nova_senha = $_POST['nova_senha'];
   $nova_senha = md5($nova_senha);
   $confirmar_senha = $_POST['confirmar_senha'];
   $confirmar_senha = md5($confirmar_senha);

   if(!empty($senha_antiga) && !empty($nova_senha) && !empty($confirmar_senha)){
      $senha_antiga_bd = $_POST['senha_antiga_bd'];
      if($senha_antiga != $senha_antiga_bd){
         $mensagem[] = 'Senha antiga não corresponde.';
      }elseif($nova_senha != $confirmar_senha){
         $mensagem[] = 'Confirmação de senha não corresponde.';
      }else{
         // Atualizar senha do perfil
         $atualizar_senha = $conn->prepare("UPDATE usuarios SET senha = ? WHERE id = ?");
         $atualizar_senha->execute([$confirmar_senha, $usuario_id]);
         $mensagem[] = 'Senha atualizada com sucesso.';
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
   <title>Atualizar perfil do usuário</title>

  
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   
   <link rel="stylesheet" href="css/components.css">
</head>
<body>
   
<?php include 'header.php'; ?>

<section class="update-profile">
   <h1 class="title">Atualizar perfil</h1>

   <form action="" method="POST" enctype="multipart/form-data">
      <?php
      // Verificar se $fetch_perfil não está vazio
      if ($fetch_perfil && !empty($fetch_perfil['imagem'])) {
          $imagem_caminho = 'uploaded_img/' . htmlspecialchars($fetch_perfil['imagem']);
          // Verificar se a imagem existe
          if (file_exists($imagem_caminho)) {
              echo '<img src="' . $imagem_caminho . '" alt="">';
          } else {
              echo '<img src="uploaded_img/default.png" alt="Imagem padrão">';
          }
      } else {
          echo '<img src="uploaded_img/default.png" alt="Imagem padrão">';
      }
      ?>
      <div class="flex">
         <div class="inputBox">
            <span>Nome de usuário :</span>
            <input type="text" name="nome" value="<?= htmlspecialchars($fetch_perfil['nome']); ?>" placeholder="Atualizar nome de usuário" required class="box">
            <span>Email :</span>
            <input type="email" name="email" value="<?= htmlspecialchars($fetch_perfil['email']); ?>" placeholder="Atualizar e-mail" required class="box">
            <span>Atualizar foto :</span>
            <input type="file" name="imagem" accept="image/jpg, image/jpeg, image/png" class="box">
            <input type="hidden" name="imagem_antiga" value="<?= htmlspecialchars($fetch_perfil['imagem']); ?>">
         </div>
         <div class="inputBox">
            <input type="hidden" name="senha_antiga_bd" value="<?= htmlspecialchars($fetch_perfil['senha']); ?>">
            <span>Senha Antiga :</span>
            <input type="password" name="senha_antiga" placeholder="Digite a senha anterior" class="box">
            <span>Nova Senha :</span>
            <input type="password" name="nova_senha" placeholder="Digite nova senha" class="box">
            <span>Confirme sua senha :</span>
            <input type="password" name="confirmar_senha" placeholder="Confirmar nova senha" class="box">
         </div>
      </div>
      <div class="flex-btn">
         <input type="submit" class="btn" value="Atualizar perfil" name="atualizar_perfil">
         <a href="home.php" class="option-btn">Voltar</a>
      </div>
   </form>
</section>

<?php include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>
