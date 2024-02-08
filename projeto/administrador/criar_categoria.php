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
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <form method="POST" action="">
        <p>
            <label for="nova_categoria_nome">Nome da Nova Categoria:</label>
            <input type="text" name="nova_categoria_nome" required>
        <p>

            <label for="nova_categoria_descricao">Descrição da Nova Categoria:</label>
            <textarea name="nova_categoria_descricao" required></textarea>
        <p>

            <label for="nova_categoria_ativo">Ativo:</label>
            <input type="checkbox" name="nova_categoria_ativo">
        <p>

            <input type="submit" name="nova_categoria" class="btn btn-primary" value="Adicionar Nova Categoria">
    </form>
</body>

</html>