<?php
/**
 * Recipe fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Recipe;
use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

/**
 * Class RecipeFixtures.
 */
class RecipeFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
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

        $this->createMany(100, 'recipes', function (int $i) {
            $recipe = new Recipe();
            $recipe->setTitle($this->faker->sentence);
            $recipe->setCreatedAt(
                DateTimeImmutable::createFromMutable(
                    $this->faker->dateTimeBetween('-100 days', '-1 days')
                )
            );
            $recipe->setContent($this->faker->text);
            /** @var Category $category */
            $category = $this->getRandomReference('categories');
            $recipe->setCategory($category);

            /** @var User $author */
            $author = $this->getRandomReference('users');
            $recipe->setAuthor($author);

            return $recipe;
        });

        $this->manager->flush();
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on.
     *
     * @return string[] of dependencies
     *
     * @psalm-return array{0: CategoryFixtures::class, 1: UserFixtures::class}
     */
    public function getDependencies(): array
    {
        return [CategoryFixtures::class, UserFixtures::class];
    }
}
