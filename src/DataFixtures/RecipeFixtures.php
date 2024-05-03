<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Recipe;
use App\Entity\Category;
use App\DataFixtures\UserFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use FakerRestaurant\Provider\fr_FR\Restaurant;
use Symfony\Component\String\Slugger\SluggerInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class RecipeFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(private readonly SluggerInterface $slugger)
    {
        
    }

    public function load(ObjectManager $manager): void
    {

        $faker = Factory::create('fr_FR');
        $faker->addProvider(new Restaurant($faker));

        $categories = ['Plat chaud','Dessert','Entrée','Goûter'];
        foreach($categories as $c){
            $category = (new Category())
                ->setName($c)
                ->setSlug($this->slugger->slug($c))
                ->setUpdatedAt(\DateTimeImmutable::createFromMutable($faker->dateTime()))
                ->setCreatedAt(\DateTimeImmutable::createFromMutable($faker->dateTime()));
            $manager->persist($category);
            $this->addReference($c, $category);
        }

        for ($i = 1; $i <= 10; $i++){
            $title = $faker->foodName();
            $recipe = (new Recipe())
                ->setTitle($title)
                ->setSlug($this->slugger->slug($title))
                ->setUpdatedAt(\DateTimeImmutable::createFromMutable($faker->dateTime()))
                ->setCreatedAt(\DateTimeImmutable::createFromMutable($faker->dateTime()))
                ->setContent($faker->paragraphs(10, true))
                ->setCategory($this->getReference($faker->randomElement($categories)))
                ->setUser($this->getReference('USER' . $faker->numberBetween(1, 10)))
                ->setDuration($faker->numberBetween(2, 60));
        
            $manager->persist($recipe);
        }
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }

    public function getDependencies()
    {
        return [UserFixtures::class];
    }



}
