<?php
// Inicia a sessão para gerenciamento do usuário.
session_start();

// Importa a configuração de conexão com o banco de dados.
require_once('../conexao/conexao.php');

include_once('../include/nav2.php');

// Verifica se o administrador está logado.
if (!isset($_SESSION['admin_logado'])) {
    header("Location:../login/login.php");
    exit();
}

// Bloco de consulta para buscar categorias.
try {
    $stmt_categoria = $pdo->prepare("SELECT * FROM CATEGORIA");
    $stmt_categoria->execute();
    $categorias = $stmt_categoria->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<p style='color:red;'>Erro ao buscar categorias: " . $e->getMessage() . "</p>";
}

// Bloco de consulta para buscar estoque.
try {
    $stmt_estoque = $pdo->prepare("SELECT * FROM PRODUTO_ESTOQUE");
    $stmt_estoque->execute();
    $estoque = $stmt_estoque->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<p style='color:red;'>Erro ao buscar estoque: " . $e->getMessage() . "</p>";
}

// Bloco que será executado quando o formulário for submetido.
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Pegando os valores do POST.
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $preco = $_POST['preco'];
    $categoria_id = $_POST['categoria_id'];
    $produto_qtd = $_POST['produto_qtd'];
    $ativo = isset($_POST['ativo']) ? 1 : 0;
    $desconto = $_POST['desconto'];
    $imagens = $_POST['imagem_url'];

    // Inserindo produto no banco.
    try {
        $sql = "INSERT INTO PRODUTO (PRODUTO_NOME, PRODUTO_DESC, PRODUTO_PRECO, CATEGORIA_ID, PRODUTO_ATIVO, PRODUTO_DESCONTO) VALUES (:nome, :descricao, :preco, :categoria_id, :ativo, :desconto)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nome', $nome, PDO::PARAM_STR);
        $stmt->bindParam(':descricao', $descricao, PDO::PARAM_STR);
        $stmt->bindParam(':preco', $preco, PDO::PARAM_STR);
        $stmt->bindParam(':categoria_id', $categoria_id, PDO::PARAM_INT);
        $stmt->bindParam(':ativo', $ativo, PDO::PARAM_INT);
        $stmt->bindParam(':desconto', $desconto, PDO::PARAM_STR);
        $stmt->execute();

        // Pegando o ID do produto inserido.
        $produto_id = $pdo->lastInsertId();

        // Inserindo imagens no banco.
        foreach ($imagens as $ordem => $url_imagem) {
            $sql_imagem = "INSERT INTO PRODUTO_IMAGEM (IMAGEM_URL, PRODUTO_ID, IMAGEM_ORDEM) VALUES (:url_imagem, :produto_id, :ordem_imagem)";
            $stmt_imagem = $pdo->prepare($sql_imagem);
            $stmt_imagem->bindParam(':url_imagem', $url_imagem, PDO::PARAM_STR);
            $stmt_imagem->bindParam(':produto_id', $produto_id, PDO::PARAM_INT);
            $stmt_imagem->bindParam(':ordem_imagem', $ordem, PDO::PARAM_INT);
            $stmt_imagem->execute();
        }


        try {

            $sql_estoque = "INSERT INTO PRODUTO_ESTOQUE (PRODUTO_ID, PRODUTO_QTD) VALUES (:produto_id, :produto_qtd)";
            $stmt_estoque = $pdo->prepare($sql_estoque);
            $stmt_estoque->bindParam(':produto_id', $produto_id, PDO::PARAM_INT);
            $stmt_estoque->bindParam(':produto_qtd', $produto_qtd, PDO::PARAM_INT);
            $stmt_estoque->execute();

            $pdo->commit();
        } catch (PDOException $e) {
        }

        echo '<script>
        document.addEventListener("DOMContentLoaded", function() {
            var myModal = new bootstrap.Modal(document.getElementById("confirmModal"));
            myModal.show();
        });
      </script>';
    } catch (PDOException $e) {
        echo "<p style='color:red;'>Erro ao cadastrar produto: " . $e->getMessage() . "</p>";
    }
}

if (isset($_POST['nova_categoria'])) {
    $nova_categoria_nome = $_POST['nova_categoria_nome'];
    $nova_categoria_descricao = $_POST['nova_categoria_descricao'];
    $nova_categoria_ativo = isset($_POST['nova_categoria_ativo']) ? 1 : 0;

    try {
        $sql_categoria = "INSERT INTO CATEGORIA (CATEGORIA_NOME, CATEGORIA_DESC, CATEGORIA_ATIVO) VALUES (:nome, :descricao, :ativo)";
        $stmt_categoria = $pdo->prepare($sql_categoria);
        $stmt_categoria->bindParam(':nome', $nova_categoria_nome, PDO::PARAM_STR);
        $stmt_categoria->bindParam(':descricao', $nova_categoria_descricao, PDO::PARAM_STR);
        $stmt_categoria->bindParam(':ativo', $nova_categoria_ativo, PDO::PARAM_INT);
        $stmt_categoria->execute();

        echo "<p style='color:green;'>Categoria cadastrada com sucesso!</p>";
    } catch (PDOException $e) {
        echo "<p style='color:red;'>Erro ao cadastrar categoria: " . $e->getMessage() . "</p>";
    }
}
?>

<!-- Início do código HTML -->
<!DOCTYPE html>
<html lang="pt-br" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <title>Cadastro de Produto</title>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="../include/cadastrar_produto.css">
    <script>
        // Adiciona um novo campo de imagem URL.
        function adicionarImagem() {
            const containerImagens = document.getElementById('containerImagens');
            const novoInput = document.createElement('input');
            novoInput.type = 'text';
            novoInput.name = 'imagem_url[]';
            containerImagens.appendChild(novoInput);
        }

        function carregarCategorias() {
            $.ajax({
                url: '../Categoria/atualizar_categorias.php', // Verifique o caminho correto para o arquivo PHP
                type: 'GET',
                dataType: 'html',
                success: function(data) {
                    $('#categoria_id').html(data);
                },
                error: function(xhr, status, error) {
                    console.error('Erro ao obter categorias: ' + error);
                }
            });
        }

        $(document).ready(function() {
            // Chama a função para carregar categorias ao carregar a página
            carregarCategorias();

            // Adiciona um ouvinte para o evento de adição de nova categoria
            $('#closemodal').click(function() {
                // Após adicionar uma nova categoria, carrega as categorias novamente
                carregarCategorias();
            });
        });
    </script>
</head>

<body>
    <div class="mt-3">
        <h1 class="display-4 py-2">Cadastrar produto</h1>
    </div>
    <div class="container bg-dark">
        <form action="" method="post" enctype="multipart/form-data">
            <label for="nome">Nome:</label>
            <input type="text" name="nome" id="nome" required>
            <p>
                <label for="descricao">Descrição:</label>
                <textarea name="descricao" id="descricao" required></textarea>
            <p>
                <label for="preco">Preço:</label>
                <input type="number" name="preco" id="preco" step="0.01" required>
            <p>
                <label for="desconto">Desconto:</label>
                <input type="number" name="desconto" id="desconto" step="0.01" required>
            <p>
                <label for="categoria_id">Categoria:</label>
                <select name="categoria_id" class="btn btn-small btn-outline-light" id="categoria_id" required>
                    <p>
                        <?php
                        // Loop para preencher o dropdown de categorias.
                        foreach ($categorias as $categoria) :
                            echo '<option value="' . $categoria['CATEGORIA_ID'] . '">' . $categoria['CATEGORIA_NOME'] . '</option>';
                        ?>
                        <?php endforeach; ?>
                </select>
                <button type="button" class="btn btn-small btn-outline-light" data-bs-toggle="modal" data-bs-target="#exampleModal" id="btnSubmitForm">
                    +
                </button>
                <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h2 class="modal-title fs-5" id="exampleModalLabel">Cadastrar Categoria</h2>
                        </div>
                        <div class="modal-body">
                            <iframe src="../Categoria/criar_categoria2.php" width="100%" height="400px" frameborder="0"></iframe>
                        </div>
                        <div class="modal-footer">
                            <button id="closemodal" onclick="carregarCategorias()" type="button" class="btn btn-light" data-bs-dismiss="modal">Fechar</button>
                        </div>
                    </div>
                </div>
            </div>
            <p>
                <label for="produto_qtd">Estoque:</label>
                <input name="produto_qtd" id="produto_qtd" required>
            <p>
                <label for="ativo">Ativo:</label>
                <input type="checkbox" name="ativo" id="ativo" value="1" checked>
            <p>
                <!-- Área para adicionar URLs de imagens. -->
                <label for="imagem">Imagem URL:</label>
            <div id="containerImagens">
                <input type="text" name="imagem_url[]" required>
            </div>
            <button type="button" class="btn btn-large btn-outline-light" data-dismiss="modal" onclick="adicionarImagem()">Adicionar mais imagens</button>
            <p>
            <p>
                <button type="submit" class="btn btn-large btn-outline-light" data-dismiss="modal">Cadastrar
                    Produto
                </button>

            <div class="modal" id="confirmModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Atenção</h5>
                        </div>
                        <div class="modal-body">
                            <p>Seu Produto Foi Cadastrado Com Sucesso!!!</p>
                        </div>
                        <div class="modal-footer d-flex">
                            <button type="button" class="btn btn-large btn-outline-light" data-dismiss="modal" aria-label="Close">Fechar</button>
                            <a href="listar_produtos.php" class="btn btn-outline-light">Lista de Produtos</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</body>

</html>