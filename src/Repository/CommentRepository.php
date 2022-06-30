<?php
/**
 * Comment repository.
 */

namespace App\Repository;

use App\Entity\Comment;
use App\Entity\Recipe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class CommentRepository.
 *
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 * @extends ServiceEntityRepository<Comment>
 *
 * @psalm-suppress LessSpecificImplementedReturnType
 */
class CommentRepository extends ServiceEntityRepository
{
    /**
     * Items per page.
     *
     * Use constants to define configuration options that rarely change instead
     * of specifying them in configuration files.
     * See https://symfony.com/doc/current/best_practices.html#configuration
     *
     * @constant int
     */
    public const PAGINATOR_ITEMS_PER_PAGE = 5;

    /**
     * Constructor.
     *
     * @param ManagerRegistry $registry Manager registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    /**
     * Query all records.
     */
    public function queryAll(Recipe $recipe): QueryBuilder
    {
        return $this->getOrCreateQueryBuilder()
            ->select(
                'partial comment.{id, content}',
                'partial recipe.{id,title}'
            )
            ->join('comment.recipe', 'recipe')
            ->andWhere('comment.recipe = :recipe')
            ->setParameter('recipe', $recipe)
            ->orderBy('comment.recipe', 'ASC');
    }

    /**
     * Query comments by recipe.
     *
     * @param Recipe $recipe Recipe entity
     *
     * @return QueryBuilder Query builder
     */
    public function queryByRecipe(Recipe $recipe): QueryBuilder
    {
        $queryBuilder = $this->queryAll();

        $queryBuilder->andWhere('comment.recipe = :recipe')
            ->setParameter('recipe', $recipe);

        return $queryBuilder;
    }

    /**
     * Save entity.
     *
     * @param Comment $comment Comment entity
     */
    public function save(Comment $comment): void
    {
        $this->_em->persist($comment);
        $this->_em->flush();
    }

    /**
     * Delete entity.
     *
     * @param Comment $comment Comment entity
     */
    public function delete(Comment $comment): void
    {
        $this->_em->remove($comment);
        $this->_em->flush();
    }

    /**
     * Get or create new query builder.
     *
     * @param QueryBuilder|null $queryBuilder Query builder
     *
     * @return QueryBuilder Query builder
     */
    private function getOrCreateQueryBuilder(QueryBuilder $queryBuilder = null): QueryBuilder
    {
        return $queryBuilder ?? $this->createQueryBuilder('comment');
    }
}
