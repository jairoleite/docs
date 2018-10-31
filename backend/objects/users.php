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

     function save($name, $email, $password, $admin) {
        // query de inserção categoria
       $query = "insert into users(name, email, password, admin) 
                 values('".$name."', '".$email."', '".$password."', '".$admin."')";
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
    
    // function logger($login, $password) {
    //      // query verifica se o usuario existe com esssa senha
    //     $query = " select * from usuario where login = '".$login."' and senha = '".$password."' ";
    //     // prepare query statement
    //     $stmt = $this->conn->prepare($query);
    //     // execute query
    //     $stmt->execute();
    //     return $stmt;
    //  }

}
