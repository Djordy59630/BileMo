<?php

namespace App\Repository;

use App\Entity\Phone;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Phone>
 *
 * @method Phone|null find($id, $lockMode = null, $lockVersion = null)
 * @method Phone|null findOneBy(array $criteria, array $orderBy = null)
 * @method Phone[]    findAll()
 * @method Phone[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PhoneRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Phone::class);
    }

    public function save(Phone $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Phone $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    // return Phone list for Api 
    public function apiFindAll(): array
    {
        $qb = $this->createQueryBuilder('p')
            ->select('p.id','p.name','p.price','p.storage','p.screenSize','p.weight','p.pictureResolution','p.simCard','p.refurbished','p.guaranteed');
            $query = $qb->getQuery();
            return $query->execute();
    }

   public function apiFindOneBy($id): array
   {
       return $this->createQueryBuilder('p')
            ->select('p.id','p.name','p.price','p.storage','p.screenSize','p.weight','p.pictureResolution','p.simCard','p.refurbished','p.guaranteed')
            ->andWhere('p.id = :val')
            ->setParameter('val', $id)
            ->getQuery()
            ->getOneOrNullResult()
       ;
   }
}
