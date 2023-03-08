<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Repository\CustomerRepository;
use Doctrine\Persistence\ObjectManager;
use App\DataFixtures\AppFixturesCustomer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixturesUser extends Fixture
{
    public function __construct(UserPasswordHasherInterface $userPasswordHasher, CustomerRepository $customerRepository)
    {
        $this->userPasswordHasher = $userPasswordHasher;
        $this->customerRepository = $customerRepository;

    }

    public function load(ObjectManager $manager,): void
    {
        $customers = $this->customerRepository->findAll();

        foreach ($customers as $customer)
        {
            $userId = $customer->getId();
            
            for($i = 0; $i <= 3 ; $i++)
            {
                $user = new User();
                $user->setEmail('user-' . $i . $userId .'@test.com');
                $user->setPassword($this->userPasswordHasher->hashPassword($user, 'user-'. $i . $userId));
                $user->setRoles(['ROLE_CLIENT']);
                $user->setCustomer($customer);
                $manager->persist($user);
                $manager->flush();
            }

            for($i = 0; $i <= 3 ; $i++)
            {
                $user = new User();
                $user->setEmail('admin-' . $i . $userId .'@test.com');
                $user->setPassword($this->userPasswordHasher->hashPassword($user, 'admin-'. $i . $userId));
                $user->setRoles(['ROLE_ADMIN']);
                $user->setCustomer($customer);
                $manager->persist($user);
                $manager->flush();
            }

            for($i = 0; $i <= 1 ; $i++)
            {
                $user = new User();
                $user->setEmail('superadmin-' . $i . $userId .'@test.com');
                $user->setPassword($this->userPasswordHasher->hashPassword($user, 'superadmin-'. $i . $userId));
                $user->setRoles(['ROLE_SUPER_ADMIN']);
                $user->setCustomer($customer);
                $manager->persist($user);
                $manager->flush();
            }

        }
    }

    public function getDependencies()
    {
        return [
            AppFixturesCustomer::class,
        ];
    }
}
