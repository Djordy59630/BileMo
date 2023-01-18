<?php

namespace App\Entity;

use App\Repository\PhoneRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PhoneRepository::class)]
class Phone
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 6, scale: 2)]
    private ?string $price = null;

    #[ORM\Column]
    private ?int $storage = null;

    #[ORM\Column]
    private ?int $screenSize = null;

    #[ORM\Column]
    private ?int $pictureResolution = null;

    #[ORM\Column(length: 255)]
    private ?string $simCard = null;

    #[ORM\Column]
    private ?int $weight = null;

    #[ORM\Column]
    private ?bool $refurbished = null;

    #[ORM\Column]
    private ?int $guaranteed = null;

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

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getStorage(): ?int
    {
        return $this->storage;
    }

    public function setStorage(int $storage): self
    {
        $this->storage = $storage;

        return $this;
    }

    public function getScreenSize(): ?int
    {
        return $this->screenSize;
    }

    public function setScreenSize(int $screenSize): self
    {
        $this->screenSize = $screenSize;

        return $this;
    }

    public function getPictureResolution(): ?int
    {
        return $this->pictureResolution;
    }

    public function setPictureResolution(int $pictureResolution): self
    {
        $this->pictureResolution = $pictureResolution;

        return $this;
    }

    public function getSimCard(): ?string
    {
        return $this->simCard;
    }

    public function setSimCard(string $simCard): self
    {
        $this->simCard = $simCard;

        return $this;
    }

    public function getWeight(): ?int
    {
        return $this->weight;
    }

    public function setWeight(int $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    public function isRefurbished(): ?bool
    {
        return $this->refurbished;
    }

    public function setRefurbished(bool $refurbished): self
    {
        $this->refurbished = $refurbished;

        return $this;
    }

    public function getGuaranteed(): ?int
    {
        return $this->guaranteed;
    }

    public function setGuaranteed(int $guaranteed): self
    {
        $this->guaranteed = $guaranteed;

        return $this;
    }
}
