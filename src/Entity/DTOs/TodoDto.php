<?php

namespace App\Entity\DTOs;

class TodoDto
{
    public function __construct(int $id, string $text, bool $executed) {
        $this->id = $id;
        $this->text = $text;
        $this->executed = $executed;
    }
    private ?int $id;
    private ?string $text;
    private ?bool $executed;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function isExecuted(): ?bool
    {
        return $this->executed;
    }

    public function setExecuted(bool $executed): self
    {
        $this->executed = $executed;

        return $this;
    }
}