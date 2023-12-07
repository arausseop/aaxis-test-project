<?php

namespace App\Entity\Traits;

use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

trait DeleteTimestampsTrait
{
    #[ORM\Column(name: 'deleted', type: 'boolean', nullable: false)]
    private $deleted = false;

    #[ORM\Column(name: 'deleted_at', type: 'datetime', nullable: true)]
    private $deletedAt;

    public function isDeleted(): ?bool
    {
        return $this->deleted;
    }

    public function setDeleted(bool $deleted): self
    {
        $this->deleted = $deleted;

        return $this;
    }

    public function getDeletedAt(): ?DateTimeInterface
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(?DateTimeInterface $timestamp): self
    {
        $this->deletedAt = $timestamp;
        return $this;
    }

    #[ORM\PreRemove]
    public function setDeletedAtAutomatically()
    {
        if ($this->getDeletedAt() === null) {
            $this->deleted = true;
            $this->setDeletedAt(new \DateTimeImmutable());
        }
    }
}
