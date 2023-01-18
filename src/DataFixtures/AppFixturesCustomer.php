<?php

namespace App\DataFixtures;


use App\Entity\Customer;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;


class AppFixturesCustomer extends Fixture
{
 
    public function load(ObjectManager $manager,): void
    {
        $nameList = array("Martin", "Bernard", "Thomas", "Robert", "Richard", "Petit", "Leroy", "Moreau", "Morel", "Fournier", "Girard", "Mercier", "Blanc", "Boyer", "Lopez", "Jacob", "Monnier");

            for($i = 0; $i <= 9 ; $i++)
            {
                $name = array_rand($nameList, 1);
                $customer = new Customer();
                $customer->setName($nameList[$name]);
                $manager->persist($customer);
                $manager->flush();
            }
    }
}
