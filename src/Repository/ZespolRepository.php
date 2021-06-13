<?php

namespace App\Repository;

use App\Entity\Zespol;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Zespol|null find($id, $lockMode = null, $lockVersion = null)
 * @method Zespol|null findOneBy(array $criteria, array $orderBy = null)
 * @method Zespol[]    findAll()
 * @method Zespol[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ZespolRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Zespol::class);
    }

    public function transform(Zespol $zespol) {
        return [
            'id' => (int) $zespol->getId(),
            'title' => (string) $zespol->getTitle(),
            'count' => (int) $zespol->getCount()
        ];
    }

    public function transformAll() {
        $zespoly = $this->findAll();
        $zespolyArray = [];

        foreach ($zespoly as $zespol) {
            $zespolyArray[] = $this->transform($zespol);
        }

        return $zespolyArray;
    }

    // /**
    //  * @return Zespol[] Returns an array of Zespol objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('z')
            ->andWhere('z.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('z.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Zespol
    {
        return $this->createQueryBuilder('z')
            ->andWhere('z.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
