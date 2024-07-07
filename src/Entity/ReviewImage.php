<?php

namespace App\Entity;


use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

#[Entity]
#[Table('review_images')]
class ReviewImage
{
    #[Id]
    #[GeneratedValue('AUTO')]
    #[Column]
    private int $id;

    #[Column('review_id')]
    #[JoinColumn(referencedColumnName: 'id')]
    private int $reviewId;

    #[Column]
    private string $image;

    #[Column('image_sort')]
    private int $imageSort;

    #[ManyToOne(targetEntity: Review::class, cascade: ['persist'], inversedBy: 'images')]
    #[JoinColumn(referencedColumnName: 'id')]
    private Review $review;

    public function getId(): int
    {
        return $this->id;
    }

    public function getReviewId(): int
    {
        return $this->reviewId;
    }

    public function setReviewId(int $reviewId): ReviewImage
    {
        $this->reviewId = $reviewId;
        return $this;
    }

    public function getImage(): string
    {
        return $this->image;
    }

    public function setImage(string $image): ReviewImage
    {
        $this->image = $image;
        return $this;
    }

    public function getImageSort(): int
    {
        return $this->imageSort;
    }

    public function setImageSort(int $imageSort): ReviewImage
    {
        $this->imageSort = $imageSort;
        return $this;
    }

    public function getReview(): Review
    {
        return $this->review;
    }

    public function setReview(Review $review): ReviewImage
    {
        $this->review = $review;
        return $this;
    }
}
