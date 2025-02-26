// src/Entity/Property.php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Property
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $id;

    #[ORM\Column(type: "string", length: 255)]
    private string $address;

    #[ORM\Column(type: "decimal", scale: 2)]
    private float $price;

    #[ORM\Column(type: "string", length: 50)]
    private string $source;

    #[ORM\Column(type: "datetime")]
    private \DateTimeInterface $lastUpdated;

    // Getters & Setters
    public function getId(): int { return $this->id; }
    public function getAddress(): string { return $this->address; }
    public function setAddress(string $address): void { $this->address = $address; }

    public function getPrice(): float { return $this->price; }
    public function setPrice(float $price): void { $this->price = $price; }

    public function getSource(): string { return $this->source; }
    public function setSource(string $source): void { $this->source = $source; }

    public function getLastUpdated(): \DateTimeInterface { return $this->lastUpdated; }
    public function setLastUpdated(\DateTimeInterface $lastUpdated): void { $this->lastUpdated = $lastUpdated; }
}
