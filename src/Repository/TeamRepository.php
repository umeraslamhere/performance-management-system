<?php

namespace App\Repository;

use App\Entity\Team;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Team|null find($id, $lockMode = null, $lockVersion = null)
 * @method Team|null findOneBy(array $criteria, array $orderBy = null)
 * @method Team[]    findAll()
 * @method Team[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TeamRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Team::class);
    }
    

    public function findAllTeams(){
        $teams=$this->findAll();
        return $teams;
    }

    public function myTeams($id , $role){
        if($role=='manager'){
            return $this->createQueryBuilder('team')
                ->andWhere('team.managerId = :val')
                ->setParameter('val', $id)
                ->getQuery()
                ->getResult()
            ;
        }else{
            $conn = $this->getEntityManager()
            ->getConnection();
            $sql = "Select team.name, team.manager_id_id ,user.first_name, user.last_name from team
            join team_user
            join user
            where team.id = team_user.team_id
            and user.id=team.manager_id_id
            and team_user.user_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(1, $id);
            $stmt->execute();
            //dd($stmt->fetchAll());
            return $stmt->fetchAll();
        }
    }
    public function teamMemebers($id){
        
        $conn = $this->getEntityManager()
        ->getConnection();
        $sql = "Select * from user
        join team_user
        where user.id = team_user.user_id
        and team_user.team_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(1, $id);
        $stmt->execute();
        //dd($stmt->fetchAll());
        return $stmt->fetchAll();

    }

    // /**
    //  * @return Team[] Returns an array of Team objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Team
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
