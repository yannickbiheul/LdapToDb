<?php

namespace App\Repository;

use App\Entity\NumberRecord;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<NumberRecord>
 *
 * @method NumberRecord|null find($id, $lockMode = null, $lockVersion = null)
 * @method NumberRecord|null findOneBy(array $criteria, array $orderBy = null)
 * @method NumberRecord[]    findAll()
 * @method NumberRecord[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NumberRecordRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NumberRecord::class);
    }

    public function save(NumberRecord $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(NumberRecord $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findGreenList(): array
    {
        return $this->createQueryBuilder('n')
                    ->andWhere('n.private = :val')
                    ->setParameter('val', 'LV')
                    ->orderBy('n.id', 'ASC')
                    ->getQuery()
                    ->getResult()
                    ;
    }

//    /**
//     * @return NumberRecord[] Returns an array of NumberRecord objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('n')
//            ->andWhere('n.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('n.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?NumberRecord
//    {
//        return $this->createQueryBuilder('n')
//            ->andWhere('n.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
