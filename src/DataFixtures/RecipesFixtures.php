<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Course;
use App\Entity\Rating;
use App\Entity\Recipes;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use FakerRestaurant\Provider\en_US\Restaurant;
use Symfony\Component\String\Slugger\SluggerInterface;

class RecipesFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        private readonly SluggerInterface $slugger
    )
    {
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('en_US');
        $faker->addProvider(new Restaurant($faker));

        $categories = ['Rice', 'Pork', 'Beef', 'Pasta', 'Potatoes', 'Chicken'];
        foreach ($categories as $cat){
            $category = (new Category())
                ->setName($cat)
                ->setSlug($this->slugger->slug($cat))
                ->setUpdatedAt(\DateTimeImmutable::createFromMutable($faker->dateTime()))
                ->setCreatedAt(\DateTimeImmutable::createFromMutable($faker->dateTime()));

            $manager->persist($category);
            $this->addReference($cat, $category);
        }

        $courses = ['Appetizer', 'Drink', 'Starter', 'Main course', 'Side Dish', 'Dessert'];
        foreach ($courses as $cours){
            $course = (new Course())
                ->setName($cours);

            $manager->persist($course);
            $this->addReference($cours, $course);
        }

        $ratings = [0,1,2,3,4,5];
        foreach ($ratings as $ra){
            $rating = (new Rating())
                ->setUser($this->getReference('USER'))
                ->setStars($ra);

            $manager->persist($course);
            $this->addReference($cours, $course);
        }

        for($i = 1; $i <= 25; $i++){
            $name = $faker->foodName();
            $recipe = (new Recipes())
                ->setName($name)
                ->setSlug($this->slugger->slug($name))
                ->setUpdatedAt(\DateTimeImmutable::createFromMutable($faker->dateTime()))
                ->setCreatedAt(\DateTimeImmutable::createFromMutable($faker->dateTime()))
                ->setContent($faker->paragraph(4, true))
                ->setDuration($faker->numberBetween(2,45))
                ->setNumberViews($faker->numberBetween(14, 1500))
                ->setVegetarian($faker->numberBetween(0,1))
                ->addCategory($this->getReference($faker->randomElement($categories)))
                ->addCourse($this->getReference($faker->randomElement($courses)));

            $manager->persist($recipe);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
       return [AppFixtures::class];
    }
}
