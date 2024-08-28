<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'] ?? null; // Usa null como padrão se a variável não estiver definida

if (!isset($user_id)) {
    header('Location: login.php');
    exit(); // Adiciona exit para garantir que o script pare após o redirecionamento
}

?>

<!DOCTYPE html>
<html lang="pt-BR"> <!-- Altere o idioma para português do Brasil -->
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar Produtos</title>

    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   
    <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'header.php'; ?>

<section class="search-results">

    <h1 class="title">Resultados da Busca</h1>

    <div class="box-container">

    <?php
        // Verifica se o campo 'search' está definido e não está vazio
        if (isset($_POST['search']) && !empty($_POST['search'])) {
            $search_query = '%' . $_POST['search'] . '%';

            // Ajuste o nome da tabela e as colunas para português
            $busca_produtos = $conn->prepare("SELECT * FROM produtos WHERE nome LIKE ?");
            $busca_produtos->execute([$search_query]);
            if ($busca_produtos->rowCount() > 0) {
                while ($produto = $busca_produtos->fetch(PDO::FETCH_ASSOC)) { 
    ?>
    <div class="box">
        <img src="uploaded_img/<?= htmlspecialchars($produto['imagem']); ?>" alt="">
        <h3><?= htmlspecialchars($produto['nome']); ?></h3>
        <p><?= htmlspecialchars($produto['descricao']); ?></p>
        <p>Preço: R$<?= number_format($produto['preco'], 2, ',', '.'); ?>/-</p>
    </div>
    <?php
                }
            } else {
                echo '<p class="empty">Nenhum produto encontrado!</p>';
            }
        } else {
            echo '<p class="empty">Digite um termo de busca para encontrar produtos.</p>';
        }
    ?>

    </div>

</section>

<?php include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>
