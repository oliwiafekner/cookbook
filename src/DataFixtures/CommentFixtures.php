<?php
/**
 * Comment fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Recipe;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

/**
 * Class CommentFixtures.
 */
class CommentFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    /**
     * Load data.
     *
     * @psalm-suppress PossiblyNullPropertyFetch
     * @psalm-suppress PossiblyNullReference
     * @psalm-suppress UnusedClosureParam
     */
    public function loadData(): void
    {
        if (null === $this->manager || null === $this->faker) {
            return;
        }

        $this->createMany(10, 'comments', function (int $i) {
            $comment = new Comment();

            $comment->setContent($this->faker->text);
            $comment->setNick($this->faker->unique()->word);
            $comment->setEmail(sprintf('user%d@example.com', $i));

            /** @var Recipe $recipe */
            $recipe = $this->getRandomReference('recipes');
            $comment->setRecipe($recipe);

            return $comment;
        });

        $this->manager->flush();
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on.
     *
     * @return string[] of dependencies
     *
     * @psalm-return array{0: UserFixtures::class, 1: RecipeFixtures::class}
     */
    public function getDependencies(): array
    {
        return [UserFixtures::class, RecipeFixtures::class];
    }
}
