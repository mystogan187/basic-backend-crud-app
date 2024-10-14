<?php

namespace App\Todo\Domain;

interface TodoRepositoryInterface
{
    public function findAll(): array;
    public function findById(int $id): ?Todo;
    public function save(Todo $todo): void;
    public function delete(Todo $todo): void;
}