<?php

namespace PiotrK\ClientBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;

/**
 * Client
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="PiotrK\ClientBundle\Entity\ClientRepository")
 */
class Client {

  public function __construct() {
    $this->orders = new ArrayCollection();
  }

  /**
   * @var integer
   *
   * @ORM\Column(name="id", type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  private $id;

  /**
   * @ORM\Column(type="string", length=255)
   */
  private $name;

  /**
   * @ORM\Column(type="string", length=255)
   */
  private $lastName;

  /**
   * @ORM\OneToMany(targetEntity="ClientOrder", mappedBy="client", cascade={"all"}, orphanRemoval=true)
   */
  private $orders = array();
  private $totalCost;

  /**
   * Get id
   *
   * @return integer 
   */
  public function getId() {
    return $this->id;
  }

  /**
   * Set name
   *
   * @param string $name
   * @return Client
   */
  public function setName($name) {
    $this->name = $name;

    return $this;
  }

  /**
   * Get name
   *
   * @return string 
   */
  public function getName() {
    return $this->name;
  }

  /**
   * Set lastName
   *
   * @param string $lastName
   * @return Client
   */
  public function setLastName($lastName) {
    $this->lastName = $lastName;

    return $this;
  }

  /**
   * Get lastName
   *
   * @return string 
   */
  public function getLastName() {
    return $this->lastName;
  }

  /**
   * Add orders
   *
   * @param \PiotrK\ClientBundle\Entity\ClientOrder $orders
   * @return Client
   */
  public function addOrder(\PiotrK\ClientBundle\Entity\ClientOrder $orders) {
    $this->orders[] = $orders;

    return $this;
  }

  /**
   * Remove orders
   *
   * @param \PiotrK\ClientBundle\Entity\ClientOrder $orders
   */
  public function removeOrder(\PiotrK\ClientBundle\Entity\ClientOrder $orders) {
    $this->orders->removeElement($orders);
  }

  /**
   * Get orders
   *
   * @return \Doctrine\Common\Collections\Collection 
   */
  public function getOrders() {
    return $this->orders;
  }

  public function getTotalCost() {
    $total = 0;
    foreach ($this->orders as $order) {
      $total = $total + $order->getCost();
    }
    return (float) $total;
  }

  public function maxCostOrders() {
    $iterator = $this->orders->getIterator();
    $iterator->uasort(function ($first, $second) {
      return (float) $first->getCost() < (float) $second->getCost() ? 1 : -1;
    });
    return $iterator;
  }

}
