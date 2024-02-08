<?php
// Importa a configuração de conexão com o banco de dados.
require_once('../conexao/conexao.php');

try {
    $stmt_categoria = $pdo->prepare("SELECT * FROM CATEGORIA");
    $stmt_categoria->execute();
    $categorias = $stmt_categoria->fetchAll(PDO::FETCH_ASSOC);

    // Gere as opções do dropdown de categorias
    $options = '';
    foreach ($categorias as $categoria) {
        $options .= '<option value="' . $categoria['CATEGORIA_ID'] . '">' . $categoria['CATEGORIA_NOME'] . '</option>';
    }

    // Retorna as opções como resposta da requisição AJAX
    echo $options;
} catch (PDOException $e) {
    echo "<p style='color:red;'>Erro ao buscar categorias: " . $e->getMessage() . "</p>";
}
?>