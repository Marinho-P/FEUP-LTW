<?php

class Size{
    public int $sizeId;
    public string $sizeName;

    public function __construct(int $sizeId, string $sizeName)
    {
        $this->sizeId = $sizeId;
        $this->sizeName = $sizeName;
    }

    public function getSizeId(): int
    {
        return $this->sizeId;
    }

    public function getSizeName(): string
    {
        return $this->sizeName;
    }

    public static function getSizes(PDO $DB): array
    {
        $stmt = $DB->prepare('SELECT * FROM Size');
        $stmt->execute();
        $sizes = $stmt->fetchAll();
        $sizeArray = array();
        foreach($sizes as $size){
            $sizeArray[] = new Size($size['SizeId'], $size['Name']);
        }
        return $sizeArray;
    }

    static function getSizeIdByName(PDO $db, ?string $name): ?int
    {

        if ($name == null) {
            return null;
        }
        $stmt = $db->prepare('
                SELECT SizeId
                FROM Size
                WHERE Name = ?
            ');
        $stmt->execute(array($name));
        $curr = $stmt->fetch();

        if ($curr) {
            return intval($curr['SizeId']);
        }
        return null;
    }


    static function exists($db, $sizeId): bool
    {
        $stmt = $db->prepare('
            SELECT *
            FROM Size
            WHERE SizeId = ?
            ');
        $stmt->execute(array($sizeId));
        return $stmt->fetch() != null;
    }
    
    static function getSizebyId(PDO $DB, int $sizeId): ?Size
    {
        $stmt = $DB->prepare('SELECT * FROM Size WHERE SizeId = ?');
        $stmt->execute(array($sizeId));
        $size = $stmt->fetch();
        if(!$size) return null;
        return new Size($size['SizeId'], $size['Name']);
    }
}