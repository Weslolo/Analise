<?php

if (isset($message)) {
    foreach ($message as $msg) {
        echo '
        <div class="message">
            <span>' . htmlspecialchars($msg) . '</span>
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
        </div>
        ';
    }
}

?>

<header class="header">

    <div class="flex">

        <a href="admin_page.php" class="logo">LR<span>.</span></a>

        <nav class="navbar">
            <a href="home.php">Home</a>
            <a href="shop.php">Comprar</a>
            <a href="orders.php">Pedidos</a>
            <a href="sobre.php">Sobre</a>
            <a href="contato.php">Contato</a>
        </nav>

        <div class="icons">
            <div id="menu-btn" class="fas fa-bars"></div>
            <div id="user-btn" class="fas fa-user"></div>
            <a href="pesquisa.php" class="fas fa-search"></a>
            <?php
                if (isset($user_id)) {
                    $count_cart_items = $conn->prepare("SELECT * FROM carrinho WHERE usuario_id = ?");
                    $count_cart_items->execute([$user_id]);
                    $count_wishlist_items = $conn->prepare("SELECT * FROM lista_desejos WHERE usuario_id = ?");
                    $count_wishlist_items->execute([$user_id]);
                }
            ?>
            <a href="wishlist.php"><i class="fas fa-heart"></i><span>(<?= isset($count_wishlist_items) ? $count_wishlist_items->rowCount() : '0'; ?>)</span></a>
            <a href="cart.php"><i class="fas fa-shopping-cart"></i><span>(<?= isset($count_cart_items) ? $count_cart_items->rowCount() : '0'; ?>)</span></a>
        </div>

        <div class="profile">
            <?php
                if (isset($user_id)) {
                    $select_profile = $conn->prepare("SELECT * FROM usuarios WHERE id = ?");
                    $select_profile->execute([$user_id]);
                    $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);

                    if ($fetch_profile) {
                        $image = !empty($fetch_profile['imagem']) ? $fetch_profile['imagem'] : 'default.png';
                        $name = !empty($fetch_profile['nome']) ? $fetch_profile['nome'] : 'Usuário';
                    } else {
                        $image = 'default.png';
                        $name = 'Usuário';
                    }
                } else {
                    $image = 'default.png';
                    $name = 'Usuário';
                }
            ?>
            <img src="uploaded_img/<?= htmlspecialchars($image); ?>" alt="Imagem do perfil">
            <p><?= htmlspecialchars($name); ?></p>
            <a href="user_profile_update.php" class="btn">Atualizar perfil</a>
            <a href="logout.php" class="delete-btn">Sair</a>
            <div class="flex-btn">
                <a href="login.php" class="option-btn">Login</a>
                <a href="register.php" class="option-btn">Registrar</a>
            </div>
        </div>

    </div>

</header>
