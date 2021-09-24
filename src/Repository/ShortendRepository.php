<?php

namespace App\Repository;

use App\Entity\Shortend;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Shortend|null find($id, $lockMode = null, $lockVersion = null)
 * @method Shortend|null findOneBy(array $criteria, array $orderBy = null)
 * @method Shortend[]    findAll()
 * @method Shortend[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShortendRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Shortend::class);
    }

    public function transform(Shortend $se)
    {
        return [
                'id'    => (int) $se->getId(),
                'url' => (string) $se->getUrl(),
                'hash' => (string) $se->getHash(),
                'tinyurl' => (string) $se->getTinyurl(),
                'created_at' => (string) $se->getCreatedAt()
        ];
    }

    public function transformAll()
    {
        $shortends = $this->findAll();
        $shortendsArray = [];

        foreach ($shortends as $se) {
            $shortendsArray[] = $this->transform($se);
        }

        return $shortendsArray;
    }

    // /**
    //  * @return Shortend[] Returns an array of Shortend objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Shortend
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
