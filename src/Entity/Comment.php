<?php
/**
 * Comment entity.
 */

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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
    #[ORM\Column(type: 'text', nullable: false)]
    #[Assert\NotBlank]
    private ?string $content = null;

    /**
     * Recipe.
     *
     * @ORM\ManyToOne(
     *     fetch="EXTRA_LAZY"
     * )
     */
    #[ORM\ManyToOne(targetEntity: Recipe::class, inversedBy: 'comment')]
    #[Assert\NotBlank]
    private ?Recipe $recipe = null;

    /**
     * Getter for id.
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Getter for content.
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * Setter for content.
     *
     * @return $this
     */
    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Getter for recipe.
     */
    public function getRecipe(): ?Recipe
    {
        return $this->recipe;
    }

    /**
     * Setter for recipe.
     *
     * @return $this
     */
    public function setRecipe(?Recipe $recipe): self
    {
        $this->recipe = $recipe;

        return $this;
    }
}
