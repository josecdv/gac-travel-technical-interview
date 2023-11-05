<?php
namespace App\Controller;

use App\Form\Type\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Categories;
use Symfony\Component\HttpFoundation\Request;


#[Route('/category')]
class CategoryController extends AbstractController
{
    #[Route('/add')]
    public function add(Request $request, EntityManagerInterface $entityManager): Response
    {
        return $this->processForm(new Categories(), $request, $entityManager);
    }

    #[Route('/list', name: 'list-categories')]
    public function list(EntityManagerInterface $entityManager): Response
    {
        $categories = $entityManager->getRepository(Categories::class)->findAll();

        return $this->render('categories.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/delete/{id}')]
    public function delete(int $id, EntityManagerInterface $entityManager): Response
    {
        $category = $entityManager->getRepository(Categories::class)->find($id);
        $entityManager->remove($category);
        $entityManager->flush();

        return $this->redirectToRoute('list-categories');
    }

    #[Route('/edit/{id}')]
    public function edit(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $category = $entityManager->getRepository(Categories::class)->find($id);

        return $this->processForm($category, $request, $entityManager);
    }

    private function processForm(Categories $category, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CategoryType::class, $category);
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $category = $form->getData();
            $category->setCreatedAt(new \DateTime());

            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirectToRoute('list-categories');
        }
        
        return $this->renderForm('add-category.html.twig', [
            'form' => $form,
            'category' => $category
        ]);
    }
}