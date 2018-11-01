<?php
class Users {
    //conexão com o banco de dados
    private $conn;
    
    // propriedade
    public $id;
    public $name;
    public $email;
    public $password;
    public $admin;

    // Construtor passando a conexão
    public function __construct($db) {
        $this->conn = $db;
    }

    function statics() {
        
       $query = " select COUNT(*) total from users ";
       // prepare query statement
       $stmt = $this->conn->prepare($query);
       // execute query
       $stmt->execute();
       return $stmt;
    }

    function listAllUsers() {
        $query = " select * from users";
        // prepare query statement
        $stmt = $this->conn->prepare($query);
        // execute query
        $stmt->execute();
        return $stmt;
     }

     function save($name, $email, $password, $admin, $token) {
        // query de inserção categoria
       $query = "insert into users(name, email, password, admin, token) 
                 values('".$name."', '".$email."', '".$password."', '".$admin."', '".$token."')";
       // prepare query statement
       $stmt = $this->conn->prepare($query);
       // execute query
       if($stmt->execute()) {
           return true;
       }
       return false;
    } 

    function remove($id) {
       $query = "delete from users where id = ".$id.""; 
        // prepare query statement
       $stmt = $this->conn->prepare($query);
       // execute query
       if($stmt->execute()) {
          return true;
       }
       return false;
    }
    
     function logger($email, $password) {
          // query verifica se o usuario existe com esssa senha
         $query = " select * from users where email = '".$email."' and password = '".$password."' ";
         // prepare query statement
         $stmt = $this->conn->prepare($query);
         // execute query
         $stmt->execute();
         return $stmt;
    }

    function checkToken($token) {
        // query verifica se o usuario existe com esssa senha
       $query = " select * from users where token = '".$token."' ";
       // prepare query statement
       $stmt = $this->conn->prepare($query);
       // execute query
       $stmt->execute();
       return $stmt;
  }

    function getToken($length) {
        $token = "";
        $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
        $codeAlphabet.= "0123456789";
        $max = strlen($codeAlphabet); // edited
   
       for ($i=0; $i < $length; $i++) {
           $token .= $codeAlphabet[random_int(0, $max-1)];
       }
   
       return $token;
   }

}
