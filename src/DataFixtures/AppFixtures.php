<?php

namespace App\DataFixtures;

use App\Entity\Klant;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 1; $i++) {
            $product = new Product();
            $product->setOmschrijving('Playstation 4');
            $product->setPrijs(mt_rand(100, 350));
            $product->setBtw(mt_rand(0, 21));
            $manager->persist($product);
        }

        $manager->flush();
        for ($i = 0; $i < 1; $i++) {
            $product = new Product();
            $product->setOmschrijving('Playstation 5');
            $product->setPrijs(mt_rand(100, 350));
            $product->setBtw(mt_rand(0, 21));

            $manager->persist($product);
        }

        $manager->flush();
        for ($i = 0; $i < 1; $i++) {
            $product = new Product();
            $product->setOmschrijving('XBox 360');
            $product->setPrijs(mt_rand(100, 350));
            $product->setBtw(mt_rand(0, 21));

            $manager->persist($product);
        }

        $manager->flush();
        for ($i = 0; $i < 1; $i++) {
            $product = new Product();
            $product->setOmschrijving('Xbox One X');
            $product->setPrijs(mt_rand(100, 350));
            $product->setBtw(mt_rand(0, 21));

            $manager->persist($product);
        }

        $manager->flush();
        for ($i = 0; $i < 1; $i++) {
            $product = new Product();
            $product->setOmschrijving('Apple Watch');
            $product->setPrijs(mt_rand(100, 350));
            $product->setBtw(mt_rand(0, 21));

            $manager->persist($product);
        }

        $manager->flush();
        for ($i = 0; $i < 1; $i++) {
            $product = new Product();
            $product->setOmschrijving('Airpods 2');
            $product->setPrijs(mt_rand(100, 350));
            $product->setBtw(mt_rand(0, 21));

            $manager->persist($product);
        }

        $manager->flush();
        for ($i = 0; $i < 1; $i++) {
            $product = new Product();
            $product->setOmschrijving('Nintendo switch');
            $product->setPrijs(mt_rand(100, 350));
            $product->setBtw(mt_rand(0, 21));

            $manager->persist($product);
        }

        $manager->flush();
        for ($i = 0; $i < 1; $i++) {
            $product = new Product();
            $product->setOmschrijving('Playstation 4');
            $product->setPrijs(mt_rand(100, 350));
            $product->setBtw(mt_rand(0, 21));

            $manager->persist($product);
        }

        $manager->flush();
        for ($i = 0; $i < 1; $i++) {
            $product = new Product();
            $product->setOmschrijving('Playstation 4');
            $product->setPrijs(mt_rand(100, 350));
            $product->setBtw(mt_rand(0, 21));

            $manager->persist($product);
        }

        $manager->flush();
        for ($i = 0; $i < 1; $i++) {
            $user = new Klant();
            $user->setEmail('beheer@squid-it.nl');
            $password = $this->encoder->encodePassword($user, 'welkomSquid');
            $user->setPassword($password);
            $user->setNaam('Mike');
            $user->setPlaats('Afghanistanië');
            $user->setStraat('Verweghistan');
            $user->setPostcode('7711 YE');
            $user->setRoles(['ROLE_USER', 'ROLE_ADMIN']);
            $manager->persist($user);
        }
        $manager->flush();


    }


}
