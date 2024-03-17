<?php

namespace App\Entity;

use App\Repository\ProjectRoomRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GeometryRoomRepository::class)
 */
class ProjectRoom
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity=RoomWall::class, mappedBy="projectRoom", orphanRemoval=true)
     */
    private $walls;

    /**
     * @ORM\ManyToOne(targetEntity=Project::class, inversedBy="rooms")
     * @ORM\JoinColumn(nullable=false)
     */
    private $project;

    public function __construct()
    {
        $this->walls = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, RoomWall>
     */
    public function getWalls(): Collection
    {
        return $this->walls;
    }

    public function addWall(RoomWall $wall): self
    {
        if (!$this->walls->contains($wall)) {
            $this->walls[] = $wall;
            $wall->setProjectRoom($this);
        }

        return $this;
    }

    public function removeWall(RoomWall $wall): self
    {
        if ($this->walls->removeElement($wall)) {
            // set the owning side to null (unless already changed)
            if ($wall->getProjectRoom() === $this) {
                $wall->setProjectRoom(null);
            }
        }

        return $this;
    }

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(?Project $project): self
    {
        $this->project = $project;

        return $this;
    }
}
