<?php
class Categories {
    //conexão com o banco de dados
    private $conn;
   
    // propriedade
    public $id;
    public $name;
    public $parent_id;
    
    // Construtor passando a conexão
    public function __construct($db) {
        $this->conn = $db;
    }

    function statics() {
       // query de lista categoria categoria
       $query = "select COUNT(*) total from categories";
       // prepare query statement
       $stmt = $this->conn->prepare($query);
       // execute query
       $stmt->execute();
       return $stmt;
    
    }

    function save($name, $parent) {
        
        $parent = $parent == '' ? 0 : $parent;
        if($parent == 0) {
            $query = "INSERT INTO `categories` (`id`, `name`, `parent_id`) VALUES (NULL, '".$name."', NULL);";
        } else {
            $query = "INSERT INTO `categories` (`id`, `name`, `parent_id`) VALUES (NULL, '".$name."', ".$parent.");";
        }
        // prepare query statement
        $stmt = $this->conn->prepare($query);
        // execute query
        if($stmt->execute()) {
            return true;
        }
        return false;
     }

     function remove($id) {
        // query de exclui categoria
       $query = "delete from categories where id = ".$id."";
       // prepare query statement
       $stmt = $this->conn->prepare($query);
       // execute query
       if($stmt->execute()) {
           return true;
       }
       return false;
    }

     function isExistsByName($name) {
        // query de lista categoria categoria
       $query = "select * from categories where name = '".$name."'";
       // prepare query statement
       $stmt = $this->conn->prepare($query);
      // execute query
      $stmt->execute();
      $num = $stmt->rowCount();
      
      return $num > 0;
    }

    function getCategoryById($id) {
        // query de lista categoria categoria
       $query = "select * from categories where id = ".$id."";
       // prepare query statement
       $stmt = $this->conn->prepare($query);
       // execute query
       $stmt->execute();
       return $stmt;
    }
    
     function listAllCategories() {
        // query de lista categoria 
       $query = "SELECT id, name, getpath(id) AS path, parent_id FROM categories";
       // prepare query statement
       $stmt = $this->conn->prepare($query);
       // execute query
       $stmt->execute();
       return $stmt;
    }

    function listAllCategoriesParent() {
        // query de lista categoria 
       $query = "SELECT id, name, getpath(id) AS path, parent_id FROM categories";
       // prepare query statement
       $stmt = $this->conn->prepare($query);
       // execute query
       $stmt->execute();
       return $stmt;
    }

}
