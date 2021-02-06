<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Book;
use App\Entity\Category;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function home(): Response
    {
        $user = $this->getUser();

        $repository = $this->getDoctrine()->getRepository(Book::class);

        $books = $repository->findAll();

        $categories = $repository = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findAll();

        return $this->render('home.html.twig', [
            'user' => $user,
            'books' => $books,
            'categories' => $categories,
            'selectedCategory' => null,
        ]);
    }

    /**
     * @Route("/book/{id}", name="book")
     */
    public function book(int $id): Response
    {
        $user = $this->getUser();

        $repository = $this->getDoctrine()->getRepository(Book::class);

        $book = $repository->find($id);

        if (!$book) {
            throw $this->createNotFoundException('book not found');
        }

        return $this->render('book.html.twig', [
            'user' => $user,
            'book' => $book,
        ]);
    }
}
