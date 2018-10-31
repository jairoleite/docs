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

// session_start inicia a sessão
session_start();

// recebe nome da categoria
$_POST = json_decode(file_get_contents("php://input"),true);
$email = $_POST['email'];
$password = $_POST['password'];

if(!isset($email) || !isset($password)) {
    http_response_code(200);
    echo json_encode(
        array(
            "message" => "O email e a senha não pode ser vazios",
            "error" => true)
        );
  exit();  
}

if((!isset ($_SESSION['id']) == true) and 
   (!isset ($_SESSION['name']) == true)
   (!isset ($_SESSION['email']) == true)
   (!isset ($_SESSION['admin']) == true)) {
    
    unset($_SESSION['id']);
    unset($_SESSION['name']);
    unset($_SESSION['email']);
    unset($_SESSION['admin']);

  }
  else {
    $user_arr = array();
    $user_item = array(
        "id" => $_SESSION['id'],
        "name" => $_SESSION['name'],
        "email" => $_SESSION['email'],
        "admin" => $_SESSION['admin']
    );
    
    array_push($user_arr, $user_item);
    echo json_encode($user_arr);
    exit();
}
   

$database = new Database();
$db = $database->getConnection();

$user = new Users($db);

$stmt = $user->logger($email, $password);
$num = $stmt->rowCount();

http_response_code(200);
// se existir algum resultado mostra
if($num > 0) {

    $user_arr = array();
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

        $user_item = array(
            "id" => $row['id'],
            "name" => $row['name'],
            "email" => $row['email'],
            "admin" => $row['admin']
        );

        array_push($user_arr, $user_item);

        $_SESSION['id'] = $row['id'];
        $_SESSION['name'] = $row['name'];
        $_SESSION['email'] = $row['email'];
        $_SESSION['admin'] = $row['admin'];
    }

    echo json_encode($user_arr);
}
else {
    http_response_code(200);
    echo json_encode(
        array(
            "message" => "Ocorreu um erro ao tentar logar.",
            "error" => true)
        );
}  

?>