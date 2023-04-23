<?php

namespace App\Entity;

use App\Repository\TodoRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ApiResource
 * @ORM\Entity(repositoryClass=TodoRepository::class)
 */
class Todo
{

    public function __construct(string $text, bool $executed)
    {
        $this->text = $text;
        $this->executed = $executed;
    }

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=1000)
     */
    private $text;

    /**
     * @ORM\Column(type="boolean")
     */
    private $executed;

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
