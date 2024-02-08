<?php
// Importa a configuração de conexão com o banco de dados.
require_once('../conexao/conexao.php');

// Bloco que será executado quando o formulário for submetido.
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Bloco para adicionar nova categoria.
    if (isset($_POST['nova_categoria'])) {
        $nova_categoria_nome = $_POST['nova_categoria_nome'];
        $nova_categoria_descricao = $_POST['nova_categoria_descricao'];
        $nova_categoria_ativo = isset($_POST['nova_categoria_ativo']) ? 1 : 0;

        // Verificar se a categoria já existe
        $sql_verifica_categoria = "SELECT COUNT(*) FROM CATEGORIA WHERE CATEGORIA_NOME = :nome";
        $stmt_verifica_categoria = $pdo->prepare($sql_verifica_categoria);
        $stmt_verifica_categoria->bindParam(':nome', $nova_categoria_nome, PDO::PARAM_STR);
        $stmt_verifica_categoria->execute();
        $categoria_existente = $stmt_verifica_categoria->fetchColumn();

        if ($categoria_existente > 0) {
            // Categoria já existe, exibir alerta
            echo '<script>alert("Categoria já existe. Escolha outro nome.");</script>';
        } else {
            // Adicionar nova categoria
            try {
                $sql_categoria = "INSERT INTO CATEGORIA (CATEGORIA_NOME, CATEGORIA_DESC, CATEGORIA_ATIVO) VALUES (:nome, :descricao, :ativo)";
                $stmt_categoria = $pdo->prepare($sql_categoria);
                $stmt_categoria->bindParam(':nome', $nova_categoria_nome, PDO::PARAM_STR);
                $stmt_categoria->bindParam(':descricao', $nova_categoria_descricao, PDO::PARAM_STR);
                $stmt_categoria->bindParam(':ativo', $nova_categoria_ativo, PDO::PARAM_INT);
                $stmt_categoria->execute();

                echo '<script>
                    document.addEventListener("DOMContentLoaded", function() {
                        var myModal = new bootstrap.Modal(document.getElementById("confirmModal"));
                        myModal.show();
                    });
                </script>';
            } catch (PDOException $e) {
                echo "<p style='color:red;'>Erro ao cadastrar categoria: " . $e->getMessage() . "</p>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categoria</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="../include/criar_categoria2.css">
</head>

<body>
    <div class="container pl-5" style="padding:0px; width:60%; border-radius:0%">
        <div class="row">
            <div class="col-12 pt-5">
                <form class="form" action="" method="post" enctype="multipart/form-data">

                    <label for="nova_categoria_nome">Nome da Nova Categoria:</label>
                    <input type="text" name="nova_categoria_nome" required>

                    <label for="nova_categoria_descricao">Descrição da Nova Categoria:</label>
                    <textarea name="nova_categoria_descricao" required></textarea>

                    <label for="nova_categoria_ativo">Ativo:</label>
                    <input type="checkbox" name="nova_categoria_ativo">

                    <input type="submit" name="nova_categoria" class="btn btn-outline-light"
                        value="Adicionar Nova Categoria">
                </form>
            </div>
        </div>
    </div>
</body>

</html>