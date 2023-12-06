<?php

namespace App\Form\Product\DTO;

use App\Entity\Product;
use DateTimeInterface;

class ProductDto
{
    public ?int $id = null;
    public ?string $sku = "";
    public ?string $productName = "";
    public ?string $description = "";
    public ?bool $deleted = null;
    public ?DateTimeInterface $createdAt = null;
    public ?DateTimeInterface $updatedAt = null;
    public ?DateTimeInterface $deletedAt = null;

    public function __construct()
    {
    }

    public static function createEmpty(): self
    {
        return new self();
    }

    public static function createFromProduct(Product $product): self
    {

        $dto = new self();

        $dto->id = $product->getId();
        $dto->sku = $product->getSku();
        $dto->productName = $product->getProductName();
        $dto->description = $product->getDescription();
        $dto->deleted = $product->isDeleted();
        $dto->createdAt = $product->getCreatedAt();
        $dto->updatedAt = $product->getUpdatedAt();
        $dto->deletedAt = $product->getDeletedAt();

        return $dto;
    }


    public function getId()
    {
        return $this->id;
    }

    public function getSku()
    {
        return $this->sku;
    }

    public function getProductName()
    {
        return $this->productName;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function isDeleted()
    {
        return $this->eleted;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    public function getDeletedAt()
    {
        return $this->deletedAt;
    }
}
