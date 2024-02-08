<!DOCTYPE html>
<html lang="pt">
<?php
session_start();
require_once('../conexao/conexao.php');

include_once('../include/nav.php');

if (!isset($_SESSION['admin_logado'])) {
    header("Location:login.php");
    exit();
}

try {
    $stmt = $pdo->prepare("SELECT PRODUTO.*, CATEGORIA.CATEGORIA_NOME, PRODUTO_IMAGEM.IMAGEM_URL, PRODUTO_ESTOQUE.PRODUTO_QTD
                           FROM PRODUTO 
                           JOIN CATEGORIA ON PRODUTO.CATEGORIA_ID = CATEGORIA.CATEGORIA_ID 
                           LEFT JOIN PRODUTO_ESTOQUE ON PRODUTO.PRODUTO_ID = PRODUTO_ESTOQUE.PRODUTO_ID
                           LEFT JOIN PRODUTO_IMAGEM ON PRODUTO.PRODUTO_ID = PRODUTO_IMAGEM.PRODUTO_ID");
    $stmt->execute();
    $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<p style='color:red;'>Erro ao listar produtos: " . $e->getMessage() . "</p>";
}
?>


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Listagem de Produtos</title>
    <link rel="stylesheet" href="../include/lista_produtos.css">
    <style>
       .modal {
            padding-top: 15%;
            backdrop-filter: blur(3px);
        }

        .modal-content {
            background-color: #333;
            border: 1px solid #333;
            border-radius: 10px;
            width: 100%;
            height: 20%;
        }

        /* Personalize o cabeçalho do modal */
        .modal-header {
            background-color: #333;
            border-bottom: 2px solid #fff;
            border-radius: 10px 10px 0 0;
            text-align: center;
            align-items: center;
            padding-left: 42%;
        }
        
        .modal h1 {
            color: red;
            font-size: xx-large;
        }

        /* Personalize o corpo do modal */
        .modal-body {
            color: #fff;
            font-size: large;
            font-family: Arial, Helvetica, sans-serif;
            text-align: center;
            padding-top: 10%;
        }

        /* Personalize o rodapé do modal */
        .modal-footer {
            background-color: #333;
            border-top: 0px solid #17a2b8;
            border-radius: 0 0 10px 10px;
        }
    </style>
</head>
<div class="container mt-5 pt-5">
    <h1 class="display-4 py-2">Lista de Produtos</h1>
</div>

<body class="cointainer align-center">
    <div class="container mt-3 mb-3">
        <div class="row">
            <div class="col-lg-12 col-md-10 col-sm-12 d-flex align-center d ">
                <table class="table table-striped table-dark">
                    <thead class="align-center">
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Descrição</th>
                            <th>Preço</th>
                            <th>Categoria</th>
                            <th>Estoque</th>
                            <th>Ativo</th>
                            <th>Desconto</th>
                            <th>Imagem</th>
                            <th width="15%">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($produtos as $produto) : ?>
                            <tr>
                                <td><?php echo $produto['PRODUTO_ID']; ?></td>
                                <td><?php echo $produto['PRODUTO_NOME']; ?></td>
                                <td><?php echo $produto['PRODUTO_DESC']; ?></td>
                                <td><?php echo $produto['PRODUTO_PRECO']; ?></td>
                                <td><?php echo $produto['CATEGORIA_NOME']; ?></td>
                                <td><?php echo $produto['PRODUTO_QTD'];
                                    if ($produto['PRODUTO_QTD'] == '') {
                                        echo "Sem Estoque";
                                    } ?></td>
                                <td><?php echo ($produto['PRODUTO_ATIVO'] == 1 ? 'Sim' : 'Não'); ?></td>
                                <td><?php echo $produto['PRODUTO_DESCONTO']; ?></td>
                                <td><img src="<?php echo $produto['IMAGEM_URL']; ?>" alt="<?php echo $produto['PRODUTO_NOME']; ?>" width="50"></td>
                                <td>
                                    <a class="btn btn-secondary" href="editar_produto.php?id=<?php echo $produto['PRODUTO_ID']; ?>">Editar</a> |
                                    <!-- Button trigger modal -->
                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#excluirModal">
                                        Excluir
                                    </button>

                                    <!-- Modal -->
                                    <div class="modal fade" id="excluirModal" tabindex="-1" aria-labelledby="excluirModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content d-flex">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Atenção</h1>
                                                </div>
                                                <div class="modal-body">
                                                    Página de Exclusão Ainda Está em Desenvolvimento
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Fechar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
            </div>
        </div>
    </div>
</body>

</html>