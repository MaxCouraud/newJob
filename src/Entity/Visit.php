<?php

namespace App\Entity;

use App\Repository\VisitRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VisitRepository::class)]
class Visit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 64)]
    private string $code;

    #[ORM\Column(length: 64)]
    private string $profileType;

    #[ORM\Column(length: 45, nullable: true)]
    private ?string $ip = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $userAgent = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $page = null;

    #[ORM\Column]
    private \DateTimeImmutable $createdAt;

    public function __construct(string $code, string $profileType, ?string $ip, ?string $userAgent, ?string $page = '/')
    {
        $this->code = $code;
        $this->profileType = $profileType;
        $this->ip = $ip;
        $this->userAgent = $userAgent;
        $this->page = $page;
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int { return $this->id; }
    public function getCode(): string { return $this->code; }
    public function getProfileType(): string { return $this->profileType; }
    public function getIp(): ?string { return $this->ip; }
    public function getUserAgent(): ?string { return $this->userAgent; }
    public function getPage(): ?string { return $this->page; }
    public function getCreatedAt(): \DateTimeImmutable { return $this->createdAt; }
}
