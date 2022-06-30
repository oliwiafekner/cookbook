<?php
/**
 * Recipe entity.
 */

namespace App\Entity;

use App\Repository\RecipeRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Recipe.
 *
 * @psalm-suppress MissingConstructor
 */
#[ORM\Entity(repositoryClass: RecipeRepository::class)]
#[ORM\Table(name: 'recipes')]
#[UniqueEntity(fields: ['title'])]
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
    #[Assert\Type('string')]
    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 255)]
    private ?string $title = null;

    /**
     * Created at.
     *
     * @var DateTimeImmutable|null
     *
     * @psalm-suppress PropertyNotSetInConstructor
     */
    #[ORM\Column(type: 'datetime_immutable')]
    #[Assert\Type(DateTimeImmutable::class)]
    #[Gedmo\Timestampable(on: 'create')]
    private ?DateTimeImmutable $createdAt;

    /**
     * Content.
     *
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 16777215)]
    #[Assert\NotBlank]
    private ?string $content = null;

    /**
     * Category.
     *
     * @ORM\ManyToOne(
     *     fetch="EXTRA_LAZY"
     * )
     */
    #[ORM\ManyToOne(targetEntity: Category::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Category $category = null;

    /**
     * Author.
     *
     * @ORM\ManyToOne(
     *     fetch="EXTRA_LAZY"
     * )
     *
     * @var User|null
     */
    #[ORM\ManyToOne(targetEntity: User::class, fetch: 'EXTRA_LAZY')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank]
    #[Assert\Type(User::class)]
    private ?User $author;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->comment = new ArrayCollection();
    }

    /**
     * Comment.
     *
     * @ORM\OneToMany(
     *     fetch="EXTRA_LAZY"
     * )
     */
    #[ORM\OneToMany(mappedBy: 'recipe', targetEntity: Comment::class)]
    private $comment;

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
     * Getter for content.
     *
     * @return string|null Content
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * Setter for content.
     *
     * @param string|null $content Content
     */
    public function setContent(?string $content): void
    {
        $this->content = $content;
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

    /**
     * Getter for author.
     */
    public function getAuthor(): ?User
    {
        return $this->author;
    }

    /**
     * Setter for author.
     *
     * @return $this
     */
    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Getter for comment.
     */
    public function getComment(): Collection
    {
        return $this->comment;
    }

    /**
     * Add comment.
     *
     * @return $this
     */
    public function addComment(Comment $comment): self
    {
        if (!$this->comment->contains($comment)) {
            $this->comment[] = $comment;
            $comment->setRecipe($this);
        }

        return $this;
    }

    /**
     * Remove comment.
     *
     * @return $this
     */
    public function removeComment(Comment $comment): self
    {
        if ($this->comment->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getRecipe() === $this) {
                $comment->setRecipe(null);
            }
        }

        return $this;
    }
}
