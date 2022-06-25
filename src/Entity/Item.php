<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ItemRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    collectionOperations: ['get', 'post'],
    itemOperations: ['get', 'delete', 'put'],
    attributes: ['pagination_items_per_page' => 10],
    normalizationContext: ['groups' => ['item.read']],
)]
#[ORM\Entity(repositoryClass: ItemRepository::class)]
class Item
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    /** Item ID */
    private $id;

    /** Item Name */
    #[ORM\Column(type: 'string', length: 255)]
    #[
        Assert\NotBlank,
        Groups(['item.read'])
    ]
    private $name;

    #[ORM\OneToMany(mappedBy: 'item', targetEntity: Property::class, orphanRemoval: true)]
    #[Groups(['item.read'])]
    private $properties;

    public function __construct()
    {
        $this->properties = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Property>
     */
    public function getProperties(): Collection
    {
        return $this->properties;
    }

    public function addProperty(Property $property): self
    {
        if (!$this->properties->contains($property)) {
            $this->properties[] = $property;
            $property->setItem($this);
        }

        return $this;
    }

    public function removeProperty(Property $property): self
    {
        if ($this->properties->removeElement($property)) {
            // set the owning side to null (unless already changed)
            if ($property->getItem() === $this) {
                $property->setItem(null);
            }
        }

        return $this;
    }
}
