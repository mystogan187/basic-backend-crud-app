<?php

namespace App\Todo\Domain;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'todo')]
class Todo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    private string $titulo;

    #[ORM\Column(type: 'boolean')]
    private bool $completada = false;

    #[ORM\Column(type: 'datetime_immutable')]
    private DateTimeImmutable $fechaCreacion;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?DateTimeImmutable $fechaCompletada = null;

    public function __construct(string $titulo, bool $completada = false)
    {
        $this->titulo = $titulo;
        $this->completada = $completada;
        $this->fechaCreacion = new DateTimeImmutable();
        $this->fechaCompletada = $completada ? new DateTimeImmutable() : null;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getTitulo(): string
    {
        return $this->titulo;
    }

    public function setTitulo(string $titulo): self
    {
        $this->titulo = $titulo;
        return $this;
    }

    public function isCompletada(): bool
    {
        return $this->completada;
    }


    public function setCompletada(bool $completada): self
    {
        $this->completada = $completada;
        if ($completada && $this->fechaCompletada === null) {
            $this->fechaCompletada = new DateTimeImmutable();
        } elseif (!$completada) {
            $this->fechaCompletada = null;
        }
        return $this;
    }

    public function getFechaCreacion(): DateTimeImmutable
    {
        return $this->fechaCreacion;
    }

    public function getFechaCompletada(): ?DateTimeImmutable
    {
        return $this->fechaCompletada;
    }
}