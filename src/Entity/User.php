<?php

namespace App\Entity;

use App\Repository\UserRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'uniq_user_email', columns: ['email'])]
#[Index(name: 'idx_user_email', columns: ['email'])]
#[Index(name: 'idx_user_registration_date', columns: ['employment_date'])]
#[ORM\HasLifecycleCallbacks]
class User extends  AbstractLatestTimestamp implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["list", "salary_calculate","payment_response"])]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Groups(["list", "salary_calculate", "create", "update"])]
    private ?string $email = null;

    #[ORM\Column]
    #[Groups(["list"])]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[Groups(["create", "update"])]
    private ?string $plainPassword = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["list", "create", "update"])]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["list", "create", "update"])]
    private ?string $surname = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Groups(["list"])]
    private string|DateTimeInterface|null $employmentDate = null;

    #[Groups(["list", "salary_calculate"])]
    private ?string $totalSalary = null;

    #[ORM\OneToMany(targetEntity: Salary::class, mappedBy: 'user', cascade: ['persist', 'remove'])]
    #[Groups("list")]
    private Collection $salaries;


    public function __construct( )
    {
        $this->salaries = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string)$this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): static
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(?string $surname): static
    {
        $this->surname = $surname;

        return $this;
    }

    public function getEmploymentDate(): ?string
    {
        return $this->employmentDate ? $this->employmentDate->format('Y-m-d') : null;
    }


    public function setEmploymentDate(?\DateTimeInterface $employmentDate): static
    {
        $this->employmentDate = $employmentDate;

        return $this;
    }

    public function getTotalSalary(): ?string
    {
        return $this->totalSalary;
    }

    public function setTotalSalary(?string $totalSalary): static
    {
        $this->totalSalary = $totalSalary;

        return $this;
    }

    /**
     * @return Collection<int, Salary>
     */
    public function getSalaries(): Collection
    {
        return $this->salaries;
    }

    public function addSalary(Salary $salary): static
    {
        if (!$this->salaries->contains($salary)) {
            $this->salaries->add($salary);
            $salary->setUser($this);
        }

        return $this;
    }

    public function removeSalary(Salary $salary): static
    {
        if ($this->salaries->removeElement($salary)) {
            if ($salary->getUser() === $this) {
                $salary->setUser(null);
            }
        }

        return $this;
    }
}
