<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProjectRepository")
 */
class Project
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="projects")
     */
    private $manager;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserGroup", mappedBy="project")
     */
    private $userGroups;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Task", mappedBy="project")
     */
    private $tasks;

    public function __toString() {
        return $this->name;
    }

    public function __construct()
    {
        $this->userGroups = new ArrayCollection();
        $this->tasks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getManager(): ?User
    {
        return $this->manager;
    }

    public function setManager(?User $manager): self
    {
        $this->manager = $manager;

        return $this;
    }

    /**
     * @return Collection|UserGroup[]
     */
    public function getUserGroups(): Collection
    {
        return $this->userGroups;
    }

    public function addUserGroup(UserGroup $userGroup): self
    {
        if (!$this->userGroups->contains($userGroup)) {
            $this->userGroups[] = $userGroup;
            $userGroup->setProject($this);
        }

        return $this;
    }

    public function removeUserGroup(UserGroup $userGroup): self
    {
        if ($this->userGroups->contains($userGroup)) {
            $this->userGroups->removeElement($userGroup);
            // set the owning side to null (unless already changed)
            if ($userGroup->getProject() === $this) {
                $userGroup->setProject(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Task[]
     */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    public function getTasksManagerCurrent()
    {
        $tasks = [];
        foreach($this->tasks as $tsk) {
            if (!$tsk->getDone()) {
                $tasks[] = $tsk; 
            }
        }
        return $tasks;
    }

    public function getTasksManagerDone()
    {
        $tasks = [];
        foreach($this->tasks as $tsk) {
            if ($tsk->getDone()) {
                $tasks[] = $tsk; 
            }
        }
        return $tasks;
    }

    public function getTasksUserCurrent($user)
    {
        $tasks = [];
        $allTasks = $this->tasks;

        foreach ($allTasks as $tsk) {
            if (empty($tsk->getUserGroup())) {
                if (!$tsk->getDone() && ( $tsk->getUsers()->contains($user) )) {
                    $tasks[] = $tsk;
                }
            } else if (!$tsk->getDone() && ( $tsk->getUsers()->contains($user) || $tsk->getUserGroup()->getUsers()->contains($user) )) {
                $tasks[] = $tsk;
            }
        }
        return $tasks;
    }

    public function getTasksUserDone($user)
    {
        $tasks = [];
        $allTasks = $this->tasks;

        foreach ($allTasks as $tsk) {
            if (empty($tsk->getUserGroup())) {
                if ($tsk->getDone() && ( $tsk->getUsers()->contains($user) )) {
                    $tasks[] = $tsk;
                }
            } else if ($tsk->getDone() && ( $tsk->getUsers()->contains($user) || $tsk->getUserGroup()->getUsers()->contains($user) )) {
                $tasks[] = $tsk;
            }
        }
        return $tasks;
    }

    public function addTask(Task $task): self
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks[] = $task;
            $task->setProject($this);
        }

        return $this;
    }

    public function removeTask(Task $task): self
    {
        if ($this->tasks->contains($task)) {
            $this->tasks->removeElement($task);
            // set the owning side to null (unless already changed)
            if ($task->getProject() === $this) {
                $task->setProject(null);
            }
        }

        return $this;
    }
}
