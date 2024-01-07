<?php

namespace App\Repository;

use App\Entity\Monstre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Monstre>
 *
 * @method Monstre|null find($id, $lockMode = null, $lockVersion = null)
 * @method Monstre|null findOneBy(array $criteria, array $orderBy = null)
 * @method Monstre[]    findAll()
 * @method Monstre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MonstreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Monstre::class);
    }

//    /**
//     * @return Monstre[] Returns an array of Monstre objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Monstre
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
    public function findByNamePart($value) {
        $query = $this->getEntityManager()->createQuery("SELECT m FROM App\Entity\Monstre m where m.nom like :value");
        $query->setParameter('value', '%' . $value . '%');
        return $query->getResult();
    }
    public function findMostPowerfulMonsters() {
        $query = $this->getEntityManager()->createQuery(
            "SELECT m FROM App\Entity\Monstre m 
            WHERE m.puissance = (
                SELECT MAX(m2.puissance) FROM App\Entity\Monstre m2
            )"
        );
    
        return $query->getResult();
    }
    public function findMostPowerfulMonstersByRoyaume() {
        $query = $this->getEntityManager()->createQuery(
            "SELECT r.nom AS nomRoyaume, m.nom AS nomMonstre, m.puissance AS puissanceMonstre
            FROM App\Entity\Monstre m
            WHERE m.puissance = (
                SELECT MAX(m2.puissance) FROM App\Entity\Monstre m2 WHERE m2.royaume = m.royaume
            )"
        );
    
        return $query->getResult();
    }
    
   
}
