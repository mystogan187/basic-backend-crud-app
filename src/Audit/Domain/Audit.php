<?php

namespace App\Audit\Domain;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'audit')]
class Audit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'integer')]
    private int $todoId;

    #[ORM\Column(type: 'string', length: 255)]
    private string $accion;

    #[ORM\Column(type: 'string', length: 255)]
    private string $descripcion;

    #[ORM\Column(type: 'datetime_immutable')]
    private DateTimeImmutable $fechaAccion;

    public function __construct(int $todoId, string $accion, string $descripcion)
    {
        $this->todoId = $todoId;
        $this->accion = $accion;
        $this->descripcion = $descripcion;
        $this->fechaAccion = new DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTodoId(): int
    {
        return $this->todoId;
    }

    public function getAccion(): string
    {
        return $this->accion;
    }

    public function getDescripcion(): string
    {
        return $this->descripcion;
    }

    public function getFechaAccion(): DateTimeImmutable
    {
        return $this->fechaAccion;
    }
}