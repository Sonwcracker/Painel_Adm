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


// Bloco que será executado quando o formulário for submetido.
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Pegando os valores do POST.
    $nome = $_POST['adm_nome'];
    $email = $_POST['adm_email'];
    $senha = $_POST['adm_senha'];
    $ativo = isset($_POST['adm_ativo']) ? 1 : 0;

    // Inserindo produto no banco.
    try {
        $sql = "INSERT INTO ADMINISTRADOR (ADM_NOME, ADM_EMAIL, ADM_SENHA, ADM_ATIVO) VALUES (:adm_nome, :adm_email, :adm_senha, :adm_ativo)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':adm_nome', $nome, PDO::PARAM_STR);
        $stmt->bindParam(':adm_email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':adm_senha', $senha, PDO::PARAM_STR);
        $stmt->bindParam(':adm_ativo', $ativo, PDO::PARAM_INT);
        $stmt->execute();

        // Pegando o ID do produto inserido.
        $ADM_ID = $pdo->lastInsertId();

        echo "<p style='color:green;'>Administrador cadastrado com sucesso!</p>";
    } catch (PDOException $e) {
        echo "<p style='color:red;'>Erro ao cadastrar Administrador: " . $e->getMessage() . "</p>";
    }
}
?>

<!-- Início do código HTML -->
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Admin</title>
</head>
<body>
<h2>Cadastrar Admin</h2>
<form action="" method="post" enctype="multipart/form-data">
    <!-- Campos do formulário para inserir informações do produto -->
    <label for="adm_nome">Nome:</label>
    <input type="text" name="adm_nome" id="adm_nome" required>
    <p>
    <label for="adm_email">Email</label>
    <input type="text" name="adm_email" id="adm_email" required>
    <p>
    <label for="adm_senha">Senha:</label>
    <input type="text" name="adm_senha" id="adm_senha" required>
    <p>
    <label for="adm_ativo">Ativo:</label>
    <input type="checkbox" name="adm_ativo" id="adm_ativo" value="1" checked>
    <p>
    <button type="submit">Cadastrar Administrador</button>
</form>
</body>
</html>