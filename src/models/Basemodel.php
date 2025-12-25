<?php

abstract class BaseModel 
{
    protected static $pdo;
    protected static $table = '';
    
    public static function setPDO($pdo) 
    {
        self::$pdo = $pdo;
    }
    
    public static function save($data) 
    {
        $id = $data['id'] ?? null;
        unset($data['id']);
        
        if ($id) {
            $set = [];
            foreach ($data as $key => $value) {
                $set[] = "$key = ?";
            }
            $sql = "UPDATE " . static::$table . " SET " . implode(', ', $set) . " WHERE id = ?";
            $stmt = self::$pdo->prepare($sql);
            $values = array_values($data);
            $values[] = $id;
            $stmt->execute($values);
            return $id;
        } else {
            $columns = implode(', ', array_keys($data));
            $placeholders = implode(', ', array_fill(0, count($data), '?'));
            $sql = "INSERT INTO " . static::$table . " ($columns) VALUES ($placeholders)";
            $stmt = self::$pdo->prepare($sql);
            $stmt->execute(array_values($data));
            return self::$pdo->lastInsertId();
        }
    }
    
    public static function findById($id) 
    {
        $sql = "SELECT * FROM " . static::$table . " WHERE id = ?";
        $stmt = self::$pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public static function delete($id) 
    {
        $sql = "DELETE FROM " . static::$table . " WHERE id = ?";
        $stmt = self::$pdo->prepare($sql);
        return $stmt->execute([$id]);
    }
}