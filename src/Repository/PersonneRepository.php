<?php

namespace App\Repository;

use App\Entity\Personne;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @method Personne|null find($id, $lockMode = null, $lockVersion = null)
 * @method Personne|null findOneBy(array $criteria, array $orderBy = null)
 * @method Personne[]    findAll()
 * @method Personne[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PersonneRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, EntityManagerInterface $em)
    {
        parent::__construct($registry, Personne::class);
        $this->em = $em;
    }

    public function add($nom, $prenom, $dateNaiss, $age): Personne {
        $pers = new Personne();
        $pers->setNom($nom);
        $pers->setPrenom($prenom);
        $pers->setDateNaissance($dateNaiss);
        $pers->setAge($age);
        $this->em->persist($pers);
        $this->em->flush();

        return $pers;
    }

    public function findByNomPrenomDateNaiss($nom, $prenom, $dateNaiss) {
        return $this->createQueryBuilder('p')
            ->andWhere('p.nom = :nom AND p.prenom = :prenom AND p.dateNaissance = :dateNaiss')
            ->setParameter('nom', $nom)
            ->setParameter('prenom', $prenom)
            ->setParameter('dateNaiss', $dateNaiss)
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return Personne[] Returns an array of Personne objects
    //  */
    
    public function findAllAsc()
    {
        return $this->createQueryBuilder('p')
            ->addOrderBy('p.nom', 'ASC')
            ->addOrderBy('p.prenom', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    // /**
    //  * @return Personne[] Returns an array of Personne objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Personne
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
