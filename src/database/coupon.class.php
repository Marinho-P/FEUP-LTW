<?php


class Coupon
{
    public int $couponId;
    public int $sellerId;
    public int $buyerId;
    public float $discount;
    public string $expiryDate;
    public string $code;
    public array $items;

    public function __construct(int $couponId, int $sellerId, int $buyerId, float $discount, string $expiryDate, string $code, string $itemsJson)
    {
        $this->couponId = $couponId;
        $this->sellerId = $sellerId;
        $this->buyerId = $buyerId;
        $this->discount = $discount;
        $this->expiryDate = $expiryDate;
        $this->code = $code;
        $this->items = json_decode($itemsJson, true); // Decode JSON to array
    }

    public function getItemsJson(): string
    {
        return json_encode($this->items); // Encode array to JSON
    }

    public function getCouponId(): int
    {
        return $this->couponId;
    }

    public function getSellerId(): int
    {
        return $this->sellerId;
    }

    public function getBuyerId(): int
    {
        return $this->buyerId;
    }

    public function getDiscount(): float
    {
        return $this->discount;
    }

    public function getExpiryDate(): string
    {
        return $this->expiryDate;
    }

    public function getCode(): string
    {
        return $this->code;
    }


    static function addCouponToDB(PDO $db, int $sellerId, int $buyerId, float $discount, string $itemsJson): ?Coupon
    {
        date_default_timezone_set('Europe/Lisbon');

        $localTimeZone = new DateTimeZone('Europe/Lisbon');

        $expiryDate = new DateTime('now', $localTimeZone);

        // Add one month to the current date
        $expiryDate->modify('+30 minutes');

        $expiryDateStr = $expiryDate->format('Y-m-d H:i:s');

        function generateUniqueCode(PDO $db)
        {
            do {
                $randomCode = substr(md5(uniqid(rand(), true)), 0, 10);
                $stmt = $db->prepare('SELECT COUNT(*) FROM Coupon WHERE Code = ?');
                $stmt->execute([$randomCode]);
                $count = $stmt->fetchColumn();
            } while ($count > 0);
            return $randomCode;
        }

        $randomCode = generateUniqueCode($db);

        $stmt = $db->prepare('
            INSERT INTO Coupon (SellerId, BuyerId, Discount, ExpiryDate, Code, Items)
            VALUES (?, ?, ?, ?, ?, ?)
        ');
        if ($stmt->execute([$sellerId, $buyerId, $discount, $expiryDateStr, $randomCode, $itemsJson])) {
            $couponId = $db->lastInsertId();
            return self::getCouponByID($db, $couponId);
        }
        return null;
    }

    static function getCouponByID(PDO $db, int $couponId): ?Coupon
    {
        $stmt = $db->prepare('SELECT * FROM Coupon WHERE CouponId = ?');
        $stmt->execute([$couponId]);
        $coupon = $stmt->fetch();
        if (!$coupon) return null;
        return new Coupon(
            $coupon['CouponId'],
            $coupon['SellerId'],
            $coupon['BuyerId'],
            $coupon['Discount'],
            $coupon['ExpiryDate'],
            $coupon['Code'],
            $coupon['Items']
        );
    }
    static function deleteExpiredCoupons(PDO $db)
    {
        $localTimeZone = new DateTimeZone('Europe/Lisbon');
        $now = new DateTime('now', $localTimeZone);
        $nowStr = $now->format('Y-m-d H:i:s');
        $stmt = $db->prepare('DELETE FROM Coupon WHERE ExpiryDate < ?');
        $stmt->execute([$nowStr]);
    }
    static function checkIfCouponExists(PDO $db, string $code, int $ownerId, int $buyerId): ?Coupon
    {
        date_default_timezone_set('Europe/Lisbon');

        $stmt = $db->prepare('SELECT * FROM Coupon WHERE Code = ? AND SellerId = ? AND BuyerId = ?');
        $stmt->execute([$code, $ownerId, $buyerId]);
        $coupon = $stmt->fetch();

        if (!$coupon) return null;

        $now = new DateTime();

        $exp = new DateTime($coupon['ExpiryDate']);

        if ($exp < $now) {
            // Coupon has expired
            return null;
        }
        return new Coupon(
            $coupon['CouponId'],
            $coupon['SellerId'],
            $coupon['BuyerId'],
            $coupon['Discount'],
            $coupon['ExpiryDate'],
            $coupon['Code'],
            $coupon['Items']
        );
    }
    static function deleteCouponIfExists(PDO $db, string $code): bool
    {
        $stmt = $db->prepare('DELETE FROM Coupon WHERE Code = ?');
        $stmt->execute([$code]);
        return $stmt->rowCount() > 0;
    }
}
