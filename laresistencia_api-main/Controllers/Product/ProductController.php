<?php
namespace Controllers\Product;

use Exception;
use Models\Produto;
use Controllers\Base\BaseController;
use Utils\api\Errors\AuthError;
use Interfaces\IController;

class ProductController extends BaseController implements IController
{
    private $produtos;


    function __construct()
    {
        $this->produtos = [
            new Produto(1, "Lava Prato"),
            new Produto(2, "Detergente"),
            new Produto(3, "Esponja"),
            new Produto(4, "Desinfetante"),
            new Produto(5, "Sabão em Pó"),
            new Produto(6, "Amaciante"),
            new Produto(7, "Água Sanitária"),
            new Produto(8, "Limpa Vidros"),
            new Produto(9, "Desengordurante"),
            new Produto(10, "Saponáceo"),
            new Produto(11, "Lustra Móveis"),
            new Produto(12, "Inseticida"),
            new Produto(13, "Papel Toalha"),
            new Produto(14, "Papel Higiênico"),
            new Produto(15, "Shampoo"),
            new Produto(16, "Condicionador"),
            new Produto(17, "Creme Dental"),
            new Produto(18, "Escova de Dentes"),
            new Produto(19, "Fio Dental"),
            new Produto(20, "Desodorante"),
            new Produto(21, "Perfume"),
            new Produto(22, "Loção Hidratante"),
            new Produto(23, "Protetor Solar"),
            new Produto(24, "Repelente"),
            new Produto(25, "Álcool Gel"),
            new Produto(26, "Álcool Líquido"),
            new Produto(27, "Limpa Alumínio"),
            new Produto(28, "Limpa Inox"),
            new Produto(29, "Cera para Piso"),
            new Produto(30, "Tira Manchas"),
            new Produto(31, "Sabonete Líquido"),
            new Produto(32, "Sabonete em Barra"),
            new Produto(33, "Água Perfumada para Roupas"),
            new Produto(34, "Alvejante sem Cloro"),
            new Produto(35, "Limpador Multiuso"),
            new Produto(36, "Removedor de Ceras"),
            new Produto(37, "Sachê Perfumado"),
            new Produto(38, "Essência para Ambientes"),
            new Produto(39, "Vela Aromática"),
            new Produto(40, "Incenso"),
            new Produto(41, "Lâmpada"),
            new Produto(42, "Pilhas"),
            new Produto(43, "Baterias"),
            new Produto(44, "Filtro de Café"),
            new Produto(45, "Guardanapos"),
            new Produto(46, "Palitos de Dente"),
            new Produto(47, "Luvas de Limpeza"),
            new Produto(48, "Rodo"),
            new Produto(49, "Vassoura"),
            new Produto(50, "Mop")
        ];
    }
    public function getProducts($nome = null)
    {
        $result = $this->produtos;
        if (isset($nome) && $nome != "")
            $result = array_filter($result, function ($v) use ($nome) {
                return str_contains(strtoupper($v->nome), strtoupper($nome));
            });
        // if ($this->useAuth())

        echo json_encode($result);
        // else {
        //     http_response_code(401);
        //     echo json_encode(new AuthError("Unautorized user", "Usuário sem autorização ou invalido, por favor verifique!"));
        // }

    }
    public function getProduct($id)
    {
        $result = array_filter($this->produtos, function ($v) use ($id) {
            return $v->id == $id;
        });
        echo json_encode(count($result) > 0 ? array_shift($result) : "");
    }

    public function get()
    {
        // $this->useAuth();
        if (isset($_GET['id']))
            $this->getProduct($_GET['id']);
        else
            $this->getProducts(isset($_GET['nome']) ? $_GET['nome'] : null);
    }
    public function post()
    {
    }
    public function put()
    {

    }
    public function delete()
    {
    }
}
