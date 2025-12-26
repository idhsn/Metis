<?php

require_once __DIR__ . '/../Core/BaseModel.php';

class Member extends BaseModel
{
    protected static $table = 'members';

    public static function validateEmail($email)
    {
        $EmailV = '/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';

        if (!preg_match($EmailV, $email)) {
            throw new \Exception("Email Is Invalid");
        }
        return true;
    }

    public static function emailExists($email)
    {
        $sql = "SELECT id FROM members WHERE email = ?";
        $stmt = self::$pdo->prepare($sql);
        $stmt->execute([$email]);
        return $stmt->fetch() !== false;
    }

    public static function save($data)
    {
        if (isset($data['email'])) {
            self::validateEmail($data['email']);
        }

        if (!isset($data['id']) && isset($data['email']) && self::emailExists($data['email'])) {
            throw new \Exception("Email already exists");
        }

        return parent::save($data);
    }

    public static function findAll()
    {
        $sql = "SELECT * FROM " . static::$table;
        $stmt = self::$pdo->query($sql);
        return $stmt->fetchAll();
    }
}
