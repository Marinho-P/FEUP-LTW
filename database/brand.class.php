<?php

class Brand{
    public int $brandId;
    public string $name;

    public function __construct(int $brandId, string $name)
    {
        $this->brandId = $brandId;
        $this->name = $name;
    }

    public function getBrandId(): int
    {
        return $this->brandId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    static function getAllBrands(PDO $db): array
    {
        $stmt = $db->prepare('
                SELECT *
                FROM Brand
            ');
        $stmt->execute();
        $brands = array();
        while ($curr = $stmt->fetch()) {
            $brands[] = new Brand(intval($curr['BrandId']), 
                                    $curr['Name']);
        }
        return $brands;
    }

    
    static function extractBrandWithBrandId(PDO $db, int $brandId): ?Brand
    {
        $stmt = $db->prepare('
                SELECT *
                FROM Brand
                WHERE BrandId = ?
            ');
        $stmt->execute(array($brandId));
        $curr = $stmt->fetch();

        if ($curr) {
            return new Brand(intval($curr['BrandId']), 
                                $curr['Name']);
        }
        return null;
    }

    static function getBrandIdByName(PDO $db, ?string $name): ?int
    {
        if ($name == null) {
            return null;
        }
        $stmt = $db->prepare('
                SELECT BrandId
                FROM Brand
                WHERE Name = ?
            ');
        $stmt->execute(array($name));
        $curr = $stmt->fetch();

        if ($curr) {
            return intval($curr['BrandId']);
        }
        return null;
    }

    static function exists(PDO $DB,int $id): bool {
        $stmt = $DB->prepare('SELECT * FROM Brand WHERE BrandId = :BrandId');
        $stmt->bindParam(':BrandId', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch() !== false;
    }
}