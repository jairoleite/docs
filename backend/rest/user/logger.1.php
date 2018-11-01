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
$token = $_POST['token'];


$database = new Database();
$db = $database->getConnection();

$user = new Users($db);

$stmt = $user->checkToken($token);
$num = $stmt->rowCount();

http_response_code(200);
// se existir algum resultado mostra
if($num > 0) {
    $user = array();
    $user["user"] = array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

        $user_item = array(
            "id" => $row['id'],
            "name" => $row['name'],
            "email" => $row['email'],
            "admin" => $row['admin'],
            "token" => $row['token']
        );

        array_push($user["user"], $user_item);
       
    }

    echo json_encode($user);
}
else {
    echo json_encode(array("checkToken" => false));
}  

?>