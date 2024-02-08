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

$produto_id = $_GET['id'];

// Busca as informações do produto.
$stmt_produto = $pdo->prepare("SELECT * FROM PRODUTO WHERE PRODUTO_ID = :produto_id");
$stmt_produto->bindParam(':produto_id', $produto_id, PDO::PARAM_INT);
$stmt_produto->execute();
$produto = $stmt_produto->fetch(PDO::FETCH_ASSOC);

// Busca as categorias.
$stmt_categoria = $pdo->prepare("SELECT * FROM CATEGORIA");
$stmt_categoria->execute();
$categorias = $stmt_categoria->fetchAll(PDO::FETCH_ASSOC);

// Busca o estoque
$stmt_estoque_existente = $pdo->prepare("SELECT * FROM PRODUTO_ESTOQUE WHERE PRODUTO_ID = :produto_id");
$stmt_estoque_existente->bindParam(':produto_id', $produto_id, PDO::PARAM_INT);
$stmt_estoque_existente->execute();
$estoque_existente = $stmt_estoque_existente->fetch(PDO::FETCH_ASSOC);

// Busca as imagens do produto.
$stmt_img = $pdo->prepare("SELECT * FROM PRODUTO_IMAGEM WHERE PRODUTO_ID = :produto_id ORDER BY IMAGEM_ORDEM");
$stmt_img->bindParam(':produto_id', $produto_id, PDO::PARAM_INT);
$stmt_img->execute();
$imagens_existentes = $stmt_img->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Atualizando as URLs das imagens.
    if (isset($_POST['editar_imagem_url'])) {
        foreach ($_POST['editar_imagem_url'] as $imagem_id => $url_editada) {
            $stmt_update = $pdo->prepare("UPDATE PRODUTO_IMAGEM SET IMAGEM_URL = :url WHERE IMAGEM_ID = :imagem_id");
            $stmt_update->bindParam(':url', $url_editada, PDO::PARAM_STR);
            $stmt_update->bindParam(':imagem_id', $imagem_id, PDO::PARAM_INT);
            $stmt_update->execute();
        }
    }

    // Se o produto já existe na tabela PRODUTO_ESTOQUE, atualiza a quantidade em estoque.
    if (isset($_POST['produto_qtd']) && $_POST['produto_qtd'] !== null) {
        // Se o produto já existe na tabela PRODUTO_ESTOQUE, atualiza a quantidade em estoque.
        if ($estoque_existente) {
            $nova_quantidade = $_POST['produto_qtd'];

            try {
                $stmt_update_estoque = $pdo->prepare("UPDATE PRODUTO_ESTOQUE SET PRODUTO_QTD = :nova_quantidade WHERE PRODUTO_ID = :produto_id");
                $stmt_update_estoque->bindParam(':nova_quantidade', $nova_quantidade, PDO::PARAM_INT);
                $stmt_update_estoque->bindParam(':produto_id', $produto_id, PDO::PARAM_INT);
                $stmt_update_estoque->execute();

                echo "<p style='color:green;'></p>";
            } catch (PDOException $e) {
                echo "<p style='color:red;'>Erro ao atualizar quantidade em estoque: " . $e->getMessage() . "</p>";
            }
        } else {
            // Se o produto não existe na tabela PRODUTO_ESTOQUE, insere uma nova entrada.
            $nova_quantidade = $_POST['produto_qtd'];

            try {
                $stmt_inserir_estoque = $pdo->prepare("INSERT INTO PRODUTO_ESTOQUE (PRODUTO_ID, PRODUTO_QTD) VALUES (:produto_id, :nova_quantidade)");
                $stmt_inserir_estoque->bindParam(':produto_id', $produto_id, PDO::PARAM_INT);
                $stmt_inserir_estoque->bindParam(':nova_quantidade', $nova_quantidade, PDO::PARAM_INT);
                $stmt_inserir_estoque->execute();

                echo "";
            } catch (PDOException $e) {
                echo "<p style='color:red;'>Erro ao adicionar produto ao estoque: " . $e->getMessage() . "</p>";
            }
        }
    } else {
        echo "<p style='color:red;'>Erro: A quantidade do produto não foi fornecida.</p>";
    }

    // Atualizando as informações do produto.
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $preco = $_POST['preco'];
    $categoria_id = $_POST['categoria_id'];
    $ativo = isset($_POST['ativo']) ? "\x01" : "\x00";
    $desconto = $_POST['desconto'];

    try {
        $stmt_update_produto = $pdo->prepare("UPDATE PRODUTO SET PRODUTO_NOME = :nome, PRODUTO_DESC = :descricao, PRODUTO_PRECO = :preco, CATEGORIA_ID = :categoria_id, PRODUTO_ATIVO = :ativo, PRODUTO_DESCONTO = :desconto WHERE PRODUTO_ID = :produto_id");
        $stmt_update_produto->bindParam(':nome', $nome);
        $stmt_update_produto->bindParam(':descricao', $descricao);
        $stmt_update_produto->bindParam(':preco', $preco);
        $stmt_update_produto->bindParam(':categoria_id', $categoria_id);
        $stmt_update_produto->bindParam(':ativo', $ativo);
        $stmt_update_produto->bindParam(':desconto', $desconto);
        $stmt_update_produto->bindParam(':produto_id', $produto_id);
        $stmt_update_produto->execute();

        echo '<script>
        document.addEventListener("DOMContentLoaded", function() {
            var myModal = new bootstrap.Modal(document.getElementById("confirmModal"));
            myModal.show();
        });
      </script>';
    } catch (PDOException $e) {
        echo "<p style='color:red;'>Erro ao atualizar produto: " . $e->getMessage() . "</p>";
    }
}

?>

<head>
    <meta charset="UTF-8">
    <title>Editar Produto</title>
    <!-- Adicione a biblioteca jQuery -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>

    <!-- Adicione a biblioteca Popper -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>

    <!-- Adicione a biblioteca Bootstrap (JS) para Bootstrap 4 -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <style>
        .modal {
            padding-top: 15%;
            backdrop-filter: blur(2px);
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
            color: green;
            font-size: xx-large;
            padding-left: 42%;
        }

        /* Personalize o botão de fechar do modal */
        .modal-header .close {
            color: #fff;
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
            padding-right: 120px;
        }
    </style>
</head>

<body>
    <h2>Editar Produto</h2>
    <form action="" id="editProduto" method="post" enctype="multipart/form-data">
        <label for="nome">Nome:</label>
        <input type="text" name="nome" id="nome" value="<?= $produto['PRODUTO_NOME'] ?>" required>
        <p>
            <label for="descricao">Descrição:</label>
            <textarea name="descricao" id="descricao" required><?= $produto['PRODUTO_DESC'] ?></textarea>
        <p>
            <label for="preco">Preço:</label>
            <input type="number" name="preco" id="preco" step="0.01" value="<?= $produto['PRODUTO_PRECO'] ?>" required>
        <p>
            <label for="desconto">Desconto:</label>
            <input type="number" name="desconto" id="desconto" step="0.01" value="<?= $produto['PRODUTO_DESCONTO'] ?>" required>
        <p>
            <label for="categoria_id">Categoria:</label>
            <select name="categoria_id" id="categoria_id" required>
                <?php
                foreach ($categorias as $categoria) :
                    $selected = $produto['CATEGORIA_ID'] == $categoria['CATEGORIA_ID'] ? 'selected' : '';
                ?>
                    <option value="<?= $categoria['CATEGORIA_ID'] ?>" <?= $selected ?>><?= $categoria['CATEGORIA_NOME'] ?></option>
                <?php endforeach; ?>
            </select>
        <p>
            <label for="estoque">Estoque:</label>
            <input type="number" name="produto_qtd" id="produto_qtd" value="<?= isset($produto_estoque['PRODUTO_QTD']) ? $produto_estoque['PRODUTO_QTD'] : 0 ?>" required>
        <P>
            <label for="ativo">Ativo:</label>
            <input type="checkbox" name="ativo" id="ativo" value="1" <?= $produto['PRODUTO_ATIVO'] ? 'checked' : '' ?>>
        <p>
            <!-- Lista de imagens existentes -->
            <?php
            foreach ($imagens_existentes as $imagem) {
                echo '<div>';
                echo '<label>URL da Imagem:</label>';
                echo '<input type="text" name="editar_imagem_url[' . $imagem['IMAGEM_ID'] . ']" value="' . $imagem['IMAGEM_URL'] . '">';
                echo '</div>';
            }
            ?>
        <p>
            <button type="submit">Atualizar Produto</button>

        <div class="modal" id="confirmModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Atenção</h5>
                    </div>
                    <div class="modal-body">
                        <p>Seu Produto Foi Atualizado Com Sucesso!!!</p>
                    </div>
                    <div class="modal-footer d-flex">
                        <button type="button" class="btn btn-large btn-outline-light" data-dismiss="modal" aria-label="Close">Fechar</button>
                        <a href="listar_produtos.php" class="btn btn-outline-light">Lista de Produtos</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</body>

</html>