<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\PropertyRepository;
use Doctrine\ORM\Mapping as ORM;

#[ApiResource()]
#[ORM\Entity(repositoryClass: PropertyRepository::class)]
class Property
{
    /** Property ID */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    /** Property Value */
    #[ORM\Column(type: 'string', length: 255)]
    private $value;

    /** Item */
    #[ORM\ManyToOne(targetEntity: Item::class, inversedBy: 'properties')]
    #[ORM\JoinColumn(nullable: false)]
    private $item;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getItem(): ?Item
    {
        return $this->item;
    }

    public function setItem(?Item $item): self
    {
        $this->item = $item;

        return $this;
    }
}
