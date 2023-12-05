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

    public function getDeletedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setDeletedAt(?DateTimeInterface $timestamp): self
    {
        $this->createdAt = $timestamp;
        return $this;
    }

    #[ORM\PreRemove]
    public function setDeletedAtAutomatically()
    {
        $this->deleted = true;
        if ($this->getDeletedAt() === null) {
            $this->setDeletedAt(new \DateTime());
        }
    }
}
