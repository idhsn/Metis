<?php

require_once __DIR__ . '/../Core/BaseModel.php';

class Activity extends BaseModel
{
    protected static $table = 'activities';
    
    public static function projectExists(int $projectId): bool
    {
        $sql = "SELECT id FROM projects WHERE id = ?";
        $stmt = self::$pdo->prepare($sql);
        $stmt->execute([$projectId]);
        return $stmt->fetch() !== false;
    }
    
    public static function save($data)
    {
        if (isset($data['project_id']) && !self::projectExists($data['project_id'])) {
            throw new \Exception("Project ID does not exist");
        }
        
        return parent::save($data);
    }
    
    public static function findByProject(int $projectId): array
    {
        $sql = "SELECT * FROM activities WHERE project_id = ? ORDER BY created_at DESC";
        $stmt = self::$pdo->prepare($sql);
        $stmt->execute([$projectId]);
        return $stmt->fetchAll();
    }
}
