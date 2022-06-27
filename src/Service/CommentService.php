<?php
/**
 * Comment service.
 */

namespace App\Service;

use App\Entity\Comment;
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
     * @param int                $page    Page number
     * @param array<string, int> $filters Filters array
     *
     * @return PaginationInterface<SlidingPagination> Paginated list
     */
    public function getPaginatedList(int $page, array $filters = []): PaginationInterface
    {
        $filters = $this->prepareFilters($filters);

        return $this->paginator->paginate(
            $this->commentRepository->queryAll($filters),
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

    /**
     * Prepare filters for the comments list.
     *
     * @param array<string, int> $filters Raw filters from request
     *
     * @return array<string, object> Result array of filters
     */
    public function prepareFilters(array $filters): array
    {
        $resultFilters = [];
        if (!empty($filters['recipe_id'])) {
            $recipe = $this->recipeService->findOneById($filters['recipe_id']);
            if (null !== $recipe) {
                $resultFilters['recipe'] = $recipe;
            }
        }

        return $resultFilters;
    }
}
