<?php

namespace App\DataFixtures;

use App\Entity\Company;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixturesOrganisation extends Fixture
{
    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function load(ObjectManager $manager,): void
    {
        for($i = 0; $i <= 9 ; $i++)
        {
            $company = new Company();
            $company->setEmail('organisation-' . $i .'@test.com');
            $company->setPassword($this->userPasswordHasher->hashPassword($company, 'Organisation-'. $i));
            $company->setRoles(['ROLE_ORGANISATION']);
            $manager->persist($company);
            $manager->flush();
        }
    }
}
