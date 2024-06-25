<?php

class SellerProductsOffered{
    public int $chatId;
    public int $productId;
    public int $quantity;

    public function __construct(int $chatId, int $productId, int $quantity)
    {
        $this->chatId = $chatId;
        $this->productId = $productId;
        $this->quantity = $quantity;
    }

    public function getChatId(): int
    {
        return $this->chatId;
    }

    public function getProductId(): int
    {
        return $this->productId;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public static function getSellerProductsOfferedByChatId(PDO $DB, int $chatId): array
    {
        $stmt = $DB->prepare('SELECT * FROM SellerProductsOffered WHERE ChatId = :ChatId');
        $stmt->bindParam(':ChatId', $chatId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'SellerProductsOffered');
    }


}