<?php

class ShoppingCart{
    public int $userId;
    public int $productId;
    public int $quantity;

    public function __construct(int $userId, int $productId, int $quantity)
    {
        $this->userId = $userId;
        $this->productId = $productId;
        $this->quantity = $quantity;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getProductId(): int
    {
        return $this->productId;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }
    public static function getShoppingCartByUserId(PDO $DB, int $userId): ?array
    {
        $stmt = $DB->prepare('SELECT * FROM ShoppingCart WHERE UserId = ?');
        $stmt->execute(array($userId));
        $curr = $stmt->fetchAll();
        if(!$curr) return null;
        $shoppingCart = [];
        foreach($curr as $row){
            $shoppingCart[] = new ShoppingCart($row['UserId'], $row['ItemId'], $row['Quantity']);
        }
        return $shoppingCart;
    }
    public static function getItemInUserShoppingCart(PDO $DB, int $userId, int $itemId): ?ShoppingCart
    {
        $stmt = $DB->prepare('SELECT * FROM ShoppingCart WHERE UserId = ? AND ItemId = ?');
        $stmt->execute(array($userId, $itemId));
        $curr = $stmt->fetch();

        if(!$curr) return null;
        return new ShoppingCart($curr['UserId'], $curr['ItemId'], $curr['Quantity']);
    }
    public static function addItemtoShoppingCart(PDO $DB, int $userId, int $productId, int $quantity): ?int
    {
        $stmt = $DB->prepare('INSERT INTO ShoppingCart (UserId, ItemId, Quantity) VALUES (:UserId, :ProductId, :Quantity)');
        $stmt->bindParam(':UserId', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':ProductId', $productId, PDO::PARAM_INT);
        $stmt->bindParam(':Quantity', $quantity, PDO::PARAM_INT);
        $stmt->execute();
        if($stmt->rowCount() === 0){
            return null;
        }
        return (int)$DB->lastInsertId();
    }
    public static function updateQuantityInShoppingCartItem(PDO $DB, int $userId,int $ItemId ,int $quantity) : bool
    {
        $stmt = $DB->prepare('UPDATE ShoppingCart SET Quantity = ? WHERE UserId = ? AND ItemId = ?');
        $stmt->execute(array($quantity, $userId, $ItemId));
        $curr = $stmt->fetch();
        if($stmt->rowCount() === 0){
            return false;
        }
        return true;
    }
    public static function removeItemFromCart(PDO $DB, int $userId, int $productId): bool
    {
        $stmt = $DB->prepare('DELETE FROM ShoppingCart WHERE UserId = ? AND ItemId = ?');
        $stmt->execute(array($userId, $productId));
        return $stmt->rowCount() !== 0;
    }
    public static function getTotal(PDO $DB, int $userId): int
    {
        $stmt = $DB->prepare('
        SELECT SUM(Quantity * Price) as Total 
        FROM ShoppingCart 
        JOIN Item ON ShoppingCart.ItemId = Item.ItemId 
        WHERE UserId = ?');
        $stmt->execute(array($userId));
        $curr = $stmt->fetch();
        if (!$curr) return 0;
        return (int)$curr['Total'];
    }
    public static function deleteShoppingCart(PDO $DB, int $userId): bool
    {
        $stmt = $DB->prepare('DELETE FROM ShoppingCart WHERE UserId = ?');
        $stmt->execute(array($userId));
        return $stmt->rowCount() !== 0;
    }
    
}