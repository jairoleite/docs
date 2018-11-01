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
$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['password'];
//gera md5
$password = md5($password);

//banco
$database = new Database();
$db = $database->getConnection();

$user = new Users($db);

//gera token para validação
$token = $user->getToken(10); 

//cria usuário
$isCreated = $user->save($name, $email, $password, 0, $token);
if($isCreated) {
    http_response_code(200);
    echo json_encode( 
        array("message" => "Usuário criado com sucesso, acesse com seu e-mail e senha.",
        "error" => false)
    );
}
else {
    http_response_code(200);
    echo json_encode(
        array(
            "message" => "Não foi possível cadastrar esse usuário!",
            "error" => true)
        );
    }
?>