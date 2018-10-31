<?php
// cabeçalho de resposta
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once('../../config/database.php');
include_once('../../objects/categories.php');

$_POST = json_decode(file_get_contents("php://input"),true);
$tree = $_POST['tree'];

echo json_encode($tree);

?>