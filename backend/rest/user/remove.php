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

if(!isset($id)) {
  http_response_code(200);
  echo json_encode(
      array(
          "message" => "Não existe usuário para excluir!",
          "error" => true)
      );
  exit();  
}
   
$database = new Database();
$db = $database->getConnection();

// inicia objeto de post
$user = new Users($db);

// query post
$isDelete = $user->remove($id);

if($isDelete) {
    http_response_code(200);
    echo json_encode( 
        array("message" => "Usuário excluído com sucesso.",
        "error" => false)
    );
}

else {
    http_response_code(200);
    echo json_encode(
        array(
            "message" => "Não foi possível excluír esse usuário",
            "error" => true)
        );
    }
       
        
?>