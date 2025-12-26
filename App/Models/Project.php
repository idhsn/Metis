<?php

require_once __DIR__ . '/../Core/BaseModel.php';

abstract class Project extends BaseModel
{
    protected static $table = 'projects';
    
    abstract public function calculateDuration();
    
    public static function memberExists($memberId)
    {
        $sql = "SELECT id FROM members WHERE id = ?";
        $stmt = self::$pdo->prepare($sql);
        $stmt->execute([$memberId]);
        return $stmt->fetch() !== false;
    }
    
    public static function save($data)
    {
        if (isset($data['member_id']) && !self::memberExists($data['member_id'])) {
            throw new \Exception("Member ID does not exist");
        }
        
        return parent::save($data);
    }
    
    public static function delete($id)
    {
        $sql = "SELECT COUNT(*) as count FROM activities WHERE project_id = ?";
        $stmt = self::$pdo->prepare($sql);
        $stmt->execute([$id]);
        $result = $stmt->fetch();
        
        if ($result['count'] > 0) {
            throw new \Exception("Cannot delete: project has activities");
        }
        
        return parent::delete($id);
    }

    public static function findAll()
    {
        $sql = "SELECT * FROM " . static::$table;
        $stmt = self::$pdo->query($sql);
        return $stmt->fetchAll();
    }
}
