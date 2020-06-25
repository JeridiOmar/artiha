<?php

namespace App\Entity;

use App\Repository\ChangePwdRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ChangePwdRepository::class)
 */
class ChangePwd
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;
    private $useroldpwd;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\IdenticalTo(
     *     propertyPath="useroldpwd",
     *     message="Old password is wrong!"
     * )
     */
    private $oldpwd;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Regex(
     *     pattern="/^(?=.*[a-z])(?=.*\d).{6,}/im", message="New password is required to be minimum 6 chars in length and to include at least one letter and one number."
     * )
     */
    private $newpwd;
    /**
     *@Assert\IdenticalTo(
     *     propertyPath="newpwd",
     * message="passwords are not matched."
     * )
     */
    private $confirmpwd;

    /**
     * @return mixed
     */
    public function getUseroldpwd()
    {
        return $this->useroldpwd;
    }

    /**
     * @param mixed $useroldpwd
     */
    public function setUseroldpwd($useroldpwd): void
    {
        $this->useroldpwd = $useroldpwd;
    }

    /**
     * @return mixed
     */
    public function getConfirmpwd()
    {
        return $this->confirmpwd;
    }

    /**
     * @param mixed $confirmpwd
     */
    public function setConfirmpwd($confirmpwd): void
    {
        $this->confirmpwd = $confirmpwd;
    }

    /**
     * @ORM\Column(type="datetime")
     * @Assert\Date
     * @var string A "Y-m-d" formatted value
     */
    private $date_de_changement;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getOldpwd(): ?string
    {
        return $this->oldpwd;
    }

    public function setOldpwd(string $oldpwd): self
    {
        $this->oldpwd = $oldpwd;

        return $this;
    }

    public function getNewpwd(): ?string
    {
        return $this->newpwd;
    }

    public function setNewpwd(string $newpwd): self
    {
        $this->newpwd = $newpwd;

        return $this;
    }

    public function getDateDeChangement(): ?\DateTimeInterface
    {
        return $this->date_de_changement;
    }

    public function setDateDeChangement(\DateTimeInterface $date_de_changement): self
    {
        $this->date_de_changement = $date_de_changement;

        return $this;
    }
    /**
     * @return Collection|ChangePwd[]
     */
    public function getChangePwds(): Collection
    {
        return $this->changePwds;
    }

    public function addChangePwd(ChangePwd $changePwd): self
    {
        if (!$this->changePwds->contains($changePwd)) {
            $this->changePwds[] = $changePwd;
            $changePwd->setUser($this);
        }

        return $this;
    }

    public function removeChangePwd(ChangePwd $changePwd): self
    {
        if ($this->changePwds->contains($changePwd)) {
            $this->changePwds->removeElement($changePwd);
            // set the owning side to null (unless already changed)
            if ($changePwd->getUser() === $this) {
                $changePwd->setUser(null);
            }
        }

        return $this;
    }
}
