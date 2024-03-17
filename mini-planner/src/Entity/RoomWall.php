<?php

namespace App\Entity;

use App\Repository\RoomWallRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RoomWallRepository::class)
 */
class RoomWall
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity=WallPoint::class, mappedBy="roomWall", orphanRemoval=true)
     */
    private $points;

    /**
     * @ORM\ManyToOne(targetEntity=ProjectRoom::class, inversedBy="walls")
     * @ORM\JoinColumn(nullable=false)
     */
    private $projectRoom;

    public function __construct()
    {
        $this->points = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, WallPoint>
     */
    public function getPoints(): Collection
    {
        return $this->points;
    }

    public function addPoint(WallPoint $point): self
    {
        if (!$this->points->contains($point)) {
            $this->points[] = $point;
            $point->setRoomWall($this);
        }

        return $this;
    }

    public function removePoint(WallPoint $point): self
    {
        if ($this->points->removeElement($point)) {
            // set the owning side to null (unless already changed)
            if ($point->getRoomWall() === $this) {
                $point->setRoomWall(null);
            }
        }

        return $this;
    }

    public function getProjectRoom(): ?ProjectRoom
    {
        return $this->projectRoom;
    }

    public function setProjectRoom(?ProjectRoom $projectRoom): self
    {
        $this->projectRoom = $projectRoom;

        return $this;
    }
}
