<?php
namespace App\Entity\Traits;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\DateFilter;
use Symfony\Component\Serializer\Annotation\Groups;

trait Timer {
    /**
     * @ORM\Column(type="datetime")
     */
    #[
        ORM\Column(type: 'datetime', options: ['default' => 'CURRENT_TIMESTAMP']),
        Groups(['time', 'time:read']),
        ApiFilter(OrderFilter::class),
        ApiFilter(DateFilter::class)
    ]
    private $createdAt;
    /**
     * @ORM\Column(type="datetime")
     */
    #[
        ORM\Column(type: 'datetime', nullable: true),
        Groups(['time', 'time:read']),
        ApiFilter(OrderFilter::class),
        ApiFilter(DateFilter::class)
    ]
    private $updatedAt;

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }
    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }
    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }
    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function updateTimer(){

        if ($this->getCreatedAt() == null){
            $this->setCreatedAt(new \DateTimeImmutable());
        }

        $this->setUpdatedAt(new \DateTimeImmutable());
    }
}