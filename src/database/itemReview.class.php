<?php

class ItemReview{
    public int $reviewId;
    public int $reviewerId;
    public int $starsNumber;
    public string $description;

    public function __construct(int $reviewId, int $reviewerId, int $starsNumber, string $description)
    {
        $this->reviewId = $reviewId;
        $this->reviewerId = $reviewerId;
        $this->starsNumber = $starsNumber;
        $this->description = $description;
    }

    public function getReviewId(): int
    {
        return $this->reviewId;
    }

    public function getReviewerId(): int
    {
        return $this->reviewerId;
    }

    public function getStarsNumber(): int
    {
        return $this->starsNumber;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    static function extractReviewWithReviewId(PDO $db, int $reviewId): ?ItemReview
    {
        $stmt = $db->prepare('
                SELECT *
                FROM ItemReview
                WHERE ReviewId = ?
            ');
        $stmt->execute(array($reviewId));
        $curr = $stmt->fetch();

        if ($curr) {
            return new ItemReview(intval($curr['ReviewId']), 
                                    intval($curr['ReviewerId']), 
                                    intval($curr['StarsNumber']), 
                                    $curr['Description']);
        }
        return null;
    }

    static function getCollumnStarsNumberWithReviewedId(PDO $db, int $reviewId): array
    {
        $stmt = $db->prepare('
                SELECT StarsNumber
                FROM ItemReview
                WHERE ReviewId = ?
            ');
        $stmt->execute(array($reviewId));
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
    public static function getReviewsByReviewedId(PDO $DB, int $reviewedId): array
    {
        $stmt = $DB->prepare('SELECT * FROM ItemReview WHERE ReviewedId = ?');
        $stmt->execute([$reviewedId]);
        
        $reviews = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $review = new ItemReview($row['ReviewerId'], $row['ReviewedId'], $row['StarsNumber'], $row['Description']);
            $reviews[] = $review;
        }
        
        return $reviews;
    }



}