<?php
// Inicia a sessão para gerenciamento do usuário.
session_start();

// Importa a configuração de conexão com o banco de dados.
require_once('../conexao/conexao.php');

include_once('../include/nav.php');

// Verifica se o administrador está logado.
if (!isset($_SESSION['admin_logado'])) {
    header("Location:login.php");
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

        echo "<p style='color:green;'>Produto cadastrado com sucesso!</p>";
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
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <title>Cadastro de Produto</title>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        // Adiciona um novo campo de imagem URL.
        function adicionarImagem() {
            const containerImagens = document.getElementById('containerImagens');
            const novoInput = document.createElement('input');
            novoInput.type = 'text';
            novoInput.name = 'imagem_url[]';
            containerImagens.appendChild(novoInput);
        }

        function atualizarCategorias() {
        $.ajax({
            url: 'atualizar_categorias.php',
            type: 'GET',
            dataType: 'html',
            success: function(data) {
                $('#categoria_id').html(data);
            },
            error: function(error) {
                console.error('Erro ao buscar categorias:', error);
            }
        });
    }

    // Chame a função para carregar as categorias no início
    atualizarCategorias();

    // Define a função para ser chamada a cada 5 segundos
    setInterval(atualizarCategorias, 5000);
    </script>
</head>

<body>
    <h2>Cadastrar Produto</h2>
    <form action="" method="post" enctype="multipart/form-data">
        <!-- Campos do formulário para inserir informações do produto -->
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
            <select name="categoria_id" id="categoria_id" required>
                <p>

                    <?php
                    // Loop para preencher o dropdown de categorias.
                    foreach ($categorias as $categoria) :
                        echo '<option value="' . $categoria['CATEGORIA_ID'] . '">' . $categoria['CATEGORIA_NOME'] . '</option>';
                    ?>


                    <?php endforeach; ?>
            </select>
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal" id="btnSubmitForm">
                Nova Categoria
            </button>
            <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Cadastrar Categoria</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <iframe src="criar_categoria.php" width="800" height="400" frameborder="0"></iframe>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

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
        <button type="button" onclick="adicionarImagem()">Adicionar mais imagens</button>
        <p>
        <p>
            <button type="submit">Cadastrar Produto</button>
    </form>
</body>

</html>