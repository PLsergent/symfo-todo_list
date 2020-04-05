<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Project;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Project|null find($id, $lockMode = null, $lockVersion = null)
 * @method Project|null findOneBy(array $criteria, array $orderBy = null)
 * @method Project[]    findAll()
 * @method Project[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Project::class);
    }

    public function getProjects(User $user)
    {
        $projects = [];

        $userTasks = $user->getTasks()->toArray();
        foreach($user->getUserGroups() as $group) {
            $userTasks = array_merge($userTasks, $group->getTasksCurrent());
        }
        
        // Add if assign to a task of a project (put manage project first)
        foreach($userTasks as $tsk) {
            $proj = $tsk->getProject();
            if (!in_array($proj, $projects) && $user == $proj->getManager()) {
                array_unshift($projects, $proj);
            } else if (!in_array($proj, $projects)) {
                $projects[] = $proj;
            }
        }

        // Add if manager of a project (put manage project first)
        foreach($this->findAll() as $p) {
            if ($user == $p->getManager() && !in_array($p, $projects)) {
                array_unshift($projects, $p);
            }
        }
        return $projects;
    }

    public function getTasksManagerCurrent()
    {
        $projectsTasks = [];
        $allProjects = $this->findAll();

        foreach ($allProjects as $project) {
            $currentTasks = $project->getTasksManagerCurrent();
            if (!empty($currentTasks)) {
                $projectsTasks[$project->getId()] = $currentTasks;
            }
        }
        return $projectsTasks;
    }

    public function getTasksManagerDone()
    {
        $projectsTasks = [];
        $allProjects = $this->findAll();

        foreach ($allProjects as $project) {
            $doneTasks = $project->getTasksManagerDone();
            if (!empty($doneTasks)) {
                $projectsTasks[$project->getId()] = $doneTasks;
            }
        }
        return $projectsTasks;
    }

    public function getTasksUserCurrent(User $user)
    {
        $projectsTasks = [];
        $allProjects = $this->findAll();

        foreach ($allProjects as $project) {
            $currentTasks = $project->getTasksUserCurrent($user);
            if (!empty($currentTasks)) {
                $projectsTasks[$project->getId()] = $currentTasks;
            }
        }
        return $projectsTasks;
    }

    public function getTasksUserDone(User $user)
    {
        $projectsTasks = [];
        $allProjects = $this->findAll();

        foreach ($allProjects as $project) {
            $doneTasks = $project->getTasksUserDone($user);
            if (!empty($doneTasks)) {
                $projectsTasks[$project->getId()] = $doneTasks;
            }
        }
        return $projectsTasks;
    }

    // /**
    //  * @return Project[] Returns an array of Project objects
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
    public function findOneBySomeField($value): ?Project
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
