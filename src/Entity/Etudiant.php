<?php

namespace App\Entity;

use App\Repository\EtudiantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EtudiantRepository::class)]
class Etudiant extends User
{
   

    #[ORM\Column]
    private ?\DateTimeImmutable $dateDeNaissanceAt = null;

    #[ORM\Column(length: 50)]
    private ?string $tuteur = null;

    #[ORM\Column(length: 200)]
    public ?string $matricule = null;

    #[ORM\OneToMany(mappedBy: 'etudiant', targetEntity: Inscription::class)]
    private Collection $inscriptions;

    #[ORM\OneToMany(mappedBy: 'etudiant', targetEntity: ReInscription::class)]
    private Collection $reInscriptions;

    #[ORM\Column(length: 1)]
    private ?string $sexe = null;

    #[ORM\Column(length: 20)]
    private ?string $lieuDeNaissance = null;

    #[ORM\OneToMany(mappedBy: 'etudiant', targetEntity: Absence::class)]
    private Collection $absences;

   public function __construct()
   {
    $this->roles=["ROLE_ETUDIANT"];
    $this->inscriptions = new ArrayCollection();
    $this->reInscriptions = new ArrayCollection();
    $this->absences = new ArrayCollection();
   }

    public function getDateDeNaissanceAt(): ?\DateTimeImmutable
    {
        return $this->dateDeNaissanceAt;
    }

    public function setDateDeNaissanceAt(\DateTimeImmutable $dateDeNaissanceAt): static
    {
        $this->dateDeNaissanceAt = $dateDeNaissanceAt;

        return $this;
    }

    public function getTuteur(): ?string
    {
        return $this->tuteur;
    }

    public function setTuteur(string $tuteur): static
    {
        $this->tuteur = $tuteur;

        return $this;
    }

    public function getMatricule(): ?string
    {
        return $this->matricule;
    }

    public function setMatricule(string $matricule): static
    {
        $this->matricule = $matricule;

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
            $inscription->setEtudiant($this);
        }

        return $this;
    }

    public function removeInscription(Inscription $inscription): static
    {
        if ($this->inscriptions->removeElement($inscription)) {
            // set the owning side to null (unless already changed)
            if ($inscription->getEtudiant() === $this) {
                $inscription->setEtudiant(null);
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
            $reInscription->setEtudiant($this);
        }

        return $this;
    }

    public function removeReInscription(ReInscription $reInscription): static
    {
        if ($this->reInscriptions->removeElement($reInscription)) {
            // set the owning side to null (unless already changed)
            if ($reInscription->getEtudiant() === $this) {
                $reInscription->setEtudiant(null);
            }
        }

        return $this;
    }

    public function getSexe(): ?string
    {
        return $this->sexe;
    }

    public function setSexe(string $sexe): static
    {
        $this->sexe = $sexe;

        return $this;
    }

    public function getLieuDeNaissance(): ?string
    {
        return $this->lieuDeNaissance;
    }

    public function setLieuDeNaissance(string $lieuDeNaissance): static
    {
        $this->lieuDeNaissance = $lieuDeNaissance;

        return $this;
    }

    /**
     * @return Collection<int, Absence>
     */
    public function getAbsences(): Collection
    {
        return $this->absences;
    }

    public function addAbsence(Absence $absence): static
    {
        if (!$this->absences->contains($absence)) {
            $this->absences->add($absence);
            $absence->setEtudiant($this);
        }

        return $this;
    }

    public function removeAbsence(Absence $absence): static
    {
        if ($this->absences->removeElement($absence)) {
            // set the owning side to null (unless already changed)
            if ($absence->getEtudiant() === $this) {
                $absence->setEtudiant(null);
            }
        }

        return $this;
    }
}
