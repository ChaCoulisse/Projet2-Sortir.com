<?php

namespace App\Entity;

use App\Repository\PlaceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=PlaceRepository::class)
 */
class Place
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank(message="Veuillez rentrer un nom de ville")
     * @ORM\Column(type="string", length=70)
     */
    private $name;

    /**
     * @Assert\NotBlank(message="Veuillez rentrer un nom de rue")
     * @ORM\Column(type="string", length=100)
     */
    private $street;

    /**
     * @Assert\NotBlank(message="Veuillez rentrer une latitude")
     * @ORM\Column(type="float")
     */
    private $latitude;

    /**
     * @Assert\NotBlank(message="Veuillez rentrer une longitude")
     * @ORM\Column(type="float")
     */
    private $longitude;

    /**
     * @ORM\ManyToOne(targetEntity=City::class, inversedBy="places")
     * @ORM\JoinColumn(nullable=false)
     */
    private $city;

    /**
     * @ORM\OneToMany(targetEntity=Trip::class, mappedBy="place")
     */
    private $trips;

    public function __construct()
    {
        $this->trips = new ArrayCollection();
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

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(string $street): self
    {
        $this->street = $street;

        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(float $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(float $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getCity(): ?City
    {
        return $this->city;
    }

    public function setCity(?City $city): self
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return Collection|Trip[]
     */
    public function getTrips(): Collection
    {
        return $this->trips;
    }

    public function addTrip(Trip $trip): self
    {
        if (!$this->trips->contains($trip)) {
            $this->trips[] = $trip;
            $trip->setPlace($this);
        }

        return $this;
    }

    public function removeTrip(Trip $trip): self
    {
        if ($this->trips->removeElement($trip)) {
            // set the owning side to null (unless already changed)
            if ($trip->getPlace() === $this) {
                $trip->setPlace(null);
            }
        }

        return $this;
    }
}
