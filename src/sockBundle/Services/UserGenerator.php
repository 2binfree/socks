<?php

/**
 * Created by PhpStorm.
 * User: ubuntu
 * Date: 20/10/16
 * Time: 13:09
 */

namespace sockBundle\Services;

use Symfony\Component\DependencyInjection\Container;

class UserGenerator
{
    /**
     * @var integer
     */
    private $quantity;

    /**
     * UserGenerator constructor.
     * @param $container Container
     * @param $quantity
     */
    public function __construct($container, $quantity)
    {
        $this->quantity = $quantity;
        $this->container = $container;
    }

    /**
     * @return array
     */
    public function getUsers()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.randomuser.me/?nat=fr&results=" . $this->quantity);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = json_decode(curl_exec($ch));
        curl_close($ch);

        return $output->results;
    }
}