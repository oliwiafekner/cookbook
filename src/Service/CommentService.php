<?php
/**
 * Comment service.
 */

namespace App\Service;

use App\Entity\Comment;
use App\Entity\Recipe;
use App\Repository\CommentRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class CommentService.
 */
class CommentService implements CommentServiceInterface
{
    /**
     * Comment repository.
     */
    private CommentRepository $commentRepository;

    /**
     * Recipe service.
     */
    private RecipeServiceInterface $recipeService;

    /**
     * Paginator.
     */
    private PaginatorInterface $paginator;

    /**
     * Constructor.
     *
     * @param RecipeServiceInterface $recipeService     Recipe service
     * @param CommentRepository      $commentRepository Comment repository
     * @param PaginatorInterface     $paginator         Paginator
     */
    public function __construct(RecipeServiceInterface $recipeService, CommentRepository $commentRepository, PaginatorInterface $paginator)
    {
        $this->recipeService = $recipeService;
        $this->commentRepository = $commentRepository;
        $this->paginator = $paginator;
    }

    /**
     * Get paginated list.
     *
     * @param int    $page   Page number
     * @param Recipe $recipe Recipe
     *
     * @return PaginationInterface<string, mixed> Paginated list
     */
    public function getPaginatedList(int $page, Recipe $recipe): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->commentRepository->queryByRecipe($recipe),
            $page,
            CommentRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Save entity.
     *
     * @param Comment $comment Comment entity
     */
    public function save(Comment $comment): void
    {
        $this->commentRepository->save($comment);
    }

    /**
     * Delete entity.
     *
     * @param Comment $comment Comment entity
     */
    public function delete(Comment $comment): void
    {
        $this->commentRepository->delete($comment);
    }
}
