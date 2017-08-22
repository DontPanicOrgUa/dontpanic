<?php

namespace WebBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(name="customer_profit")
 * @ORM\Entity(repositoryClass="WebBundle\Repository\CustomerProfitRepository")
 */
class CustomerProfit
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id", type="integer")
     */
    private $id;

    /**
     * @ORM\Column()
     */
    private $amount;

    private $currency;

    private $user;

    private $discount;
}
