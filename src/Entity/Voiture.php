<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\VoitureRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: VoitureRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Voiture
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'voitures')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Marque $marque = null;

    #[ORM\Column(length: 120)]
    #[Assert\NotBlank(message:'veillez remplire le champ model')]
    #[Assert\Length(min: 1, max: 120, minMessage: "Le Model doit faire plus de 1 caractère", maxMessage:"Le Model ne doit pas faire plus de 120 caractères")]
    private ?string $modele = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\Column]

    #[Assert\NotBlank(message:'veillez remplire le champ klm')]
    //#[Assert\GreaterThanOrEqual(value: 0, valueMessage:"le Nombre de KLM est impossible")]
    private ?int $km = null;

    #[ORM\Column]
    #[Assert\NotBlank(message:'veillez remplire le champ prix')]
    //#[Assert\GreaterThan(min: 1, max: 250000, minMessage: "prix trop petit", maxMessage: "prix trop haut")]
    private ?float $prix = null;

    #[ORM\Column(length: 120)]
    #[Assert\NotBlank(message:'veillez remplire le champ cylindrée')]
    //#[Assert\Choice(['monocylindre', 'bicylindre ', '3-cylindres' ,'4-cylindres','6-cylindres'])]
    private ?string $cylindree = null;

    #[ORM\Column]
    #[Assert\NotBlank(message:'veillez remplire le champ puissance')]
    //#[Assert\GreaterThan(value: 0, valueMessage:"la puissance doit etre plus élevé")]
    private ?int $puissance = null;

    #[ORM\Column(length: 120)]
    #[Assert\NotBlank(message:'veillez remplire le champ carburant')]
    //#[Assert\Choice(['Diesel', 'Essence ', 'Electriques' ,'Hybride','GPL','Autres'])]
    private ?string $carburant = null;

    #[ORM\Column(length: 120)]
    #[Assert\NotBlank(message:'veillez remplire le champ transmission')]
    //#[Assert\Choice(['Avant', 'Arrière ', 'Avant/Arrière'])]
    private ?string $transmission = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank(message:'veillez remplire le champ date de 1ere mise en circulation')]
    private ?\DateTimeInterface $anneeCircul = null;

    #[ORM\Column]
    //#[Assert\GreaterThan(value: 0, valueMessage:"il doit aumoin avoir eu un proprio")]
    #[Assert\NotBlank(message:'veillez remplire le champ nombres de propriétaire')]
    private ?int $nbProprio = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message:'veillez remplire le champ description')]
    //#[Assert\Length(min: 10, max: 1000, minMessage: "la desc doit au moin faire 10 caractères", maxMessage:"la desc doit faire moin de 1000 caractères")]
    private ?string $description = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\Length(min: 10, max: 1000, minMessage: "les options doit au moin faire 10 caractères", maxMessage:"les option doit faire moin de 1000 caractères")]
    private ?string $optionCar = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:'veillez télécharger un cover pour la voiture')]
    private ?string $cover = null;

    #[ORM\OneToMany(mappedBy: 'voiture', targetEntity: Image::class)]
    private Collection $images;

    public function __construct()
    {
        $this->images = new ArrayCollection();
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function initializeSlug() :void
    {
        if(empty($this->slug))
        {
            $slugify = new Slugify();
            $this->slug = $slugify->slugify($this->modele.'-'.rand(2000,8000000));
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMarque(): ?Marque
    {
        return $this->marque;
    }

    public function setMarque(?Marque $marque): self
    {
        $this->marque = $marque;

        return $this;
    }

    public function getModele(): ?string
    {
        return $this->modele;
    }

    public function setModele(string $modele): self
    {
        $this->modele = $modele;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getKm(): ?int
    {
        return $this->km;
    }

    public function setKm(int $km): self
    {
        $this->km = $km;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getCylindree(): ?string
    {
        return $this->cylindree;
    }

    public function setCylindree(string $cylindree): self
    {
        $this->cylindree = $cylindree;

        return $this;
    }

    public function getPuissance(): ?int
    {
        return $this->puissance;
    }

    public function setPuissance(int $puissance): self
    {
        $this->puissance = $puissance;

        return $this;
    }

    public function getCarburant(): ?string
    {
        return $this->carburant;
    }

    public function setCarburant(string $carburant): self
    {
        $this->carburant = $carburant;

        return $this;
    }

    public function getTransmission(): ?string
    {
        return $this->transmission;
    }

    public function setTransmission(string $transmission): self
    {
        $this->transmission = $transmission;

        return $this;
    }

    public function getAnneeCircul(): ?\DateTimeInterface
    {
        return $this->anneeCircul;
    }

    public function setAnneeCircul(\DateTimeInterface $anneeCircul): self
    {
        $this->anneeCircul = $anneeCircul;

        return $this;
    }

    public function getNbProprio(): ?int
    {
        return $this->nbProprio;
    }

    public function setNbProprio(int $nbProprio): self
    {
        $this->nbProprio = $nbProprio;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getOptionCar(): ?string
    {
        return $this->optionCar;
    }

    public function setOptionCar(string $optionCar): self
    {
        $this->optionCar = $optionCar;

        return $this;
    }

    public function getCover(): ?string
    {
        return $this->cover;
    }

    public function setCover(string $cover): self
    {
        $this->cover = $cover;

        return $this;
    }

    /**
     * @return Collection<int, Image>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
            $image->setVoiture($this);
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getVoiture() === $this) {
                $image->setVoiture(null);
            }
        }

        return $this;
    }
}
