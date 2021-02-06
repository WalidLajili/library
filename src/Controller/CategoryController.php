<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class CategoryController extends AbstractController
{
    /**
     * @Route("/category/{id}", name="category")
     */
    public function category(int $id): Response
    {
        $user = $this->getUser();

        $category = $this->getDoctrine()
            ->getRepository(Category::class)
            ->find($id);

        $repository = $this->getDoctrine()->getRepository(Book::class);

        $books = $repository->findBy(['category' => $category]);
        $categories = $repository = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findAll();

        return $this->render('home.html.twig', [
            'user' => $user,
            'books' => $books,
            'categories' => $categories,
            'selectedCategory' => $category,
        ]);
    }

    /**
     * @Route("/admin/category", name="admin-category")
     */
    public function adminCategory(): Response
    {
        $user = $this->getUser();
        if (!$user || !in_array('admin', $user->getRoles())) {
            throw $this->createNotFoundException('Page not found');
        }

        $repository = $this->getDoctrine()->getRepository(Category::class);
        $categories = $repository->findAll();

        return $this->render('admin/category.html.twig', [
            'user' => $user,
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/admin/category/add", name="add-category")
     */
    public function addCategory(Request $request): Response
    {
        $user = $this->getUser();
        if (!$user || !in_array('admin', $user->getRoles())) {
            throw $this->createNotFoundException('Page not found');
        }
        if ($request->isMethod('POST')) {
            $title = $request->get('title');
            $category = new Category();
            $category->setTitle($title);
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();
            return $this->redirectToRoute('admin-category');
        }

        return $this->render('admin/addCategory.html.twig', ['user' => $user]);
    }

    /**
     * @Route("/admin/category/{id}", name="update-category")
     */
    public function updateCategory(Request $request, int $id): Response
    {
        $user = $this->getUser();
        if (!$user || !in_array('admin', $user->getRoles())) {
            throw $this->createNotFoundException('Page not found');
        }
        $repository = $this->getDoctrine()->getRepository(Category::class);
        $category = $repository->find($id);

        if (!$category) {
            throw $this->createNotFoundException(
                'No category found for id ' . $id
            );
        }
        $em = $this->getDoctrine()->getManager();
        if ($request->isMethod('POST')) {
            $title = $request->get('title');
            $category->setTitle($title);

            $em->flush();
            return $this->redirectToRoute('admin-category');
        }

        if ($request->isMethod('DELETE')) {
            $em->remove($category);
            $em->flush();
            return $this->redirectToRoute('admin-category');
        }

        return $this->render('admin/updateCategory.html.twig', [
            'user' => $user,
            'category' => $category,
        ]);
    }
}
