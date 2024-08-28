<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
    exit();
}

if (isset($_POST['add_to_cart'])) {

    $pid = filter_var($_POST['pid'], FILTER_SANITIZE_STRING);
    $p_name = filter_var($_POST['p_name'], FILTER_SANITIZE_STRING);
    $p_price = filter_var($_POST['p_price'], FILTER_SANITIZE_STRING);
    $p_image = filter_var($_POST['p_image'], FILTER_SANITIZE_STRING);
    $p_qty = filter_var($_POST['p_qty'], FILTER_SANITIZE_STRING);

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
}

if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $delete_wishlist_item = $conn->prepare("DELETE FROM lista_desejos WHERE id = ?");
    $delete_wishlist_item->execute([$delete_id]);
    header('location:wishlist.php');
    exit();
}

if (isset($_GET['delete_all'])) {
    $delete_wishlist_item = $conn->prepare("DELETE FROM lista_desejos WHERE usuario_id = ?");
    $delete_wishlist_item->execute([$user_id]);
    header('location:wishlist.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de desejos</title>

    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   
    <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'header.php'; ?>

<section class="wishlist">

    <h1 class="title">Produtos adicionados</h1>

    <div class="box-container">

    <?php
    $grand_total = 0;
    $select_wishlist = $conn->prepare("SELECT * FROM lista_desejos WHERE usuario_id = ?");
    $select_wishlist->execute([$user_id]);
    if ($select_wishlist->rowCount() > 0) {
        while ($fetch_wishlist = $select_wishlist->fetch(PDO::FETCH_ASSOC)) {
    ?>
    <form action="" method="POST" class="box">
        <a href="wishlist.php?delete=<?= $fetch_wishlist['id']; ?>" class="fas fa-times" onclick="return confirm('Excluir isso da lista de desejos?');"></a>
        <a href="view_page.php?pid=<?= $fetch_wishlist['produto_id']; ?>" class="fas fa-eye"></a>
        <img src="uploaded_img/<?= htmlspecialchars($fetch_wishlist['imagem']); ?>" alt="">
        <div class="name"><?= htmlspecialchars($fetch_wishlist['nome']); ?></div>
        <div class="price">R$<?= htmlspecialchars($fetch_wishlist['preco']); ?>/-</div>
        <input type="number" min="1" value="1" class="qty" name="p_qty">
        <input type="hidden" name="pid" value="<?= htmlspecialchars($fetch_wishlist['produto_id']); ?>">
        <input type="hidden" name="p_name" value="<?= htmlspecialchars($fetch_wishlist['nome']); ?>">
        <input type="hidden" name="p_price" value="<?= htmlspecialchars($fetch_wishlist['preco']); ?>">
        <input type="hidden" name="p_image" value="<?= htmlspecialchars($fetch_wishlist['imagem']); ?>">
        <input type="submit" value="Adicionar ao carrinho." name="add_to_cart" class="btn">
    </form>
    <?php
        $grand_total += $fetch_wishlist['preco'];
        }
    } else {
        echo '<p class="empty">Sua lista de desejos está vazia</p>';
    }
    ?>
    </div>

    <div class="wishlist-total">
        <p>Total geral : <span>R$<?= $grand_total; ?>/-</span></p>
        <a href="shop.php" class="option-btn">Continuar comprando</a>
        <a href="wishlist.php?delete_all" class="delete-btn <?= ($grand_total > 0) ? '' : 'disabled'; ?>">Apagar tudo</a>
    </div>

</section>

<?php include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>
