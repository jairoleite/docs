<?php
// cabeçalho de resposta
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


include_once('../../config/database.php');
include_once('../../objects/users.php');


// recebe nome da categoria
$_POST = json_decode(file_get_contents("php://input"),true);
$id = $_POST['id'];
$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['password'];
$admin = $_POST['admin'];

if(!isset($name) || !isset($email) || !isset($password)) {
    http_response_code(200);
    echo json_encode(
        array(
            "message" => "Existem campos obrigatórios para preencher!".$name.",".$email.",".$password."",
            "error" => true)
        );
  exit();  
  }
   
$database = new Database();
$db = $database->getConnection();

$user = new Users($db);

$isCreated = $user->save($name, $email, $password, $admin);
if($isCreated) {
    http_response_code(200);
    echo json_encode( 
        array("message" => "Usuário criado com sucesso.",
        "error" => false)
    );
}
else {
    http_response_code(200);
    echo json_encode(
        array(
            "message" => "Ocorreu um erro ao criar o usuário.",
            "error" => true)
        );
    }
       
?>