<?php
namespace App\Entity\Traits;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * Le trait Timer est utilisé pour ajouter des propriétés de dates de création et de mise à jour à une entité, ainsi que des méthodes associées pour accéder et définir ces valeurs. Ce trait facilite la gestion automatique des dates dans les entités qui l'utilisent.
 * 
 */
trait Timer {

    #[Groups(['time:read'])]
    #[ORM\Column(type: 'datetime', options: ['default' => 'CURRENT_TIMESTAMP'])]
    private $createdAt;
    
    #[Groups(['time:read'])]
    #[ORM\Column(type: 'datetime', nullable: true)]
    private $updatedAt;

    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }
    public function setCreatedAt(DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }
    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }
    public function setUpdatedAt(DateTimeInterface $updatedAt): self
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