<?php
declare(strict_types=1);

class ShippingOrder {
    private $orderId;
    private $ownerId;
    private $buyerId;
    private $buyerAddress;
    private $buyerCity;
    private $buyerPostalCode;
    private $buyerCountry;
    
    public function __construct($orderId, $ownerId, $buyerId, $buyerAddress, $buyerCity, $buyerPostalCode, $buyerCountry) {
        $this->orderId = $orderId;
        $this->ownerId = $ownerId;
        $this->buyerId = $buyerId;
        $this->buyerAddress = $buyerAddress;
        $this->buyerCity = $buyerCity;
        $this->buyerPostalCode = $buyerPostalCode;
        $this->buyerCountry = $buyerCountry;
    }
    
    // Getters for each property
    
    public function getOrderId() {
        return $this->orderId;
    }
    
    public function getOwnerId() {
        return $this->ownerId;
    }
    
    public function getBuyerId() {
        return $this->buyerId;
    }
    
    public function getBuyerAddress() {
        return $this->buyerAddress;
    }
    
    public function getBuyerCity() {
        return $this->buyerCity;
    }
    
    public function getBuyerPostalCode() {
        return $this->buyerPostalCode;
    }
    
    public function getBuyerCountry() {
        return $this->buyerCountry;
    }
    public static function createShippingOrder($db, $ownerId, $buyerId, $buyerAddress, $buyerCity, $buyerPostalCode, $buyerCountry){
        $stmt = $db->prepare('INSERT INTO ShippingOrder (OwnerId, BuyerId, BuyerAddress, BuyerCity, BuyerPostalCode, BuyerCountry) VALUES (?, ?, ?, ?, ?, ?)');
        $stmt->execute([$ownerId, $buyerId, $buyerAddress, $buyerCity, $buyerPostalCode, $buyerCountry]);
        if($stmt->rowCount() === 0){
            return null;
        }
        return $db->lastInsertId();
    }
    public static function getOrderbyBuyerIdAndOwnerId($db, $buyerId, $ownerId){
        $stmt = $db->prepare('SELECT * FROM ShippingOrder WHERE BuyerId = ? AND OwnerId = ?');
        $stmt->execute([$buyerId, $ownerId]);
        if($stmt->rowCount() === 0){
            return null;
        }
        return $stmt->fetch();
    }
}
?>
