<?php

namespace App\DataFixtures;

use App\Entity\Image;
use Faker\Factory;
use App\Entity\Marque;
use App\Entity\User;
use App\Entity\Voiture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{   
    // gestion du hasher de password
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

     public function load(ObjectManager $manager): void
     {

        $faker = Factory::create('fr_FR');

        //création d'un admin 
        $admin = new User();
        $admin->setEmail('admin@epse.be')
              ->setRoles(['ROLE_ADMIN'])
              ->setPassword($this->passwordHasher->hashPassword($admin,'password'));
        $manager->persist($admin);
        
            
             //  tableau des nom des marque et images
            
            $marqueNom = array(
                'audi' , 'BMW', 'Opel', 'Porsche', 'Seat', 'Volkswagen', 'Mercedes-Benz'
            );
            $marqueCover = array(
                'Audi.png', 'Bmw.png', 'Opel.png', 'Porsche.png', 'Seat.png', 'Volkswagen.png', 'Mercedes-Benz.png'
            );
        
        
        // boucle des Marques
        for ($i=0; $i < 7 ; $i++) { 
            $marque = new Marque();
            $marque->setNom($marqueNom[$i])
                ->setCover($marqueCover[$i]);

             
            // boucle des voitures dans la boucle des marques          
            for ($j=0; $j < rand(4,8) ; $j++){ 

                // tableau pour les entré a choix
                $cl = array( 'monocylindre', 'bicylindre', '3-cylindres' ,'4-cylindres','6-cylindres' );
                $carb = array ('Diesel', 'Essence ', 'Electriques' ,'Hybride','GPL','Autres');
                $transmi = array ('Avant', 'Arrière', 'Avant/Arrière');

                $voiture = new Voiture();
                $voiture->setModele($faker->Word())
                        ->setKm(rand(5000,200000))
                        ->setPrix(rand(1500,15000))
                        ->setCylindree($cl[array_rand($cl)])
                        ->setPuissance(rand(88,200))
                        ->setCarburant($carb[array_rand($carb)])
                        ->setAnneeCircul($faker->dateTimeBetween($startDate = '-10 years', $endDate = 'now'))
                        ->setTransmission($transmi[array_rand($transmi)])
                        ->setNbProprio(rand(1,3))
                        ->setDescription(join(',', $faker->paragraphs(1)))
                        ->setOptionCar(join(',', $faker->words(5)))
                        ->setMarque($marque)
                        ->setCover('cover.png');

                        // boucle pour ajouter des photos relier au voiture
                        for ($k=0; $k < rand(2,4); $k++) { 
                            $image = new Image();
                            $image->setVoiture($voiture)
                                  ->setPath('https://api.lorem.space/image/car?w=500&h=300');
                            $manager->persist($image);     
                        }

                $manager->persist($voiture); 
            }

        $manager->persist($marque);
        }
        $manager->flush();
        }
        

        

            
    }


