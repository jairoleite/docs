<?php
// cabeçalho de resposta
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once('../../config/database.php');
include_once('../../objects/categories.php');

//$_GET = json_decode(file_get_contents("php://input"),true);
$id = $_GET['id'];

$database = new Database();
$db = $database->getConnection();

// inicia objeto categoria
$category = new Categories($db);

// query categoria
$stmt = $category->getCategoryById($id);
$num = $stmt->rowCount();

http_response_code(200);
// se existir algum resultado mostra
if($num > 0) {
    $category_arr = array();
    $category_arr["category"] = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        $category_item = array(
            "id" => $row['id'],
            "name" => $row['name']
        );

        array_push($category_arr["category"], $category_item);
    }

    echo json_encode($category_arr);
}
 else {
    echo json_encode(
        array("message" => "Nenhuma categoria encontrado.")
    );
}

?>