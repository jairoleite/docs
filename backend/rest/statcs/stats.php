<?php
// cabeçalho de resposta
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

//banco de dados
include_once('../../config/database.php');

//classes
include_once('../../objects/articles.php');
include_once('../../objects/categories.php');
include_once('../../objects/users.php');

$database = new Database();
$db = $database->getConnection();

//instâncias das classes
$article = new Articles($db);
$category = new Categories($db);
$user = new Users($db);

// query article
$stmtArticle = $article->statics();
$numArticle = $stmtArticle->rowCount();

// query categories
$stmtCategory = $category->statics();
$numCategory = $stmtCategory->rowCount();

// query article
$stmtUser = $user->statics();
$numUser = $stmtUser->rowCount();

$post_arr = array();
$post_arr["stats"] = array();

http_response_code(200);

if($numArticle > 0) {
    while ($rowArticle = $stmtArticle->fetch(PDO::FETCH_ASSOC)) {
        $totalArticle = array(
            "articles" => $rowArticle['total']
        );
        array_push($post_arr["stats"], $totalArticle);
    }
} 

if($numCategory > 0) {
    while ($rowCategory = $stmtCategory->fetch(PDO::FETCH_ASSOC)) {
        $totalCategory = array(
            "categories" => $rowCategory['total']
        );
        array_push($post_arr["stats"], $totalCategory);
    }
}

if($numUser > 0) {
    while ($rowUser = $stmtUser->fetch(PDO::FETCH_ASSOC)) {
        $totalUser = array(
            "users" => $rowUser['total']
        );
        array_push($post_arr["stats"], $totalUser);
    }
} 

echo json_encode($post_arr);

?>
