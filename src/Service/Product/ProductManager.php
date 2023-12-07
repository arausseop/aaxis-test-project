<?php

namespace App\Service\Product;

use App\Entity\Product;
use App\Model\Exception\DatabaseException;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;

class ProductManager
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly ProductRepository $productRepository,
    ) {
    }

    public function find(int $id): ?Product
    {
        try {
            return $this->productRepository->find($id);
        } catch (\Exception $e) {
            throw DatabaseException::createFromMessage($e->getMessage());
        }
    }

    public function findAll(): ?array
    {
        try {
            return $this->productRepository->findAll();
        } catch (\Exception $e) {
            throw DatabaseException::createFromMessage($e->getMessage());
        }
    }

    public function getRepository(): ProductRepository
    {
        return $this->productRepository;
    }

    public function create(): Product
    {
        $product = new Product();
        return $product;
    }

    public function persist(Product $product): Product
    {
        $this->em->persist($product);
        return $product;
    }

    public function save(Product $product): Product
    {
        try {
            $this->em->persist($product);
            $this->em->flush();
        } catch (\Exception $e) {
            throw DatabaseException::createFromMessage($e->getMessage());
        }
        return $product;
    }

    public function reload(Product $product): Product
    {
        $this->em->refresh($product);
        return $product;
    }

    public function delete(Product $product): void
    {
        try {
            $product->setDeletedAtAutomatically();
            $this->em->persist($product);
            $this->em->flush();
        } catch (\Exception $e) {
            throw DatabaseException::createFromMessage($e->getMessage());
        }
    }

    public function remove(Product $product): void
    {
        try {
            $this->em->remove($product);
            $this->em->flush();
        } catch (\Exception $e) {
            throw DatabaseException::createFromMessage($e->getMessage());
        }
    }

    public function getEntityReference($entityId)
    {
        return $this->em->getReference(Product::class, $entityId);
    }
}
