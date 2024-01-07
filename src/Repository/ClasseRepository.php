<?php

namespace App\Repository;

use App\Entity\Classe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Classe>
 *
 * @method Classe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Classe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Classe[]    findAll()
 * @method Classe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClasseRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {

        parent::__construct($registry, Classe::class);
    }

//    /**
//     * @return Classe[] Returns an array of Classe objects
//     */
// public function findDetailsByClasse($id): array {
//     return $this->createQueryBuilder('m')
//         ->select('m.libelle')
//         ->innerJoin('m.classe_module', 'cm')
//         ->andWhere('cm.classe_id = :classeId')
//         ->setParameter('classeId', $id)
//         ->getQuery()
//         ->getResult();
// }
        public function findDistinctClasseLibelle(): array
        {  
                $entityManager = $this->getEntityManager();
                $query = $entityManager->createQuery(
                    'SELECT c.id,c.libelle
                    FROM App\Entity\Classe c
                    ORDER BY c.libelle ASC'
                    );
                // dd($query->getResult());
                return $query->getResult();
        }
        
//    public function findOneBySomeField($value): ?Classe
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
