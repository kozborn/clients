<?php

namespace PiotrK\ClientBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * ClientOrder
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="PiotrK\ClientBundle\Entity\ClientOrderRepository")
 */
class ClientOrder
{

    public function __construct(){
        $this->products = new ArrayCollection();
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
     * @ORM\ManyToOne(targetEntity="Client", cascade={"persist"})
     * @ORM\JoinColumn(name="client_id", referencedColumnName="id")
     */
    private $client;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created;

    private $cost;

    /**
     * @ORM\OneToMany(targetEntity="Product", mappedBy="order", cascade={"all"})
     * */
    private $products = array();

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return ClientOrder
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime 
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set client
     *
     * @param \PiotrK\ClientBundle\Entity\Client $client
     * @return ClientOrder
     */
    public function setClient(\PiotrK\ClientBundle\Entity\Client $client = null)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Get client
     *
     * @return \PiotrK\ClientBundle\Entity\Client 
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Add products
     *
     * @param \PiotrK\ClientBundle\Entity\Product $products
     * @return ClientOrder
     */
    public function addProduct(\PiotrK\ClientBundle\Entity\Product $products)
    {
        $this->products[] = $products;

        return $this;
    }

    /**
     * Remove products
     *
     * @param \PiotrK\ClientBundle\Entity\Product $products
     */
    public function removeProduct(\PiotrK\ClientBundle\Entity\Product $products)
    {
        $this->products->removeElement($products);
    }

    /**
     * Get products
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProducts()
    {
        return $this->products;
    }

    public function getCost(){
        $cost = 0;
        foreach ($this->products as $product){
            $cost =+ $product->getCost();
        }
        return $cost;
    }
}
