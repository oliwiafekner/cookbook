<?php
/**
 * Recipe service interface.
 */

namespace App\Service;

use App\Entity\Recipe;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Interface RecipeServiceInterface.
 */
interface RecipeServiceInterface
{
    /**
     * Get paginated list.
     *
     * @param int                $page    Page number
     * @param array<string, int> $filters Filters array
     *
     * @return PaginationInterface<SlidingPagination> Paginated list
     */
    public function getPaginatedList(int $page, array $filters = []): PaginationInterface;

    /**
     * Save entity.
     *
     * @param Recipe $recipe Recipe entity
     */
    public function save(Recipe $recipe): void;

    /**
     * Delete entity.
     *
     * @param Recipe $recipe Recipe entity
     */
    public function delete(Recipe $recipe): void;

    /**
     * Prepare filters for the recipes list.
     *
     * @param array<string, int> $filters Raw filters from request
     *
     * @return array<string, object> Result array of filters
     */
    public function prepareFilters(array $filters): array;

    /**
     * Find by id.
     *
     * @param int $id Recipe id
     *
     * @return Recipe|null Category entity
     *
     * @throws NonUniqueResultException
     */
    public function findOneById(int $id): ?Recipe;
}
