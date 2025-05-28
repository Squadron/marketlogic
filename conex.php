<?php
//require  'Medoo.php';

/*
Nota: Hacer el require a 'Medoo.php' en el mismo archivo en donde se le este haciendo require a este mismo 'conex.php'
Si se usaran cosas dentro de funciones el require a 'conex.php' debe ser dentro de cada funcion ('Medoo.php puede
sergir estando solo una vez al inicio como de costumbre')
*/

use Medoo\Medoo;


$database = new Medoo([
	// [requerido]
	'type' => 'mysql',
	'host' => 'localhost',
	'database' => 'marketlogic',
	'username' => 'root',
	'password' => '',
 
	// [opcional]
	// 'charset' => 'utf8',
	// 'collation' => 'utf8mb4_general_ci',
	// 'port' => 3306,
]);

$database->select("usuarios", "*");

if ($database->error) {
	echo "Error! al conectar con la DB";
}

//var_dump($database->error);
//var_dump($database->errorInfo);
?>