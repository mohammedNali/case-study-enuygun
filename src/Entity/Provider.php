<?php

namespace App\Entity;

use App\Repository\ProviderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProviderRepository::class)
 */
class Provider
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $url;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }


//    /**
//     * @ORM\OneToMany(targetEntity="App\Entity\Task", mappedBy="provider")
//     */
//    private $tasks;
//
//    public function __construct()
//    {
//        $this->tasks = new ArrayCollection();
//    }
//
//    /**
//     * @return Collection|Task[]
//     */
//    public function getTasks(): Collection
//    {
//        return $this->tasks;
//    }
}
