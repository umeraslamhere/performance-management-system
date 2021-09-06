<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(UserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    public function findUserBy($options){
        $user=$this->findOneBy($options);
        return $user;
    }

    public function findAllUsers(){
        $users=$this->findAll();
        return $users;
    }
    
    public function findAllUsersBy($options){
        $users=$this->findBy($options);
        return $users;
    }

    public function removeUser($id){
        $em = $this->getEntityManager();
        $user = $this->findOneBy(['id' => $id]);
        $em->remove($user);
        $em->flush();
    }

    public function myManagers($id){

        /* return $this->getEntityManager()
        ->createQuery('
            SELECT user FROM user WHERE user.id IN (SELECT team.manager_id_id
            FROM team 
            WHERE team.id IN (SELECT id 
            FROM team_user 
            WHERE team_user.user_id ='.$id.'))
        ')
        ->getSQL(); */




        /* 
        GIVES NULL RESULT
        $rsm = new ResultSetMapping();
        // build rsm here
        $em = $this->getEntityManager();

        $query = $em->createNativeQuery('SELECT * FROM user WHERE user.id IN (SELECT team.manager_id_id
        FROM team 
           WHERE team.id IN (SELECT id 
        FROM team_user 
        WHERE team_user.user_id = id))', $rsm);
        $query->setParameter('id', $id);

        $users = $query->getResult();
        dd($users); */


        $conn = $this->getEntityManager()
        ->getConnection();
        $sql = "SELECT * FROM user WHERE user.id IN (SELECT team.manager_id_id
        FROM team 
           WHERE team.id IN (SELECT id 
        FROM team_user 
        WHERE team_user.user_id = ?))";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(1, $id);
        $stmt->execute();
        //dd($stmt->fetchAll());
        return $stmt->fetchAll();


        /* $em = $this->getEntityManager();
        $expr = $em->getExpressionBuilder();
        return $em->createQueryBuilder()
        ->from('user', 'u')
        ->where(
            $expr->in(
                'u.id',
                $em->createQueryBuilder()
                    ->select('t.manager_id_id')
                    ->from('team', 't')
                    ->where(
                        $expr->in(
                            't.id',
                            $em->createQueryBuilder()
                                ->select('tuser.user_id')
                                ->from('team_user', 'tuser')
                                ->where('tuser.user_id = :id')
                                ->setParameter('id', $id)
                                ->getQuery()
                                ->getResult()
                        )
                    )
            )
        ); */
    }

    public function mySubordinates($id){
        
        $conn = $this->getEntityManager()
        ->getConnection();
        $sql = "SELECT * FROM user WHERE user.id IN (SELECT team_user.user_id
        FROM team_user 
        WHERE team_user.team_id IN (SELECT id 
              FROM team
              WHERE team.manager_id_id = ?))";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(1, $id);
        $stmt->execute();
        //dd($stmt->fetchAll());
        return $stmt->fetchAll();
    }

    

    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
        ->select
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
