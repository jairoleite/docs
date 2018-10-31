<?php
class Articles {
    //conexão com o banco de dados
    private $conn;
    
    // propriedade
    public $id;
    public $name;
    public $description;
    public $imageUrl;
    public $content;
    public $user_id;
    public $category_id;

    // Construtor passando a conexão
    public function __construct($db) {
        $this->conn = $db;
    }

    function statics() {
        // query de estatísticas gerais
        $query = "select COUNT(*) total from articles";
        // prepare query statement
        $stmt = $this->conn->prepare($query);
        // execute query
        $stmt->execute();
        return $stmt;
    }

    function listArticles($init, $total) {
        $query = "SELECT SQL_CALC_FOUND_ROWS id, name, description FROM articles 
                     order by id desc LIMIT ".$init.",".$total."";
        // prepare query statement
        $stmt = $this->conn->prepare($query);
        // execute query
        $stmt->execute();
        return $stmt;
     }

     function getArticleById($id) {
        $query = "select * from articles where id = ".$id."";
        // prepare query statement
        $stmt = $this->conn->prepare($query);
        // execute query
        $stmt->execute();
        return $stmt;
     }

     function getArticleByCategoryId($id, $init, $total) {
        $query = "select * from articles where category_id = ".$id." order by id desc limit ".$init.",".$total."";
        // prepare query statement
        $stmt = $this->conn->prepare($query);
        // execute query
        $stmt->execute();
        return $stmt;
     }
    
     function save($name, $description, $imageUrl, $content, $userId, $categoryId) {
        $query = "INSERT INTO `articles` (`id`, `name`, `description`, `imageUrl`, `content`, `user_id`, `category_id`) 
        VALUES (NULL, '".$name."', '".$description."', '".$imageUrl."', '".$content."', ".$userId.", ".$categoryId.");";
        // prepare query statement
        $stmt = $this->conn->prepare($query);
        // execute query
        if($stmt->execute()) {
            return true;
        }
        return false;
     }

     function update($id, $name, $description, $imageUrl, $content, $userId, $categoryId) {
        $query = "UPDATE `articles` SET `name` = '".$name."', `description` = '".$description."', 
        `imageUrl` = '".$imageUrl."', `content` = '".$content."', 
        `user_id` = ".$userId.", `category_id` = ".$categoryId." WHERE `articles`.`id` = ".$id.";";
        // prepare query statement
        $stmt = $this->conn->prepare($query);
        // execute query
        if($stmt->execute()) {
            return true;
        }
        return false;
     }

    
     function remove($id) {
         // query altera conteudo artigo
        $query = "delete from articles where id = ".$id.""; 
         // prepare query statement
        $stmt = $this->conn->prepare($query);
        // execute query
        if($stmt->execute()) {
           return true;
        }
        return false;
     }

    
     
}
