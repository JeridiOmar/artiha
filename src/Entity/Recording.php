<?php

namespace App\Entity;

use App\Repository\RecordingRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RecordingRepository::class)
 */
class Recording extends Content
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $recordingPath;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRecordingPath(): ?string
    {
        return $this->recordingPath;
    }

    public function setRecordingPath(string $recordingPath): self
    {
        $this->recordingPath = $recordingPath;

        return $this;
    }
    public function getChildContent()
    {
        return $this->getRecordingPath();
    }
    public function __toString()
    {
     return $this->getRecordingPath();
    }
}
