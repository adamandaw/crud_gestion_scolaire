<?php

namespace App\Entity;

use App\Repository\AnneeScolaireRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AnneeScolaireRepository::class)]
class AnneeScolaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 30)]
    private ?string $libelle = null;

    #[ORM\Column]
    private ?bool $isActive = null;

    #[ORM\OneToMany(mappedBy: 'anneeScolaire', targetEntity: Inscription::class)]
    private Collection $inscriptions;

    #[ORM\OneToMany(mappedBy: 'anneeScolaire', targetEntity: ReInscription::class)]
    private Collection $reInscriptions;

    #[ORM\OneToMany(mappedBy: 'anneeScolaire', targetEntity: Cours::class)]
    private Collection $cours;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $periodInscriptionAt = null;

    public function __construct()
    {
        $this->inscriptions = new ArrayCollection();
        $this->reInscriptions = new ArrayCollection();
        $this->cours = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): static
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function isIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): static
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * @return Collection<int, Inscription>
     */
    public function getInscriptions(): Collection
    {
        return $this->inscriptions;
    }

    public function addInscription(Inscription $inscription): static
    {
        if (!$this->inscriptions->contains($inscription)) {
            $this->inscriptions->add($inscription);
            $inscription->setAnneeScolaire($this);
        }

        return $this;
    }

    public function removeInscription(Inscription $inscription): static
    {
        if ($this->inscriptions->removeElement($inscription)) {
            // set the owning side to null (unless already changed)
            if ($inscription->getAnneeScolaire() === $this) {
                $inscription->setAnneeScolaire(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ReInscription>
     */
    public function getReInscriptions(): Collection
    {
        return $this->reInscriptions;
    }

    public function addReInscription(ReInscription $reInscription): static
    {
        if (!$this->reInscriptions->contains($reInscription)) {
            $this->reInscriptions->add($reInscription);
            $reInscription->setAnneeScolaire($this);
        }

        return $this;
    }

    public function removeReInscription(ReInscription $reInscription): static
    {
        if ($this->reInscriptions->removeElement($reInscription)) {
            // set the owning side to null (unless already changed)
            if ($reInscription->getAnneeScolaire() === $this) {
                $reInscription->setAnneeScolaire(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Cours>
     */
    public function getCours(): Collection
    {
        return $this->cours;
    }

    public function addCour(Cours $cour): static
    {
        if (!$this->cours->contains($cour)) {
            $this->cours->add($cour);
            $cour->setAnneeScolaire($this);
        }

        return $this;
    }

    public function removeCour(Cours $cour): static
    {
        if ($this->cours->removeElement($cour)) {
            // set the owning side to null (unless already changed)
            if ($cour->getAnneeScolaire() === $this) {
                $cour->setAnneeScolaire(null);
            }
        }

        return $this;
    }

    public function getPeriodInscriptionAt(): ?\DateTimeImmutable
    {
        return $this->periodInscriptionAt;
    }

    public function setPeriodInscriptionAt(?\DateTimeImmutable $periodInscriptionAt): static
    {
        $this->periodInscriptionAt = $periodInscriptionAt;

        return $this;
    }
}
