<?php
/**
 * Created by PhpStorm.
 * User: dmitriyt
 * Date: 2019-06-18
 * Time: 21:59
 */

namespace App\Security;


use App\Entity\Device;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class ApiKeyUserProvider implements UserProviderInterface
{
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getUsernameForApiKey($apiKey)
    {
        // Look up the username based on the token in the database, via
        // an API call, or do something entirely different
//        $username = $apiKey;

        return $apiKey;
    }

    public function loadUserByUsername($username)
    {

        $devices = $this->em->getRepository(Device::class)->findBy([
            'token' => $username,
        ]);
        if (count($devices) == 1) {
            $client = $devices[0]->getUser();
            return $client;
        } else {
            throw new BadCredentialsException();
        }
    }

    public function refreshUser(UserInterface $user)
    {
        // this is used for storing authentication in the session
        // but in this example, the token is sent in each request,
        // so authentication can be stateless. Throwing this exception
        // is proper to make things stateless
        return $user;
    }

    public function supportsClass($class)
    {
        return User::class === $class;
    }
}