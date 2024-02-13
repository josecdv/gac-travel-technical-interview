<?php

namespace App\DataFixtures;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpClient\Exception\TransportException;
use App\Entity\Users;
use App\Controller\UsersController;
use App\Entity\Categories;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;



class AppFixtures extends Fixture
{
    private $client;
    private $usersController;
    private $flashBag;

    public function __construct(UsersController $usersController, FlashBagInterface $flashBag)
    {
        $this->client = HttpClient::create();
        $this->usersController = $usersController;
        $this->flashBag = $flashBag;
    }

    public function loadUsers(ObjectManager $manager): array
    {
        $users = [];
        //int $count = 1;
        for ($i = 0; $i < 1; $i++) {
            try {
                            
                $response = $this->client->request('GET', 'https://fakestoreapi.com/users/');
                $statusCode = $response->getStatusCode();
                if ($statusCode == 200) {
                    $content = $response->toArray();
                    $randomUsers = array_rand($content);
                    if ($content !== null) {
                        //print_r($content[0]['username'] . "\n");
                         $user = new Users();
                         $user->setUsername($content[$randomUsers]['username']);
                         $user->setPassword($content[$randomUsers]['password']);
                         
                         // Check if the username already exists
                        $existingUser = $manager->getRepository(Users::class)->findOneBy(['username' => $user->getUsername()]);
                        if ($existingUser) {
                            // Add a flash message for the error
                            $this->flashBag->add('error', 'Username already exists.');
                            continue;
                        }
                         
                         $users[] = $user;
                    }
                }
            } catch (TransportException $e) {
                // handle exception
            }
        }
        return $users;
    }

    // public function loadCategories(int $count = 10): array
    // {
    //     // $categories = [];
    //     // for ($i = 0; $i < $count; $i++) {
    //     //     try {
    //     //         $response = $this->client->request('GET', 'https://fakeapi.com/categories/1' . $i);
    //     //         $statusCode = $response->getStatusCode();
    //     //         if ($statusCode == 200) {
    //     //             $content = $response->toArray();
    //     //             $category = new Categories();
    //     //             $category->setName($content['name']);
    //     //             $categories[] = $category;
    //     //         }
    //     //     } catch (TransportException $e) {
    //     //         // handle exception
    //     //     }
    //     // }
    //     // return $categories;
    // }

    public function load(ObjectManager $manager)
    {
        $users = $this->loadUsers($manager);
        foreach ($users as $user) {
            $manager->persist($user);
        }

        // $categories = $this->loadCategories(5);
        // foreach ($categories as $category) {
        //     $manager->persist($category);
        // }

        $manager->flush();
    }
}
