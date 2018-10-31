<?php
// cabeçalho de resposta
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


include_once('../../config/database.php');
include_once('../../objects/articles.php');


// recebe parâmetros do artigo
$_POST = json_decode(file_get_contents("php://input"),true);

$id = $_POST['id'];
$name = $_POST['name'];
$description = $_POST['description'];
$imageUrl = $_POST['imageUrl'];
$content = $_POST['content'];
$userId = $_POST['userId'];
$categoryId = $_POST['categoryId'];

if(!isset($name) || !isset($content) || !isset($userId) || !isset($categoryId)) {
    http_response_code(200);
    echo json_encode(
        array(
            "message" => "Existem campos vazios que são obrigatórios!",
            "error" => true)
        );
  exit();  
}
   
$database = new Database();
$db = $database->getConnection();

$article = new Articles($db);

if($id == 0) {
    $isCreated = $article->save($name, $description, $imageUrl, $content, $userId, $categoryId);
    if($isCreated) {
        http_response_code(200);
        echo json_encode( 
            array("message" => "Artigo criado com sucesso.",
            "error" => false)
        );
    }
    else {
        http_response_code(200);
        echo json_encode(
            array(
                "message" => "Erro ao criar o artigo.",
                "error" => true)
            );
    }
}
else {
    $isUpdate = $article->update($id, $name, $description, $imageUrl, $content, $userId, $categoryId);
    if($isUpdate) {
        http_response_code(200);
        echo json_encode( 
            array("message" => "Artigo alterado com sucesso.",
            "error" => false)
        );
    }
    else {
        http_response_code(200);
        echo json_encode(
            array(
                "message" => "Erro ao alterar o artigo.",
                "error" => true)
            );
    }
}

       
?>