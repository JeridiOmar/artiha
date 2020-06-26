<?php

namespace App\Entity;

use App\Repository\VideoRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=VideoRepository::class)
 */
class Video extends Content
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="ce champ ne doit pas etre vide !")
     */
    private $videoPath;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     *
     * @Assert\NotBlank(message="ce champ ne doit pas etre vide !")
     */
    private $thumbnailPath;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVideoPath(): ?string
    {
        return $this->videoPath;
    }

    public function setVideoPath(string $videoPath): self
    {
        $this->videoPath = $videoPath;

        return $this;
    }

    public function getThumbnailPath(): ?string
    {
        return $this->thumbnailPath;
    }

    public function setThumbnailPath(string $thumbnailPath): self
    {
        $this->thumbnailPath = $thumbnailPath;

        return $this;
    }
    public function getChildContent()
    {
        return array('videoPath'=>$this->videoPath,'thumbnailPath'=>$this->thumbnailPath);
    }
}
