<?php
// cabeçalho de resposta
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


include_once('../../config/database.php');
include_once('../../objects/categories.php');


// recebe nome da categoria
$_POST = json_decode(file_get_contents("php://input"),true);

$name = $_POST['name'];
$parentId = $_POST['parentId'];

if(!isset($name)) {
    http_response_code(200);
    echo json_encode(
        array(
            "message" => "O nome é obrigatório!",
            "error" => true)
        );
  exit();  
}
   
$database = new Database();
$db = $database->getConnection();

$category = new Categories($db);

//verifica se existe essa categoria cadastrada
if($category->isExistsByName($name)) {
    http_response_code(200);
    echo json_encode(
        array(
            "message" => "Essa categoria já está cadastrada!",
            "error" => true)
        );
  exit();  
}

$isCreated = $category->save($name, $parentId);
if($isCreated) {
    http_response_code(200);
    echo json_encode( 
        array("message" => "Categoria criada com sucesso.",
        "error" => false)
    );
}
else {
    http_response_code(200);
    echo json_encode(
        array(
            "message" => "Erro ao criar a categoria.",
            "error" => true)
        );
    }
       
?>