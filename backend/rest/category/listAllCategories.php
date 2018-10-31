<?php
// cabeçalho de resposta
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once('../../config/database.php');
include_once('../../objects/categories.php');

$database = new Database();
$db = $database->getConnection();

// inicia objeto categoria
$category = new Categories($db);

// query categoria
$stmt = $category->listAllCategories();
$num = $stmt->rowCount();

http_response_code(200);
// se existir algum resultado mostra
if($num > 0) {
    $category_arr = array();
    $category_arr["categories"] = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        $category_item = array(
            "id" => $row['id'],
            "name" => $row['name'],
            "path" => $row['path'],
            "parent" => $row['parent_id']
        );

        array_push($category_arr["categories"], $category_item);
    }

    echo json_encode($category_arr);
}
 else {
    echo json_encode(
        array("message" => "Nenhuma categoria encontrado.")
    );
}

?>