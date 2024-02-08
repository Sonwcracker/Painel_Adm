<?php 
//Abaixo são as configuraçõpes do banco de dados.

/*$host é onde o nosso banco de dados está instalado. Variável $host recebe = entre aspas simples 'localhost' que é o local onde está o banco de dados.*/
$host = '144.22.157.228';

/*$db - 'projeto' é o nome do seu projeto que vc criou no php myadmin. */
$db = 'Bravo';

/*$user é variável para o nome do usuário.*/
$user = 'Bravo';

/*Se for root padrão,  */
$pass = 'Bravo';

/*$charset é a variável do tipo de caractere.*/
$charset = 'utf8mb4';

/*$dsn ,diz que tipo de drive estamos usando para o sql. Esse nome muda de acordo com o tipo de banco de dados, o nome do drive muda. */
$dsn = "mysql:host=$host;dbname=$db;charset=$charset"; //Colocando os nomes de variáveis é bom para facilitar o trabalho de ter que digitar tudo.
//É sempre o nome primeiro e depois o nome da variável.

//Tudo isso é para a criação da conexão do banco de dados através do pdo.
try { //try é para verificar se tem algum erro. Ele analisa se a instrução tem algum erro.

$pdo = new PDO($dsn, $user, $pass); /*A variável $pdo é para criar classes de objetos, que nesse caso é o admin. E criar uma conexão com o banco de dados.*/
//Estamos instanciando classes, ou seja, criando 
echo "";
//O catch pega o erro e trata esse erro.
}catch(PDOException $e){ //O erro vai ser capturado na variável $e
    echo "Erro ao tentar conectar com o banco de dados. <p>".$e;
}
//Se não tiver erro, enviar mensagem.
