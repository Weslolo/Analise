<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'] ?? null;

if (!isset($user_id)) {
    header('Location: login.php');
    exit();
}

if (isset($_POST['add_to_wishlist'])) {
    $pid = filter_var($_POST['pid'], FILTER_SANITIZE_STRING);
    $p_name = filter_var($_POST['p_name'], FILTER_SANITIZE_STRING);
    $p_price = filter_var($_POST['p_price'], FILTER_SANITIZE_STRING);
    $p_image = filter_var($_POST['p_image'], FILTER_SANITIZE_STRING);

    try {
        $check_wishlist_numbers = $conn->prepare("SELECT * FROM lista_desejos WHERE nome = ? AND usuario_id = ?");
        $check_wishlist_numbers->execute([$p_name, $user_id]);

        $check_cart_numbers = $conn->prepare("SELECT * FROM carrinho WHERE nome = ? AND usuario_id = ?");
        $check_cart_numbers->execute([$p_name, $user_id]);

        if ($check_wishlist_numbers->rowCount() > 0) {
            $message[] = 'Já adicionado à lista de desejos!';
        } elseif ($check_cart_numbers->rowCount() > 0) {
            $message[] = 'Já adicionado ao carrinho.';
        } else {
            $insert_wishlist = $conn->prepare("INSERT INTO lista_desejos(usuario_id, produto_id, nome, preco, imagem) VALUES(?,?,?,?,?)");
            $insert_wishlist->execute([$user_id, $pid, $p_name, $p_price, $p_image]);
            $message[] = 'Adicionado à lista de desejos.';
        }
    } catch (PDOException $e) {
        echo 'Erro: ' . $e->getMessage();
    }
}

if (isset($_POST['add_to_cart'])) {
    $pid = filter_var($_POST['pid'], FILTER_SANITIZE_STRING);
    $p_name = filter_var($_POST['p_name'], FILTER_SANITIZE_STRING);
    $p_price = filter_var($_POST['p_price'], FILTER_SANITIZE_STRING);
    $p_image = filter_var($_POST['p_image'], FILTER_SANITIZE_STRING);
    $p_qty = filter_var($_POST['p_qty'], FILTER_SANITIZE_NUMBER_INT);

    try {
        $check_cart_numbers = $conn->prepare("SELECT * FROM carrinho WHERE nome = ? AND usuario_id = ?");
        $check_cart_numbers->execute([$p_name, $user_id]);

        if ($check_cart_numbers->rowCount() > 0) {
            $message[] = 'Já adicionado ao carrinho.';
        } else {
            $check_wishlist_numbers = $conn->prepare("SELECT * FROM lista_desejos WHERE nome = ? AND usuario_id = ?");
            $check_wishlist_numbers->execute([$p_name, $user_id]);

            if ($check_wishlist_numbers->rowCount() > 0) {
                $delete_wishlist = $conn->prepare("DELETE FROM lista_desejos WHERE nome = ? AND usuario_id = ?");
                $delete_wishlist->execute([$p_name, $user_id]);
            }

            $insert_cart = $conn->prepare("INSERT INTO carrinho(usuario_id, produto_id, nome, preco, quantidade, imagem) VALUES(?,?,?,?,?,?)");
            $insert_cart->execute([$user_id, $pid, $p_name, $p_price, $p_qty, $p_image]);
            $message[] = 'Adicionado ao carrinho.';
        }
    } catch (PDOException $e) {
        echo 'Erro: ' . $e->getMessage();
    }
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprar</title>

   
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
   
<?php include 'header.php'; ?>

<section class="p-category">
    <a href="category.php?category=fruits">Frutas</a>
    <a href="category.php?category=vegetables">Vegetais</a>
    <a href="category.php?category=fish">Peixe</a>
    <a href="category.php?category=meat">Carne</a>
</section>

<section class="products">
    <h1 class="title">Últimos produtos</h1>

    <div class="box-container">
        <?php
        try {
            $select_products = $conn->prepare("SELECT * FROM produtos");
            $select_products->execute();

            if ($select_products->rowCount() > 0) {
                while ($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)) {
                    $price = isset($fetch_products['preco']) ? htmlspecialchars($fetch_products['preco']) : 'Indisponível';
                    $name = isset($fetch_products['nome']) ? htmlspecialchars($fetch_products['nome']) : 'Sem Nome';
                    $image = isset($fetch_products['imagem']) ? htmlspecialchars($fetch_products['imagem']) : 'default.jpg';
        ?>
        <form action="" class="box" method="POST">
    <div class="price">R$<span><?= $price; ?></span>/-</div>
    <a href="view_page.php?pid=<?= htmlspecialchars($fetch_products['id']); ?>" class="fas fa-eye"></a>
    <?php 
    $image_path = 'uploaded_img/' . htmlspecialchars($fetch_products['imagem']); 
    if (file_exists($image_path)) {
        echo '<img src="' . $image_path . '" alt="">';
    } else {
        echo '<img src="uploaded_img/default.jpg" alt="Imagem não disponível">';
    }
    ?>
    <div class="name"><?= $name; ?></div>
    <input type="hidden" name="pid" value="<?= htmlspecialchars($fetch_products['id']); ?>">
    <input type="hidden" name="p_name" value="<?= $name; ?>">
    <input type="hidden" name="p_price" value="<?= $price; ?>">
    <input type="hidden" name="p_image" value="<?= $image; ?>">
    <input type="number" min="1" value="1" name="p_qty" class="qty">
    <input type="submit" value="Adicionar à lista de desejos." class="option-btn" name="add_to_wishlist">
    <input type="submit" value="Adicionar ao carrinho." class="btn" name="add_to_cart">
</form>
        <?php
                }
            } else {
                echo '<p class="empty">Nenhum produto adicionado ainda.</p>';
            }
        } catch (PDOException $e) {
            echo 'Erro: ' . $e->getMessage();
        }
        ?>
    </div>
</section>

<?php include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>
