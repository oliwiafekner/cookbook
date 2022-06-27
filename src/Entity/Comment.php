<?php
/**
 * Comment entity.
 */

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Comment.
 *
 * @psalm-suppress MissingConstructor
 */
#[ORM\Entity(repositoryClass: CommentRepository::class)]
#[ORM\Table(name: 'comments')]
class Comment
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
     * Content.
     *
     * @var string|null
     */
    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $content = null;

    /**
     * Author.
     *
     * @ORM\ManyToOne(
     *     fetch="EXTRA_LAZY"
     * )
     */
    #[ORM\ManyToOne(targetEntity: User::class)]
    private ?User $author = null;

    /**
     * Recipe.
     *
     * @ORM\ManyToOne(
     *     fetch="EXTRA_LAZY"
     * )
     */
    #[ORM\ManyToOne(targetEntity: Recipe::class, inversedBy: 'comment')]
    private ?Recipe $recipe = null;

    /**
     * Getter for id.
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Getter for content.
     *
     * @return string|null
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * Setter for content.
     *
     * @param string|null $content
     * @return $this
     */
    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Getter for author.
     *
     * @return User|null
     */
    public function getAuthor(): ?User
    {
        return $this->author;
    }

    /**
     * Setter for author.
     *
     * @param User|null $author
     * @return $this
     */
    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Getter for recipe.
     *
     * @return Recipe|null
     */
    public function getRecipe(): ?Recipe
    {
        return $this->recipe;
    }

    /**
     * Setter for recipe.
     *
     * @param Recipe|null $recipe
     * @return $this
     */
    public function setRecipe(?Recipe $recipe): self
    {
        $this->recipe = $recipe;

        return $this;
    }
}
