<?php
require '../Slim/Slim/Slim.php';
\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();
$app->response()->header('Content-Type', 'application/json;charset=utf-8');
$app->get('/', function () {
});

$app->get('/categorias','getCategorias');
$app->get('/categorias/:id','getCategoria');
$app->post('/categorias','addCategoria');
$app->post('/categorias/:id','saveCategoria');
$app->delete('/categorias/:id','deleteCategoria');

$app->run();

function getConn()
{
	return new PDO(
	'mysql:host=localhost;dbname=casePHP',
	'root',
	'',
	array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
}

function getCategoria($id){

	$conn = getConn();
	$sql = "SELECT * FROM tb_categoria WHERE id=:id";
	$stmt = $conn->prepare($sql);
	$stmt->bindParam("id",$id);
	$stmt->execute();
	$categoria = $stmt->fetchObject();
	echo json_encode($categoria);

}

function getCategorias()
{
	$stmt = getConn()->query("SELECT * FROM tb_categoria");
	$categorias = $stmt->fetchAll(PDO::FETCH_OBJ);
	echo "{categorias:".json_encode($categorias)."}";
}

function addCategoria()
{
	$request = \Slim\Slim::getInstance()->request();
	$categoria = json_decode($request->getBody());
	$sql = "INSERT INTO tb_categoria (nome) values (:nome) ";
	$conn = getConn();
	$stmt = $conn->prepare($sql);
	$stmt->bindParam("nome",$categoria->nome);
	$stmt->execute();
	$categoria->id = $conn->lastInsertId();
	echo json_encode($categoria);
}

function saveCategoria($id)
{
	$request = \Slim\Slim::getInstance()->request();
	$categoria = json_decode($request->getBody());
	$sql = "UPDATE tb_categoria SET nome=:nome WHERE id=:id";
	$conn = getConn();
	$stmt = $conn->prepare($sql);
	$stmt->bindParam("nome",$categoria->nome);
	$stmt->bindParam("id",$id);
	$stmt->execute();
	echo json_encode($categoria);
}

function deletecategoria($id)
{
	$sql = "DELETE FROM tb_categoria WHERE id=:id";
	$conn = getConn();
	$stmt = $conn->prepare($sql);
	$stmt->bindParam("id",$id);
	$stmt->execute();
	$stmt = getConn()->query("SELECT * FROM tb_categoria");
	$categorias = $stmt->fetchAll(PDO::FETCH_OBJ);
	echo "{categorias:".json_encode($categorias)."}";
}