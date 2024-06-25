<?php

class Wishlist{
    public int $userId;
    public int $productId;


    public function __construct(int $userId, int $productId)
    {
        $this->userId = $userId;
        $this->productId = $productId;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getProductId(): int
    {
        return $this->productId;
    }

    
    // might be wrong / not tested
    public static function getWishlistByUserId(PDO $DB, int $userId): ?array
    {
        $stmt = $DB->prepare('SELECT * FROM Wishlist WHERE UserId = ?');
        $stmt->execute(array($userId));
        $curr = $stmt->fetchAll();
        if(!$curr) return null;
        $wishlist = [];
        foreach($curr as $row){
            $wishlist[] = new Wishlist($row['UserId'], $row['ItemId']);
        }
        return $wishlist;
    }

    public static function getItemInUserWishlist(PDO $DB, int $userId,int $ItemId): ?Wishlist
    {
        $stmt = $DB->prepare('SELECT * FROM Wishlist WHERE ItemId = ? AND UserId = ?');
        $stmt->execute(array($ItemId, $userId));
        $curr = $stmt->fetch();
        if(!$curr) return null;
        return new Wishlist($curr['UserId'], $curr['ItemId']);
    }
 
    public static function addItemtoWishlist(PDO $DB, int $userId, int $productId): ?int
    {
        $stmt = $DB->prepare('INSERT INTO Wishlist (UserId, ItemId) VALUES (:UserId, :ProductId)');
        $stmt->bindParam(':UserId', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':ProductId', $productId, PDO::PARAM_INT);
        $stmt->execute();
        if($stmt->rowCount() === 0){
            return null;
        }
        return (int)$DB->lastInsertId();
    }
    public static function removeItemFromWishlist(PDO $DB, int $userId, int $productId): bool
    {
        $stmt = $DB->prepare('DELETE FROM Wishlist WHERE UserId = ? AND ItemId = ?');
        $stmt->execute(array($userId, $productId));
        return $stmt->rowCount() !== 0;
    }

}