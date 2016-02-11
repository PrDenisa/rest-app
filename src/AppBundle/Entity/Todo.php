<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Todo
 *
 * @ORM\Table(name="todo")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TodoRepository")
 */
class Todo
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    public $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=50, nullable=true)
     */
    public $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    public $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    public $date;
}

