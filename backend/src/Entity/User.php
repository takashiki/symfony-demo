<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Annotation\Ignore;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Annotation as Serializer;

#[ORM\Table(name: "test_users")]
#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 20)]
    private $username;

    #[ORM\Column(type: 'string', length: 75)]
    private $email;

    #[Ignore]
    #[ORM\Column(type: 'string', length: 255)]
    private $password;

    #[Ignore]
    #[ORM\Column(type: 'boolean')]
    private $is_member;

    #[Ignore]
    #[ORM\Column(type: 'boolean', nullable: true)]
    private $is_active;

    #[Ignore]
    #[ORM\Column(type: 'integer')]
    private $user_type;

    #[Ignore]
    #[ORM\Column(type: 'datetime', nullable: true)]
    private $last_login_at;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function isIsMember(): ?bool
    {
        return $this->is_member;
    }

    public function setIsMember(bool $is_member): self
    {
        $this->is_member = $is_member;

        return $this;
    }

    public function isIsActive(): ?bool
    {
        return $this->is_active;
    }

    public function setIsActive(?bool $is_active): self
    {
        $this->is_active = $is_active;

        return $this;
    }

    public function getUserType(): ?int
    {
        return $this->user_type;
    }

    public function setUserType(int $user_type): self
    {
        $this->user_type = $user_type;

        return $this;
    }

    #[Serializer\Context([DateTimeNormalizer::FORMAT_KEY => 'Y-m-d H:i:s'])]
    public function getLastLoginAt(): ?\DateTime
    {
        return $this->last_login_at;
    }

    public function setLastLoginAt(?\DateTime $last_login_at): self
    {
        $this->last_login_at = $last_login_at;

        return $this;
    }
}
