<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(
 *     fields={"Email"},
 *     message="l'email que vous avez entrer est deja utilise"
 * )
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     *  @Assert\Length(
     *      min = 2,
     *      max = 50,
     *      minMessage = "votre prenom doit avoir au moins {{ limit }} characteres ",
     *      maxMessage = "Votre prenom doit avoir au plus {{ limit }} characteres",
     *      allowEmptyString = false
     * )
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     *  @Assert\Length(
     *      min = 2,
     *      max = 50,
     *      minMessage = "votre nom doit avoir au moins {{ limit }} characteres ",
     *      maxMessage = "Votre nom doit avoir au plus {{ limit }} characteres",
     *      allowEmptyString = false
     * )
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255)
     *   @Assert\Email(
     *     message = "L'email '{{ value }}' n est pas un email valide."
     * )
     */
    private $Email;

    /**
     * @ORM\Column(type="string", length=255)
     *  @Assert\Length(
     *      min = 6,
     *      minMessage = "Votre mot de passe doit contenir au moins 6 characteres ",
     * )
     */
    private $password;
    /**
     *   @Assert\EqualTo(
     *     propertyPath="password",
     *     message="Le mot de passe n'est pas identique"
     * )
     */

    public $confirm_password;

    /**
     * @ORM\Column(type="string", length=6)
     */
    private $sexe;

    /**
     * @ORM\Column(type="date")
     */
    private $birthday;

    /**
     * @ORM\Column(type="string", length=9)
     *  @Assert\Length(
     *      min = 8,
     *      max = 8,
     *      minMessage = "entrer un numero valide ",
     *      maxMessage = "entrer un numero valide",
     *      allowEmptyString = false
     * )
     */
    private $numTel;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $bio;

    /**
     * @ORM\Column(type="string", length=255)

     */
    private $profilePicture;

    /**
     * @ORM\OneToMany(targetEntity=Post::class, mappedBy="user")
     */
    private $posts;



    public function __construct()
    {
        $this->posts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->Email;
    }

    public function setEmail(string $Email): self
    {
        $this->Email = $Email;

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

    public function getSexe(): ?string
    {
        return $this->sexe;
    }

    public function setSexe(string $sexe): self
    {
        $this->sexe = $sexe;

        return $this;
    }

    public function getBirthday(): ?\DateTimeInterface
    {
        return $this->birthday;
    }

    public function setBirthday(\DateTimeInterface $birthday): self
    {
        $this->birthday = $birthday;

        return $this;
    }

    public function getNumTel(): ?string
    {
        return $this->numTel;
    }

    public function setNumTel(string $numTel): self
    {
        $this->numTel = $numTel;

        return $this;
    }

    public function getBio(): ?string
    {
        return $this->bio;
    }

    public function setBio(?string $bio): self
    {
        $this->bio = $bio;

        return $this;
    }

    public function getProfilePicture(): ?string
    {
        return $this->profilePicture;
    }

    public function setProfilePicture(string $profilePicture): self
    {
        $this->profilePicture = $profilePicture;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getRoles() {
        // TODO: Implement getRoles() method.
        return ['ROLE_USER'];
    }

    /**
     * @inheritDoc
     */
    public function getSalt() {
        // TODO: Implement getSalt() method.
    }

    /**
     * @inheritDoc
     */
    public function getUsername() {
        // TODO: Implement getUsername() method.
        return $this->getEmail();
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials() {
        // TODO: Implement eraseCredentials() method.
    }

    /**
     * @return Collection|Post[]
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
            $post->setUser($this);
        }

        return $this;
    }

    public function removePost(Post $post): self
    {
        if ($this->posts->contains($post)) {
            $this->posts->removeElement($post);
            // set the owning side to null (unless already changed)
            if ($post->getUser() === $this) {
                $post->setUser(null);
            }
        }

        return $this;
    }

}
