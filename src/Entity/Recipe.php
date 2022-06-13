<?php
/**
 * Recipe entity.
 */

namespace App\Entity;

use App\Repository\RecipeRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Recipe.
 *
 * @psalm-suppress MissingConstructor
 */
#[ORM\Entity(repositoryClass: RecipeRepository::class)]
#[ORM\Table(name: 'recipes')]
class Recipe
{
    /**
     * Primary key.
     *
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    /**
     * Title.
     *
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 255)]
    private ?string $title = null;

    /**
     * Created at.
     *
     * @var DateTimeImmutable|null
     *
     * @psalm-suppress PropertyNotSetInConstructor
     */
    #[ORM\Column(type: 'datetime_immutable')]
    private ?DateTimeImmutable $createdAt;

    /**
     * Many To One.
     *
     * @var
     */
    #[ORM\ManyToOne(targetEntity: Category::class)]
    private $category;

    /**
     * Getter for Id.
     *
     * @return int|null Id
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Getter for title.
     *
     * @return string|null Title
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Setter for title.
     *
     * @param string|null $title Title
     */
    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    /**
     * Getter for created at.
     *
     * @return DateTimeImmutable|null Created at
     */
    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * Setter for created at.
     *
     * @param DateTimeImmutable|null $createdAt Created at
     */
    public function setCreatedAt(?DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * Getter for category.
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * Setter for category.
     *
     * @return $this
     */
    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }
}