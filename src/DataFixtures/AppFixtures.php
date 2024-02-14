<?php
namespace App\DataFixtures;

use App\Entity\Categories;
use App\Entity\Products;
use App\Entity\Users;
use App\Controller\UsersController;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpClient\Exception\TransportException;

class AppFixtures extends Fixture
{
    private $client;
    private $usersController;
    private $flashBag;
    private string $entityType;

    public function __construct(UsersController $usersController, FlashBagInterface $flashBag)
    {
        $this->client = HttpClient::create();
        $this->usersController = $usersController;
        $this->flashBag = $flashBag;
    }

    public function setEntityType(string $entityType): void
    {
        $this->entityType = $entityType;
    }

    public function load(ObjectManager $manager): void
    {
        if ($this->entityType === null) {
            throw new \RuntimeException("Entity type has not been set.");
        }

        switch ($this->entityType) {
            case 'Users':
                $entities = $this->loadUsers( $manager);
                break;
            case 'Categories':
                $entities = $this->loadCategories( $manager);
                break;
            case 'Products':
                $entities = $this->loadProducts( $manager);
                break;
            // add more cases for other entities
            default:
                throw new \InvalidArgumentException("Entity type '$this->entityType' is not supported.");
        }

        foreach ($entities as $entity) {
            $manager->persist($entity);
        }

        $manager->flush();
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

    public function loadCategories(ObjectManager $manager): array
    {
        $categories = [];
        for ($i = 0; $i < 1; $i++) 
        {
            try {
                $response = $this->client->request('GET', 'https://fakestoreapi.com/products/categories');
                $statusCode = $response->getStatusCode();
                if ($statusCode == 200) {
                    $content = $response->toArray();
					$randomCategories = array_rand($content);
                    if ($content !== null) {
						$category = new Categories();
						$category->setName($content[$randomCategories]);
						 
                         // Check if the Category already exists
                        $existingCategoria = $manager->getRepository(Categories::class)->findOneBy(['name' => $category->getName()]);
                        if ($existingCategoria) {
                            // Add a flash message for the error
                            $this->flashBag->add('error', 'Category already exists.');
                            continue;
                        }						
						
                        $categories[] = $category;
                    }
                }
            } catch (TransportException $e) {
                // handle exception
            }
        }
        return $categories;
    }

    public function loadProducts(ObjectManager $manager): array
    {
       
        $products = [];
        $categories = $manager->getRepository(Categories::class)->findAll();
        for ($i = 0; $i < 1; $i++) 
        {
            try {
                $response = $this->client->request('GET', 'https://fakestoreapi.com/products');
                $statusCode = $response->getStatusCode();
                if ($statusCode == 200) {
                    $content = $response->toArray();
                    //$randomProduct = $content[array_rand($content)];
					$randomProduct = array_rand($content);
                    //$productCategory = $randomProduct['category'];
					$productCategory = $content[$randomProduct]['category'];
					$title = $content[$randomProduct]['title'];

                    //$existingCategoria = $manager->getRepository(Categories::class)->findOneBy(['name' => $productCategory]);
                    $existingCategoria = null;
                    foreach ($categories as $category) {
                        if ($category->getName() === $productCategory) {
                            $existingCategoria = $category;
                            break;
                        }
                    }
                    
                    if ($content !== null && $existingCategoria) {
                        
						$category = new Categories();
                        $product = new Products();
						$product->setName($title);
                        $product->setCategory($existingCategoria);
                    
                        $products[] = $product;
                    }
                }
            } catch (TransportException $e) {
                // handle exception
            }
        }
        return $products;
    }


}