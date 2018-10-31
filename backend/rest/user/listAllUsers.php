<?php
// cabeçalho de resposta
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once('../../config/database.php');
include_once('../../objects/users.php');

$database = new Database();
$db = $database->getConnection();

// inicia objeto usuário
$user = new Users($db);

// query usuário
$stmt = $user->listAllUsers();
$num = $stmt->rowCount();

http_response_code(200);
// se existir algum resultado mostra
if($num > 0) {

    $user_arr = array();
    $user_arr["users"] = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){

        //extract($row);

        $user_item = array(
            "id" => $row['id'],
            "name" => $row['name'],
            "email" => $row['email'],
            "admin" => $row['admin']
        );

        array_push($user_arr["users"], $user_item);
    }

    echo json_encode($user_arr);
}
 else {
    echo json_encode(
        array("message" => "Nenhum usuário encontrado.")
    );
}

?>