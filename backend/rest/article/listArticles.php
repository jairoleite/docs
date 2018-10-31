<?php
// cabeçalho de resposta
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once('../../config/database.php');
include_once('../../objects/articles.php');


// recebe nome da categoria
//$_GET = json_decode(file_get_contents("php://input"),true);
$page = $_GET['page'];
$page = $page == 0 || $page == 1  ? 1 : $page;

$size = 5;
$init = (($page * $size) - $size);
// echo $init;

$database = new Database();
$db = $database->getConnection();

// inicia objeto categoria
$article = new Articles($db);

// query categoria
$stmt = $article->listArticles($init, $size);
$num = $stmt->rowCount();

// query article
$stmtArticle = $article->statics();
$numArticle = $stmtArticle->rowCount();
$countTotal= null;
if($numArticle > 0) {
    while ($rowArticle = $stmtArticle->fetch(PDO::FETCH_ASSOC)) {
       $countTotal = $rowArticle['total'];
    }
} 

http_response_code(200);
// se existir algum resultado mostra
if($num > 0) {
    $article_arr = array();
    $article_arr["articles"] = array();
    $article_arr["limit"] = array();
    $article_arr["count"] = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $article_item = array(
            "id" => $row['id'],
            "name" => $row['name'],
            "description" => $row['description']
        );

        array_push($article_arr["articles"], $article_item);
    }
    array_push($article_arr["limit"], $size);
    array_push($article_arr["count"], $countTotal);

    echo json_encode($article_arr);
}
 else {
    echo json_encode(
        array("message" => "Nenhuma artigo encontrado.")
    );
}

?>