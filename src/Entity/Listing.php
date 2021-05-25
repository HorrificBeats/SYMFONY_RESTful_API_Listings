<?php

namespace App\Entity;

use App\Repository\ListingRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
//API Platform
use ApiPlatform\Core\Annotation\ApiResource;
//Serialization Groups
use Symfony\Component\Serializer\Annotation\Groups;
//Humanize DateTime
use Carbon\Carbon;
//Api Filters
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\RangeFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
//Validation
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ListingRepository::class)
 * @ApiResource(
 *  collectionOperations = {"get","post"},
 *  itemOperations = {"get", "put", "delete"},
 *  normalizationContext = {"groups" = {"listing:read"}},
 *  denormalizationContext = {"groups" = {"listing:write"}},
 *  attributes = {
 *    "pagination_items_per_page"= 4
 *  }
 * )
 * @ApiFilter(BooleanFilter::class, properties = {"isPublished"})
 * @ApiFilter(SearchFilter::class, properties = {"title" : "partial", "description" : "partial"})
 * @ApiFilter(RangeFilter::class, properties = {"price"})
 * @ApiFilter(OrderFilter::class, properties={"title": "ASC", "createdAt": "DESC"})
 */
class Listing
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"listing:read", "listing:write"})
     * @Assert\NotBlank()
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"listing:read", "listing:write"})
     * @Assert\NotBlank()
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=50)
     * @Groups({"listing:read", "listing:write"})
     * @Assert\NotBlank()
     * @Assert\GreaterThan(0)
     */
    private $price;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="listings")
     * @Groups({"listing:read", "listing:write"})
     */
    private $category;

    /**
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="create")
     * @Groups({"listing:read"})
     */
    private $createdAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isPublished = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getIsPublished(): ?bool
    {
        return $this->isPublished;
    }

    public function setIsPublished(bool $isPublished): self
    {
        $this->isPublished = $isPublished;

        return $this;
    }

    /**
     * Adds "1 day ago" instead of normal dateTime
     * @Groups({"listing:read"})
     * @return string
     */
    public function getCreatedAgo(): string
    {
        return Carbon::instance($this->getCreatedAt())->diffForHumans();
    }
}
