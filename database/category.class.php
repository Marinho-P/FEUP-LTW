<?php

declare(strict_types=1);

class Category
{
    public int $categoryId;
    public string $name;
    public ?int $parentId;
    public ?string $description;

    public function __construct(int $categoryId, string $name, ?int $parentId, ?string $description)
    {
        $this->categoryId = $categoryId;
        $this->name = $name;
        $this->parentId = $parentId;
        $this->description = $description;
    }
    public function getCategoryId(): int
    {
        return $this->categoryId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getParentId(): int
    {
        return $this->parentId;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    static function extractCategoryWithcategoryId(PDO $db, int $categoryId): ?Category
    {
        $stmt = $db->prepare('
                SELECT *
                FROM Category
                WHERE CategoryId = ?
            ');
        $stmt->execute(array($categoryId));
        $curr = $stmt->fetch();

        if ($curr) {
            return new Category(intval($curr['CategoryId']), 
                                    $curr['Name'], 
                                    intval($curr['ParentId']), 
                                    $curr['Description']);
        }
        return null;
    }

    static function extractCategoryWithName(PDO $db, string $name): ?Category
    {
        $stmt = $db->prepare('
                SELECT *
                FROM Category
                WHERE Name = ?
            ');
        $stmt->execute(array($name));
        $curr = $stmt->fetch();

        if ($curr) {
            return new Category(intval($curr['CategoryId']), 
                                    $curr['Name'], 
                                    intval($curr['ParentId']), 
                                    $curr['Description']);
        }
        return null;
    }

    static function existsCategory(PDO $db, string $name): bool
    {
        $stmt = $db->prepare('
            SELECT *
            FROM Category
            WHERE Name = ?
            ');
        $stmt->execute(array($name));

        $Category = $stmt->fetch();

        return $Category ? true : false;
    }

    static function createCategory(PDO $db, string $name): ?Category
    {
        if (Category::existsCategory($db, $name)) {
            return null;
        }

        $stmt = $db->prepare('
            INSERT INTO Category (Name) VALUES (?)
            ');
        $stmt->execute(array("$name"));
        return Category::extractCategoryWithName($db, $name);
    }

    public function deleteCategory(PDO $db)
    {
        $stmt = $db->prepare('
            DELETE FROM Category WHERE CategoryId = ?
            ');
        $stmt->execute(array($this->categoryId));
    }

    static function getAllCategoryNames(PDO $db): array
    {
        $stmt = $db->prepare("SELECT name FROM Category");
        $stmt->execute();
        $raw = $stmt->fetchAll();
        $Categorys = [];
        foreach ($raw as $array) {
            array_push($Categorys, $array['Name']);
        }
        sort($Categorys);
        return $Categorys;
    }

    static public function getAllCategories(PDO $db): array
    {
        $stmt = $db->prepare('
                SELECT *
                FROM Category
            ');
        $stmt->execute();
        $curr = $stmt->fetchAll();

        $Categorys = [];
        foreach ($curr as $curr) {
            array_push($Categorys, new Category(intval($curr['CategoryId']), 
                                                $curr['Name'], 
                                                intval($curr['ParentId']), 
                                                $curr['Description']));
        }
        sort($Categorys);
        return $Categorys;
    }

    static function getCategoryIdByName(PDO $db, ?string $name): ?int
    {
        if ($name == null) {
            return null;
        }
        $stmt = $db->prepare('
                SELECT CategoryId
                FROM Category
                WHERE Name = ?
            ');
        $stmt->execute(array($name));
        $curr = $stmt->fetch();

        if ($curr) {
            return intval($curr['CategoryId']);
        }
        return null;
    }

    static function exists($db, $categoryId): bool
    {
        $stmt = $db->prepare('
            SELECT *
            FROM Category
            WHERE CategoryId = ?
            ');
        $stmt->execute(array($categoryId));
        $Category = $stmt->fetch();
        return $Category ? true : false;
    }

}