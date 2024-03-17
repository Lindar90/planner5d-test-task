<?php

namespace App\Entity;

use App\Repository\WallPointRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=WallPointRepository::class)
 */
class WallPoint
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     */
    private $pointX;

    /**
     * @ORM\Column(type="float")
     */
    private $pointY;

    /**
     * @ORM\ManyToOne(targetEntity=RoomWall::class, inversedBy="points")
     * @ORM\JoinColumn(nullable=false)
     */
    private $roomWall;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPointX(): ?float
    {
        return $this->pointX;
    }

    public function setPointX(float $pointX): self
    {
        $this->pointX = $pointX;

        return $this;
    }

    public function getPointY(): ?float
    {
        return $this->pointY;
    }

    public function setPointY(float $pointY): self
    {
        $this->pointY = $pointY;

        return $this;
    }

    public function getRoomWall(): ?RoomWall
    {
        return $this->roomWall;
    }

    public function setRoomWall(?RoomWall $roomWall): self
    {
        $this->roomWall = $roomWall;

        return $this;
    }
}
