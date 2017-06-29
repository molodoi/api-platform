<?php

namespace AppBundle\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Task
 * @ApiResource(
 *     attributes={
 *          "filters"={"task.search","task.range","task.order", "task.date"},
 *          "normalization_context"={"groups"={"read", "task"}},
 *          "denormalization_context"={"groups"={"write"}},
 *          "pagination_items_per_page"=15
 *     },
 *     itemOperations={
 *          "get"={"method"="GET"},
 *          "delete"={"method"="DELETE"},
 *          "put"={"method"="PUT", "denormalization_context"={"groups"={"put"}}}
 *     } *
 * )
 * ApiResource(attributes={"filters"={"generic.search","generic.range","generic.order", "generic.date"}})
 * @ORM\Table(name="task")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TaskRepository")
 */
class Task
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"read"})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     * @Groups({"read", "write"})
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="time", type="string", length=255)
     * @Groups({"read", "write", "put"})
     * @Assert\NotBlank()
     * @Assert\Choice(choices = {"10", "20", "30", "40", "50", "60"}, message = "Choose a valid time.")
     */
    private $time;

    /**
     * @var string
     *
     * @ORM\Column(name="priority", type="integer")
     * @Groups({"read", "write", "put"})
     * @Assert\NotBlank()
     * @Assert\Range(
     *      min = 0,
     *      max = 10,
     *      minMessage = "You must be at least {{ limit }}",
     *      maxMessage = "You cannot be taller than {{ limit }}"
     * )
     */
    private $priority;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @Groups({"read", "write", "task"})
     * @Assert\NotBlank()
     */
    public $user;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     * @Groups({"read"})
     */
    public $createdAt;


    public function __construct()
    {
        $this->createdAt= new \DateTime();
    }


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Task
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set time
     *
     * @param string $time
     *
     * @return Task
     */
    public function setTime($time)
    {
        $this->time = $time;

        return $this;
    }

    /**
     * Get time
     *
     * @return string
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Set priority
     *
     * @param string $priority
     *
     * @return Task
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * Get priority
     *
     * @return string
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @ORM\PreUpdate()
     */
    public function preUpdate()
    {
        $this->createdAt = new \DateTime();
    }
}

