<?php

namespace App\Entity;

use Doctrine\ORM\Mapping\InheritanceType;
use App\Repository\ContentRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ContentRepository::class)
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="types" , type="string")
 * @ORM\DiscriminatorMap({
 *     "picture" = "Picture",
 *     "text" = "Text",
 *     "video" = "Video",
 *     "recording" = "Recording"
 * })
 */
abstract class Content
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    public function getId(): ?int
    {
        return $this->id;
    }
}
