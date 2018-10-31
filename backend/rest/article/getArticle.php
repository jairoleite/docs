<?php
// cabeçalho de resposta
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once('../../config/database.php');
include_once('../../objects/articles.php');

//$_GET = json_decode(file_get_contents("php://input"),true);
$id = $_GET['id'];

$database = new Database();
$db = $database->getConnection();

// inicia objeto categoria
$article = new Articles($db);

// query categoria
$stmt = $article->getArticleById($id);
$num = $stmt->rowCount();

http_response_code(200);
// se existir algum resultado mostra
if($num > 0) {
    $article_arr = array();
    $article_arr["article"] = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        $article_item = array(
            "id" => $row['id'],
            "name" => $row['name'],
            "description" => $row['description'],
            "imageUrl" => $row['imageUrl'],
            "content" => $row['content'],
            "userId" => $row['user_id'],
            "categoryId" => $row['category_id']
        );

        array_push($article_arr["article"], $article_item);
    }

    echo json_encode($article_arr);
}
 else {
    echo json_encode(
        array("message" => "Nenhuma artigo encontrado.".$id."")
    );
}

?>