<?php 

abstract class Basemodel {
    protected $id = null;
    protected static $table = '';   

    public function save(){
        $pdo = Database::getConnection();

        $stmt = $pdo->prepare("INSERT INTO " . static::$table . " (...) VALUES (?, ?)");
        $stmt->execute([?, ?]);

    }
    public function delete(){
        //todo
    }
    public function findByID(){
        //todo
    }
};