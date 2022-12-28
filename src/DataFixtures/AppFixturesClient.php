<?php

namespace App\DataFixtures;


use App\Entity\Client;
use App\Repository\CompanyRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use App\DataFixtures\AppFixturesOrganisation;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixturesClient extends Fixture implements DependentFixtureInterface
{
    public function __construct(CompanyRepository $companyRespository)
    {
        $this->companyRespository = $companyRespository;
    }

    public function load(ObjectManager $manager,): void
    {
        $companies = $this->companyRespository->findAll();
        $firstNameList = array("Gabriel", "Léo", "Raphael", "Louis", "Arthur", "Jules", "Mael", "jade", "Noah", "Ambre", "Lucas", "Hugo", "Alice", "Gabin", "Rose", "Mia", "Léon");
        $lastNameList = array("Martin", "Bernard", "Thomas", "Robert", "Richard", "Petit", "Leroy", "Moreau", "Morel", "Fournier", "Girard", "Mercier", "Blanc", "Boyer", "Lopez", "Jacob", "Monnier");

        foreach ($companies as $company) {
           
            for($i = 0; $i <= 9 ; $i++)
            {
                $firstname = array_rand($firstNameList, 1);
                $lastname = array_rand($lastNameList, 1);
                $client = new Client();
                $client->setFirstName($firstNameList[$firstname]);
                $client->setLastName($lastNameList[$lastname]);
                $client->setCompany($company);
                $manager->persist($client);
                $manager->flush();
            }
        }
    }

    public function getDependencies()
    {
        return [
            AppFixturesOrganisation::class,
        ];
    }

}
