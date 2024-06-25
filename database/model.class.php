<?php

class Model{
    public int $modelId;
    public string $name;
    public int $brandId;

    public function __construct(int $modelId, string $name, int $brandId)
    {
        $this->modelId = $modelId;
        $this->name = $name;
        $this->brandId = $brandId;
    }

    public function getModelId(): int
    {
        return $this->modelId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getBrandId(): int
    {
        return $this->brandId;
    }

    static function getAllModelsWithBrandId(PDO $db, int $brandId): array
    {
        $stmt = $db->prepare('
                SELECT *
                FROM Model
                WHERE BrandId = ?
            ');
        $stmt->execute(array($brandId));
        $models = array();
        while ($curr = $stmt->fetch()) {
            $models[] = new Model(intval($curr['ModelId']), 
                                    $curr['Name'], 
                                    intval($curr['BrandId']));
        }
        return $models;
    }

    static function extractModelWithModelId(PDO $db, int $modelId): ?Model
    {
        $stmt = $db->prepare('
                SELECT *
                FROM Model
                WHERE ModelId = ?
            ');
        $stmt->execute(array($modelId));
        $curr = $stmt->fetch();

        if ($curr) {
            return new Model(intval($curr['ModelId']), 
                                $curr['Name'], 
                                intval($curr['BrandId']));
        }
        return null;
    }

    static function getModelIdByName(PDO $db, ?string $name): ?int
    {
        if ($name == null) {
            return null;
        }
        $stmt = $db->prepare('
                SELECT ModelId
                FROM Model
                WHERE Name = ?
            ');
        $stmt->execute(array($name));
        $curr = $stmt->fetch();

        if ($curr) {
            return intval($curr['ModelId']);
        }
        return null;
    }

    static function exists(PDO $db, int $modelId): bool
    {
        $stmt = $db->prepare('
                SELECT ModelId
                FROM Model
                WHERE ModelId = ?
            ');
        $stmt->execute(array($modelId));
        $curr = $stmt->fetch();

        return $curr ? true : false;
    }

    static function belongsToBrand(PDO $db, int $modelId, int $brandId): bool
    {
        $stmt = $db->prepare('
                SELECT ModelId
                FROM Model
                WHERE ModelId = ? AND BrandId = ?
            ');
        $stmt->execute(array($modelId, $brandId));
        $curr = $stmt->fetch();

        return $curr ? true : false;
    }

    
}