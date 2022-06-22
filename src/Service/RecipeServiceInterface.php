<?php
/**
 * Recipe service interface.
 */

namespace App\Service;

use Knp\Component\Pager\Pagination\PaginationInterface;
use App\Entity\Category;

/**
 * Interface RecipeServiceInterface.
 */
interface RecipeServiceInterface
{
    /**
     * Get paginated list.
     *
     * @param int $page Page number
     *
     * @return PaginationInterface<string, mixed> Paginated list
     */
    public function getPaginatedList(int $page): PaginationInterface;
}
