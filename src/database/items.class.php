<?php

declare(strict_types=1);

class Item
{
    public int $itemId;
    public string $name;
    public ?string $description;
    public ?int $brandId;
    public ?int $modelId;
    public float $price;
    public bool $unavailable;
    public ?int $ownerId;
    public ?int $categoryId;
    public ?int $sizeId;
    public ?int $conditionId;
    public int $stock;
    public int $ImageCount;

    public function __construct(int $itemId, ?int $brand, ?int $model, float $price, bool $unavailable, ?int $ownerId, ?int $categoryId, ?int $sizeId, ?int $conditionId, string $name, ?string $description, int $stock, int $ImageCount)
    {
        $this->itemId = $itemId;
        $this->name = $name;
        $this->description = $description;
        $this->brandId = $brand;
        $this->modelId = $model;
        $this->price = $price;
        $this->unavailable = $unavailable;
        $this->ownerId = $ownerId;
        $this->categoryId = $categoryId;
        $this->sizeId = $sizeId;
        $this->conditionId = $conditionId;
        $this->name = $name;
        $this->description = $description;
        $this->stock = $stock;
        $this->ImageCount = $ImageCount;
    }

    public function getItemId(): int
    {
        return $this->itemId;
    }

    public function getBrandId(): ?int
    {
        return $this->brandId;
    }

    public function getModelId(): ?int
    {
        return $this->modelId;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getUnavailable(): bool
    {
        return $this->unavailable;
    }

    public function getOwnerId(): ?int
    {
        return $this->ownerId;
    }

    public function getCategoryId(): ?int
    {
        return $this->categoryId;
    }

    public function getSizeId(): ?int
    {
        return $this->sizeId;
    }

    public function getConditionId(): ?int
    {
        return $this->conditionId;
    }
    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getStock(): int
    {
        return $this->stock;
    }

    public function getImageCount(): int
    {
        return $this->ImageCount;
    }

    static function addNewProduct(PDO $DB, ?int $brand, ?int $model, float $price, bool $unavailable, ?int $ownerId, ?int $categoryId, ?int $sizeId, ?int $conditionId, string $name, ?string $description, int $stock,int $ImageCount): ?int
    {
        $stmt = $DB->prepare('INSERT INTO Item (BrandId, ModelId, Price, UNAVAILABLE, OwnerId, CategoryId, SizeId, ConditionId, Name, Description, Stock, ImageCount) VALUES (:BrandId, :ModelId, :Price, :UNAVAILABLE, :OwnerId, :CategoryId, :SizeId, :ConditionId, :Name, :Description, :Stock,:ImageCount)');
        $stmt->bindParam(':BrandId', $brand, PDO::PARAM_INT);
        $stmt->bindParam(':ModelId', $model, PDO::PARAM_INT);
        $stmt->bindParam(':Price', $price);
        $stmt->bindParam(':UNAVAILABLE', $unavailable, PDO::PARAM_BOOL);
        $stmt->bindParam(':OwnerId', $ownerId, PDO::PARAM_INT);
        $stmt->bindParam(':CategoryId', $categoryId, PDO::PARAM_INT);
        $stmt->bindParam(':SizeId', $sizeId, PDO::PARAM_INT);
        $stmt->bindParam(':ConditionId', $conditionId, PDO::PARAM_INT);
        $stmt->bindParam(':Name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':Description', $description, PDO::PARAM_STR);
        $stmt->bindParam(':Stock', $stock, PDO::PARAM_INT);
        $stmt->bindParam(':ImageCount', $ImageCount, PDO::PARAM_INT);
        $stmt->execute();
        if($stmt->rowCount() === 0){
            return null;
        }
        return (int)$DB->lastInsertId();
    }

    public static function getProductsByOwnerId(PDO $DB, int $ownerId): array
    {
        $stmt = $DB->prepare('SELECT * FROM Item WHERE OwnerId = :OwnerId');
        $stmt->bindParam(':OwnerId', $ownerId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'Item');
    }

    public static function getProductById(PDO $DB, int $itemId): ?Item
    {
        $stmt = $DB->prepare('SELECT * FROM Item WHERE ItemId = ?');
        $stmt->execute(array($itemId));
        $item = $stmt->fetch();
        if(!$item) return null;
        $unavailable = (bool) $item['UNAVAILABLE'];
        return new Item($item['ItemId'], $item['BrandId'], $item['ModelId'], $item['Price'],$unavailable, $item['OwnerId'], $item['CategoryId'], $item['SizeId'], $item['ConditionId'], $item['Name'], $item['Description'], $item['Stock'], $item['ImageCount']);
    }

// this functions doesnt work
    public static function getProductsByCategoryId(PDO $DB, int $categoryId): array
    {
        $stmt = $DB->prepare('SELECT * FROM Item WHERE CategoryId = ?');
        $stmt->execute(array($categoryId));
        $items = array();
        while ($item = $stmt->fetch()){
            $unavailable = (bool) $item['UNAVAILABLE'];
            $items[] = new Item(
                $item['ItemId'],
                $item['BrandId'],
                $item['ModelId'],
                $item['Price'],
                $unavailable,
                $item['OwnerId'],
                $item['CategoryId'],
                $item['SizeId'],
                $item['ConditionId'],
                $item['Name'],
                $item['Sescription'],
                $item['Stock'],
                $item['ImageCount']
            );
        }
        return $items;
    }
// this functions doesnt work
    public static function getProductsByBrandId(PDO $DB, int $brandId): array
    {
        $stmt = $DB->prepare('SELECT * FROM Item WHERE BrandId = :BrandId');
        $stmt->bindParam(':BrandId', $brandId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'Item');
    }
// this functions doesnt work
    public static function getProductsByModelId(PDO $DB, int $modelId): array
    {
        $stmt = $DB->prepare('SELECT * FROM Item WHERE ModelId = :ModelId');
        $stmt->bindParam(':ModelId', $modelId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'Item');
    }
// this functions doesnt work
    public static function getProductsBySizeId(PDO $DB, int $sizeId): array
    {
        $stmt = $DB->prepare('SELECT * FROM Item WHERE SizeId = :SizeId');
        $stmt->bindParam(':SizeId', $sizeId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'Item');
    }
// this functions doesnt work
    public static function getProductsByConditionId(PDO $DB, string $conditionId): array
    {
        $stmt = $DB->prepare('SELECT * FROM Item WHERE ConditionId = :ConditionId');
        $stmt->bindParam(':ConditionId', $conditionId, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'Item');
    }
// this functions doesnt work
    public static function getProductsByPrice(PDO $DB, int $price): array
    {
        $stmt = $DB->prepare('SELECT * FROM Item WHERE Price = :Price');
        $stmt->bindParam(':Price', $price, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'Item');
    }
// this functions doesnt work
    public static function getProductsByAvailability(PDO $DB, bool $unavailable): array
    {
        $stmt = $DB->prepare('SELECT * FROM Item WHERE UNAVAILABLE = :UNAVAILABLE');
        $stmt->bindParam(':UNAVAILABLE', $unavailable, PDO::PARAM_BOOL);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'Item');
    }
// this functions doesnt work
    public static function getProductsByCategoryIdAndBrandId(PDO $DB, int $categoryId, int $brandId): array
    {
        $stmt = $DB->prepare('SELECT * FROM Item WHERE CategoryId = :CategoryId AND BrandId = :BrandId');
        $stmt->bindParam(':CategoryId', $categoryId, PDO::PARAM_INT);
        $stmt->bindParam(':BrandId', $brandId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'Item');
    }

    public static function UpdateStock(PDO $DB, int $itemId, int $quantity): bool
    {
        $stmt = $DB->prepare('UPDATE Item SET Stock = Stock - ? WHERE ItemId = ?');
        $stmt->execute(array($quantity, $itemId));
        return $stmt->rowCount() !== 0;
    }
    static function getPriceById(PDO $DB, int $itemId): float
    {
        $stmt = $DB->prepare('SELECT Price FROM Item WHERE ItemId = ?');
        $stmt->execute(array($itemId));
        $curr = $stmt->fetch();
        if(!$curr) return 0.0;
        return (float)$curr['Price'];
    }    
    public static function getProductsByCategoryIdWithFilters(PDO $DB, int $categoryId, ?float $price = null, ?int $conditionId = null): array 
    {
        $query = 'SELECT * FROM Item WHERE CategoryId = :CategoryId';
        $params = [':CategoryId' => $categoryId];

        if ($price !== null) {
            $query .= ' AND Price <= :Price';
            $params[':Price'] = $price;
        }

        if ($conditionId !== null) {
            $query .= ' AND ConditionId = :ConditionId';
            $params[':ConditionId'] = $conditionId;
        }

        $stmt = $DB->prepare($query);
        $stmt->execute($params);

        $items = [];
        while ($item = $stmt->fetch()) {
            $unavailable = (bool) $item['UNAVAILABLE'];
            $items[] = new Item(
                $item['ItemId'],
                $item['BrandId'],
                $item['ModelId'],
                $item['Price'],
                $unavailable,
                $item['OwnerId'],
                $item['CategoryId'],
                $item['SizeId'],
                $item['ConditionId'],
                $item['Name'],
                $item['Description'],
                $item['Stock'],
                $item['ImageCount']
            );
        }
        return $items;
    }
}