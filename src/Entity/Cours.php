<?php

namespace App\Entity;

use App\Repository\CoursRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CoursRepository::class)]
class Cours
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $nbrHeureGlobal = null;

    #[ORM\Column]
    private ?int $nbrHeurePlanifier = 0;

    #[ORM\Column]
    private ?int $nbrHeureEffectuer = 0;

    #[ORM\ManyToOne(inversedBy: 'cours')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Professeur $professeur = null;

    #[ORM\ManyToOne(inversedBy: 'cours')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Module $module = null;

    #[ORM\ManyToOne(inversedBy: 'cours')]
    #[ORM\JoinColumn(nullable: false)]
    private ?AnneeScolaire $anneeScolaire = null;

    #[ORM\ManyToOne(inversedBy: 'cours')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Semestre $semestre = null;

    #[ORM\OneToMany(mappedBy: 'cours', targetEntity: Planning::class)]
    private Collection $plannings;

    #[ORM\OneToMany(mappedBy: 'cour', targetEntity: Participation::class)]
    private Collection $participations;

    #[ORM\ManyToMany(targetEntity: Classe::class, inversedBy: 'cours')]
    private Collection $classes;

    #[ORM\ManyToOne(inversedBy: 'cours')]
    private ?Niveau $niveau = null;

    #[ORM\Column]
    private ?bool $isDone = false;

    // #[ORM\OneToMany(mappedBy: 'cours', targetEntity: Absence::class)]
    // private Collection $absences;

    public function __construct()
    {
        $this->plannings = new ArrayCollection();
        $this->participations = new ArrayCollection();
        $this->classes = new ArrayCollection();
        // $this->absences = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNbrHeureGlobal(): ?int
    {
        return $this->nbrHeureGlobal;
    }

    public function setNbrHeureGlobal(int $nbrHeureGlobal): static
    {
        $this->nbrHeureGlobal = $nbrHeureGlobal;

        return $this;
    }

    public function getNbrHeurePlanifier(): ?int
    {
        return $this->nbrHeurePlanifier;
    }

    public function setNbrHeurePlanifier(int $nbrHeurePlanifier): static
    {
        $this->nbrHeurePlanifier = $nbrHeurePlanifier;

        return $this;
    }

    public function getNbrHeureEffectuer(): ?int
    {
        return $this->nbrHeureEffectuer;
    }

    public function setNbrHeureEffectuer(int $nbrHeureEffectuer): static
    {
        $this->nbrHeureEffectuer = $nbrHeureEffectuer;

        return $this;
    }

    public function getProfesseur(): ?Professeur
    {
        return $this->professeur;
    }

    public function setProfesseur(?Professeur $professeur): static
    {
        $this->professeur = $professeur;

        return $this;
    }

    public function getModule(): ?Module
    {
        return $this->module;
    }

    public function setModule(?Module $module): static
    {
        $this->module = $module;

        return $this;
    }

    public function getAnneeScolaire(): ?AnneeScolaire
    {
        return $this->anneeScolaire;
    }

    public function setAnneeScolaire(?AnneeScolaire $anneeScolaire): static
    {
        $this->anneeScolaire = $anneeScolaire;

        return $this;
    }

    public function getSemestre(): ?Semestre
    {
        return $this->semestre;
    }

    public function setSemestre(?Semestre $semestre): static
    {
        $this->semestre = $semestre;

        return $this;
    }

    /**
     * @return Collection<int, Planning>
     */
    public function getPlannings(): Collection
    {
        return $this->plannings;
    }

    public function addPlanning(Planning $planning): static
    {
        if (!$this->plannings->contains($planning)) {
            $this->plannings->add($planning);
            $planning->setCours($this);
        }

        return $this;
    }

    public function removePlanning(Planning $planning): static
    {
        if ($this->plannings->removeElement($planning)) {
            // set the owning side to null (unless already changed)
            if ($planning->getCours() === $this) {
                $planning->setCours(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Participation>
     */
    public function getParticipations(): Collection
    {
        return $this->participations;
    }

    public function addParticipation(Participation $participation): static
    {
        if (!$this->participations->contains($participation)) {
            $this->participations->add($participation);
            $participation->setCour($this);
        }

        return $this;
    }

    public function removeParticipation(Participation $participation): static
    {
        if ($this->participations->removeElement($participation)) {
            // set the owning side to null (unless already changed)
            if ($participation->getCour() === $this) {
                $participation->setCour(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Classe>
     */
    public function getClasses(): Collection
    {
        return $this->classes;
    }

    public function addClass(Classe $class): static
    {
        if (!$this->classes->contains($class)) {
            $this->classes->add($class);
        }

        return $this;
    }

    public function removeClass(Classe $class): static
    {
        $this->classes->removeElement($class);

        return $this;
    }

    public function getNiveau(): ?Niveau
    {
        return $this->niveau;
    }

    public function setNiveau(?Niveau $niveau): static
    {
        $this->niveau = $niveau;

        return $this;
    }

    public function isIsDone(): ?bool
    {
        return $this->isDone;
    }

    public function setIsDone(bool $isDone): static
    {
        $this->isDone = $isDone;

        return $this;
    }

    // /**
    //  * @return Collection<int, Absence>
    //  */
    // public function getAbsences(): Collection
    // {
    //     return $this->absences;
    // }

    // public function addAbsence(Absence $absence): static
    // {
    //     if (!$this->absences->contains($absence)) {
    //         $this->absences->add($absence);
    //         $absence->setCours($this);
    //     }

    //     return $this;
    // }

    // public function removeAbsence(Absence $absence): static
    // {
    //     if ($this->absences->removeElement($absence)) {
    //         // set the owning side to null (unless already changed)
    //         if ($absence->getCours() === $this) {
    //             $absence->setCours(null);
    //         }
    //     }

    //     return $this;
    // }
}
