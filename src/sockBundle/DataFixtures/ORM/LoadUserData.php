<?php
/**
 * Created by PhpStorm.
 * User: ubuntu
 * Date: 20/10/16
 * Time: 16:58
 */

namespace sockBundle\DataFixtures\ORM;


use sockBundle\Entity\User;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Container;

class LoadUserData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * @var Container
     */
    private $container;
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $users = $this->container->get('sock.user_generator')->getUsers();
        $cpt = 0;
        foreach ($users as $user){
            $cpt++;
            $userEntity = new User();
            $userEntity->setFirstname($user->name->first);
            $userEntity->setLastname($user->name->last);
            $userEntity->setEmail($user->email);
            $userEntity->setCountry($user->nat);
            $manager->persist($userEntity);
            unset($userEntity);
            if ($cpt % 10 == 0){
                $manager->flush();
            }
        }
        $manager->flush();
    }
    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 3;
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}