<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserGroupRepository")
 */
class UserGroup
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
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="userGroups")
     */
    private $users;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Task", mappedBy="userGroup")
     */
    private $tasks;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Project", inversedBy="userGroups")
     */
    private $project;

    public function __toString() {
        return $this->name;
    }

    public function __construct($project)
    {
        $this->users = new ArrayCollection();
        $this->tasks = new ArrayCollection();
        $this->project = $project;
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

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->addUserGroup($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            $user->removeUserGroup($this);
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

    public function getTasksCurrent()
    {
        $tasks = [];
        $allTasks = $this->tasks;
        foreach ($allTasks as $tsk) {
            if (!$tsk->getDone()) {
                $tasks[] = $tsk;
            }
        }
        return $tasks;
    }

    public function getTasksDone()
    {
        $tasksDone = [];
        $allTasks = $this->tasks;
        foreach ($allTasks as $tsk) {
            if ($tsk->getDone()) {
                $tasksDone[] = $tsk;
            }
        }
        return $tasksDone;
    }

    public function addTask(Task $task): self
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks[] = $task;
            $task->setUserGroup($this);
        }

        return $this;
    }

    public function removeTask(Task $task): self
    {
        if ($this->tasks->contains($task)) {
            $this->tasks->removeElement($task);
            // set the owning side to null (unless already changed)
            if ($task->getUserGroup() === $this) {
                $task->setUserGroup(null);
            }
        }

        return $this;
    }

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(?Project $project): self
    {
        $this->project = $project;

        return $this;
    }
}
