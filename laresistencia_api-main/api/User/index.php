<?php
require_once '../../vendor/autoload.php';

use Models\Usuario;

header('Content-Type: application/json');
// Handle HTTP methods
$method = $_SERVER['REQUEST_METHOD'];
switch ($method) {
    case 'GET':
        // Read operation (fetch books)
        // $stmt = $pdo->query('SELECT * FROM books');
        // $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode([new Usuario(1, 'Robson', 'Rua 1123'), new Usuario(2, 'Anderson', 'ruas teste', '2312321312')]);
        break;
}