<?php

namespace App\DataFixtures;


use App\Entity\Phone;
use App\Repository\CompanyRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use App\DataFixtures\AppFixturesOrganisation;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixturesPhone extends Fixture implements DependentFixtureInterface
{
    public function __construct(CompanyRepository $companyRespository)
    {
        $this->companyRespository = $companyRespository;
    }

    public function load(ObjectManager $manager,): void
    {
        $companies = $this->companyRespository->findAll();
        $phoneNameList = array("Xiaomi", "Huawei", "Realme", "OPPO", "Alcatel", "OnePlus", "Google Pixel");
        $simNameList = array("Standard", "Mini SIM", "Micro SIM", "Nano SIM", "E SIM");

        foreach ($companies as $company) {
           
            for($i = 0; $i <= 9 ; $i++)
            {
                $phoneName = array_rand($phoneNameList, 1);
                $simName = array_rand($simNameList, 1);
                $phone = new Phone();
                $phone->setName($phoneNameList[$phoneName] . ' ' . mt_rand(1, 9));
                $phone->setPrice(mt_rand(100, 3000));
                $phone->setStorage(mt_rand(10, 100));
                $phone->setScreenSize(mt_rand(6, 12));
                $phone->setPictureResolution(mt_rand(10, 100));
                $phone->setSimCard($simNameList[$simName]);
                $phone->setWeight(mt_rand(100, 300));
                $phone->setRefurbished(random_int(0, 1));
                $phone->setGuaranteed(mt_rand(1, 5));
                $phone->setCompany($company);
                $manager->persist($phone);
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
