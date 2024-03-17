<?php

namespace App\Service\ProjectsImporter\DTO;

class ParsedPointDTO
{
    private float $x;

    private float $y;

    public function __construct(float $x, float $y)
    {
        $this->x = $x;
        $this->y = $y;
    }

    public function getX()
    {
        return $this->x;
    }

    public function getY()
    {
        return $this->y;
    }
}
