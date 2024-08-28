<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
    exit();
}

if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $delete_cart_item = $conn->prepare("DELETE FROM carrinho WHERE id = ?");
    $delete_cart_item->execute([$delete_id]);
    header('location:cart.php');
    exit();
}

if (isset($_GET['delete_all'])) {
    $delete_cart_item = $conn->prepare("DELETE FROM carrinho WHERE usuario_id = ?");
    $delete_cart_item->execute([$user_id]);
    header('location:cart.php');
    exit();
}

if (isset($_POST['update_qty'])) {
    $cart_id = $_POST['cart_id'];
    $p_qty = filter_var($_POST['p_qty'], FILTER_SANITIZE_STRING);
    $update_qty = $conn->prepare("UPDATE carrinho SET quantidade = ? WHERE id = ?");
    $update_qty->execute([$p_qty, $cart_id]);
    $message[] = 'Quantidade do carrinho atualizada';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrinho de Compras</title>

    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    
    <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'header.php'; ?>

<section class="shopping-cart">

    <h1 class="title">Produtos Adicionados</h1>

    <div class="box-container">

    <?php
    $grand_total = 0;
    $select_cart = $conn->prepare("SELECT * FROM carrinho WHERE usuario_id = ?");
    $select_cart->execute([$user_id]);
    if ($select_cart->rowCount() > 0) {
        while ($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)) {
    ?>
    <form action="" method="POST" class="box">
        <a href="cart.php?delete=<?= htmlspecialchars($fetch_cart['id']); ?>" class="fas fa-times" onclick="return confirm('Excluir isso do carrinho?');"></a>
        <a href="view_page.php?pid=<?= htmlspecialchars($fetch_cart['produto_id']); ?>" class="fas fa-eye"></a>
        <img src="uploaded_img/<?= htmlspecialchars($fetch_cart['imagem']); ?>" alt="">
        <div class="name"><?= htmlspecialchars($fetch_cart['nome']); ?></div>
        <div class="price">R$<?= htmlspecialchars($fetch_cart['preco']); ?>/-</div>
        <input type="hidden" name="cart_id" value="<?= htmlspecialchars($fetch_cart['id']); ?>">
        <div class="flex-btn">
            <input type="number" min="1" value="<?= htmlspecialchars($fetch_cart['quantidade']); ?>" class="qty" name="p_qty">
            <input type="submit" value="Atualizar" name="update_qty" class="option-btn">
        </div>
        <div class="sub-total"> Subtotal: <span>R$<?= $sub_total = ($fetch_cart['preco'] * $fetch_cart['quantidade']); ?>/-</span> </div>
    </form>
    <?php
        $grand_total += $sub_total;
        }
    } else {
        echo '<p class="empty">Seu carrinho está vazio</p>';
    }
    ?>
    </div>

    <div class="cart-total">
        <p>Total Geral: <span>R$<?= $grand_total; ?>/-</span></p>
        <a href="shop.php" class="option-btn">Continuar Comprando</a>
        <a href="cart.php?delete_all" class="delete-btn <?= ($grand_total > 0) ? '' : 'disabled'; ?>">Apagar Tudo</a>
        <a href="checkout.php" class="btn <?= ($grand_total > 0) ? '' : 'disabled'; ?>">Fazer o Check-out</a>
    </div>

</section>

<?php include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>
