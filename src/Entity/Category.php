<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 */
class Category
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
     * @ORM\Column(type="string", length=255)
     */
    private $color;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Task", mappedBy="categories")
     * @ORM\OrderBy({"deadline" = "ASC", "id" = "DESC"})
     */
    private $tasks;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Todo", mappedBy="categories")
     * @ORM\OrderBy({"deadline" = "ASC", "id" = "DESC"})
     */
    private $todos;

    public function __toString() {
        return $this->name;
    }

    public function __construct()
    {
        $this->color = "#494CA2";
        $this->tasks = new ArrayCollection();
        $this->todos = new ArrayCollection();
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

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): self
    {
        $this->color = $color;

        return $this;
    }

    /**
     * @return Collection|Task[]
     */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    public function getTasksPerUser($user)
    {
        $tasks = [];
        $allTasks = $this->tasks;
        foreach ($allTasks as $tsk) {
            if ($tsk->getUsers()->contains($user) || $tsk->getUserGroup()->getUsers()->contains($user)) {
                $tasks[] = $tsk;
            }
        }
        return $tasks;
    }

    public function getTasksCurrentPerUser($user)
    {
        $tasks = [];
        $allTasks = $this->tasks;
        foreach ($allTasks as $tsk) {
            if (!$tsk->getDone() && ( $tsk->getUsers()->contains($user) || $tsk->getUserGroup()->getUsers()->contains($user) )) {
                $tasks[] = $tsk;
            }
        }
        return $tasks;
    }

    public function getTasksDonePerUser($user)
    {
        $tasks = [];
        $allTasks = $this->tasks;
        foreach ($allTasks as $tsk) {
            if ($tsk->getDone() && in_array($user, $tsk->getUsers()->toArray())) {
                $tasks[] = $tsk;
            }
        }
        return $tasks;
    }

    public function addTask(Task $task): self
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks[] = $task;
            $task->addCategory($this);
        }

        return $this;
    }

    public function removeTask(Task $task): self
    {
        if ($this->tasks->contains($task)) {
            $this->tasks->removeElement($task);
            $task->removeCategory($this);
        }

        return $this;
    }

    /**
     * @return Collection|Todo[]
     */
    public function getTodos():Collection
    {
        return $this->todos;
    }

    public function getTodosPerUser($user)
    {
        $todos = [];
        $allTodos = $this->todos;
        foreach ($allTodos as $td) {
            if ($user == $td->getUser()) {
                $todos[] = $td;
            }
        }
        return $todos;
    }

    public function getTodosCurrentPerUser($user)
    {
        $todos = [];
        $allTodos = $this->todos;
        foreach ($allTodos as $td) {
            if (!$td->getDone() && $user == $td->getUser()) {
                $todos[] = $td;
            }
        }
        return $todos;
    }

    public function getTodosDonePerUser($user)
    {
        $todos = [];
        $allTodos = $this->todos;
        foreach ($allTodos as $td) {
            if ($td->getDone() && $user == $td->getUser()) {
                $todos[] = $tsk;
            }
        }
        return $todos;
    }

    public function addTodo(Todo $todo): self
    {
        if (!$this->todos->contains($todo)) {
            $this->todos[] = $todo;
            $todo->addCategory($this);
        }

        return $this;
    }

    public function removeTodo(Todo $todo): self
    {
        if ($this->todos->contains($todo)) {
            $this->todos->removeElement($todo);
            $todo->removeCategory($this);
        }

        return $this;
    }
}
