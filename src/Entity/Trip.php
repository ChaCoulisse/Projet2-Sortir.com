<?php

namespace App\Entity;

use App\Repository\TripRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=TripRepository::class)
 */
class Trip
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\Length (min=2, max=100)
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @Assert\NotBlank(message="Veuillez rentrer une date de début")
     * @Assert\GreaterThanOrEqual("today UTC")
     * @ORM\Column(type="datetimetz")
     */
    private $startHour;

    /**
     * @Assert\NotBlank(message="Veuillez rentrer une durée")
     * @ORM\Column(type="time")
     */
    private $duration;

    /**
     * @Assert\NotBlank(message="Veuillez rentrer une date de fin d'inscription")
     * @Assert\LessThan(propertyPath="startHour", message="Votre date limite d'inscription doit être inférieure à la date de début de l'évènement.")
     * @ORM\Column(type="datetimetz")
     */
    private $limitDate;

    /**
     * @Assert\NotBlank(message="Veuillez rentrer un nombre maximum de participants")
     * @Assert\GreaterThan (1)
     * @ORM\Column(type="integer")
     */
    private $limitedPlace;

    /**
     * @Assert\NotBlank(message="Veuillez rentrer une description")
     * @Assert\Length (min=10)
     * @ORM\Column(type="text")
     */
    private $infoTrip;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="organizedTrips")
     * @ORM\JoinColumn(nullable=false)
     */
    private $organizer;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="participant")
     */
    private $participant;

    /**
     * @ORM\ManyToOne(targetEntity=Campus::class, inversedBy="trips")
     * @ORM\JoinColumn(nullable=false)
     */
    private $campus;

    /**
     * @ORM\ManyToOne(targetEntity=State::class, inversedBy="trips")
     * @ORM\JoinColumn(nullable=false)
     */
    private $state;

    /**
     * @ORM\ManyToOne(targetEntity=Place::class, inversedBy="trips")
     * @ORM\JoinColumn(nullable=false)
     */
    private $place;

    /**
     * @ORM\Column(type="text")
     */
    private $cancelReason;

    public function __construct()
    {
        $this->participant = new ArrayCollection();
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

    public function getStartHour(): ?\DateTimeInterface
    {
        return $this->startHour;
    }

    public function setStartHour(\DateTimeInterface $startHour): self
    {
        $this->startHour = $startHour;

        return $this;
    }

    public function getDuration(): ?\DateTimeInterface
    {
        return $this->duration;
    }

    public function setDuration(\DateTimeInterface $duration): self
    {
        $this->duration = $duration;
        return $this;
    }

    public function getLimitDate(): ?\DateTimeInterface
    {
        return $this->limitDate;
    }

    public function setLimitDate(\DateTimeInterface $limitDate): self
    {
        $this->limitDate = $limitDate;

        return $this;
    }

    public function getLimitedPlace(): ?int
    {
        return $this->limitedPlace;
    }

    public function setLimitedPlace(int $limitedPlace): self
    {
        $this->limitedPlace = $limitedPlace;

        return $this;
    }

    public function getInfoTrip(): ?string
    {
        return $this->infoTrip;
    }

    public function setInfoTrip(string $infoTrip): self
    {
        $this->infoTrip = $infoTrip;

        return $this;
    }

    public function getOrganizer(): ?User
    {
        return $this->organizer;
    }

    public function setOrganizer(?User $organizer): self
    {
        $this->organizer = $organizer;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getParticipant(): Collection
    {
        return $this->participant;
    }

    public function addParticipant(User $participant): self
    {
        if (!$this->participant->contains($participant)) {
            $this->participant[] = $participant;
        }

        return $this;
    }

    public function removeParticipant(User $participant): self
    {
        $this->participant->removeElement($participant);

        return $this;
    }

    public function getCampus(): ?Campus
    {
        return $this->campus;
    }

    public function setCampus(?Campus $campus): self
    {
        $this->campus = $campus;

        return $this;
    }

    public function getState(): ?State
    {
        return $this->state;
    }

    public function setState(?State $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getPlace(): ?Place
    {
        return $this->place;
    }

    public function setPlace(?Place $place): self
    {
        $this->place = $place;

        return $this;
    }

    public function getCancelReason(): ?string
    {
        return $this->cancelReason;
    }

    public function setCancelReason(string $cancelReason): self
    {
        $this->cancelReason = $cancelReason;

        return $this;
    }
}
