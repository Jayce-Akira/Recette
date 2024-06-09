<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Recipe;
use App\Entity\Category;
use App\DataFixtures\UserFixtures;
use App\Entity\Ingredient;
use App\Entity\Quantity;
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

        $ingredients = array_map(fn(string $name) => (new Ingredient())
            ->setName($name)
            ->setSlug(strtolower($this->slugger->slug($name))), [
                "Farine",
                "Sucre",
                "Oeufs",
                "Beurre",
                "Lait",
                "Levure chimique",
                "Sel",
                "Chocolat noir",
                "Pépites de chocolat",
                "Fruits secs(amandes, noix, etc.)",
                "Vanille",
                "Cannelle",
                "Fraise",
                "Banane",
                "Pomme",
                "Carotte",
                "Oignon",
                "Ail",
                "Echalote",
                "Herbes fraîches (ciboulette, persil, etc.)"
            ]
        );

        $units = [
            "g",
            "Kg",
            "L",
            "ml",
            "cl",
            "dL",
            "c. à soupe",
            "c. à café",
            "pincée",
            "verre"
        ];


        foreach($ingredients as $ingredient) {
            $manager->persist($ingredient);
        }

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

            foreach($faker->randomElements($ingredients, $faker->numberBetween(2, 5)) as $ingredient){
                $recipe->addQuantity((new Quantity())
                    ->setQuantity($faker->numberBetween(1, 250))
                    ->setUnit($faker->randomElement($units))
                    ->setIngredient($ingredient)
                );
            }
        
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
