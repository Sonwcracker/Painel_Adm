<?php 
/*Processa_login vai processar os dados de entrada da tela de login. */
session_start();

/*Require é requerido uma vez o arquivo de login. Pode usar Include porém se não existir o arquivo ele continua executando o arquivo. */
require_once('../conexao/conexao.php');

/*Essa variável irá receber o valor nome que vai entrar no campo nome da tela de login. */
$nome = $_POST['nome'];
$senha = $_POST['senha'];

/*Abaixo é uma consulta de sql, essa consulta serve para impedir que algum hacker tente invadir o banco de dados. 

Depois de ADM_ATIVO o 0 é para inativo e o 1 é para ativo. É para saber se administrador está ou não ativo.*/
$sql = "SELECT * FROM ADMINISTRADOR WHERE ADM_NOME=:nome AND ADM_SENHA=:senha AND ADM_ATIVO=1";
/*Declarações preparadas protege contra injestão de sql 

Conexão com o banco de dados pode ser feito com PDO ou mysql.
PDO é mais novo.

A variável abaixo vai preparar para receber a variável sql que está acima.
E o parâmetro prepare vai */

$query = $pdo->prepare($sql);

/*Os 2 pontos abaixo é usado para Metódo Estático, está ligado a Orientação a Objetos. 
PDO::PARAM_STR é para Metódo Estático. 

:: é operador de parâmetro de resolução de escopo.*/
$query->bindParam(':nome', $nome, PDO::PARAM_STR); //Nos Placeholders, não pode ter espaços.

/*bindParm - Vincule esse parâmetro pra mim. */
$query->bindParam(':senha', $senha, PDO::PARAM_STR);

/*Para executar tudo de cima usaremos o */
$query->execute();
/*Se a contagem de linha for maior que zero, o ADM está ATIVO. E existi esse usuário no banco de dados. 
Se a condição for true, ele entra no bloco de if else.*/
if ($query->rowCount() > 0){
    /*$_SESSION é a variável global de session.
    Abaixo o resultado dessa variável tem que ser true - verdadeiro para continuar. */
    $_SESSION['admin_logado'] = true;
    header('Location: painel_admin.php');
}else{
    /*Se for false, vai redirecionar para a tela de login, para receber os dados verdadeiros e ai sim vai executar e entrar no painel_admin.php  */
    header('Location: login.php?erro');
}







?>