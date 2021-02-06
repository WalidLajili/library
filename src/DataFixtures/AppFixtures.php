<?php

namespace App\DataFixtures;

use App\Entity\Book;
use App\Entity\Category;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $admin = new User();
        $admin->setFirstName('admin');
        $admin->setLastName('admin');
        $admin->setRoles(['admin']);
        $admin->setEmail('admin@admin.com');
        $admin->setPassword($this->encoder->encodePassword($admin, 'Test123'));
        $manager->persist($admin);

        $categoriesTitle = [
            'Action and Adventure',
            'Classics',
            'Comic Book',
            'Detective and Mystery',
            'Fantasy',
            'Historical Fiction',
            'Horror',
        ];

        $categories = [];

        for ($i = 0; $i < count($categoriesTitle); $i++) {
            $category = new Category();
            $category->setTitle($categoriesTitle[$i]);
            $manager->persist($category);
            $categories[] = $category;
        }

        $booksContent = [
            [
                'title' => 'In Search of Lost Time',
                'author' => 'Marcel Proust',
                'description' =>
                    "Swann's Way, the first part of A la recherche de temps perdu, Marcel Proust's seven-part cycle, was published in 1913. In it, Proust introduces the themes that run through the entire work. The narrator recalls his childhood, aided by the famous madeleine; and describes M. Swann's passion for Odette. The work is incomparable. Edmund Wilson said \"[Proust] has supplied for the first time in literature an equivalent in the full scale for the new theory of modern physics.\"",
                'cover' => '51Wrlbko5QL.jpg',
                'category' => $categories[1],
            ],

            [
                'title' => 'Ulysses',
                'author' => 'James Joyce',
                'description' =>
                    "Ulysses chronicles the passage of Leopold Bloom through Dublin during an ordinary day, June 16, 1904. The title parallels and alludes to Odysseus (Latinised into Ulysses), the hero of Homer's Odyssey (e.g., the correspondences between Leopold Bloom and Odysseus, Molly Bloom and Penelope, and Stephen Dedalus and Telemachus). Joyce fans worldwide now celebrate June 16 as Bloomsday.",
                'cover' => '41uosf2H2JL.jpg',
                'category' => $categories[0],
            ],

            [
                'title' => 'Don Quixote',
                'author' => 'Miguel de Cervantes',
                'description' =>
                    'Alonso Quixano, a retired country gentleman in his fifties, lives in an unnamed section of La Mancha with his niece and a housekeeper. He has become obsessed with books of chivalry, and believes their every word to be true, despite the fact that many of the events in them are clearly impossible. Quixano eventually appears to other people to have lost his mind from little sleep and food and because of so much reading.',
                'cover' => '51nBHIQv6zL.jpg',
                'category' => $categories[3],
            ],

            [
                'title' => 'One Hundred Years of Solitude',
                'author' => 'Gabriel Garcia Marquez',
                'description' =>
                    "One of the 20th century's enduring works, One Hundred Years of Solitude is a widely beloved and acclaimed novel known throughout the world, and the ultimate achievement in a Nobel Prize–winning career. The novel tells the story of the rise and fall of the mythical town of Macondo through the history of the Buendía family. It is a rich and brilliant chronicle of life and death, and the tragicomedy of humankind. In the noble, ridiculous, beautiful, and tawdry story of the Buendía family, one sees all of humanity, just as in the history, myths, growth, and decay of Macondo, one sees all of Latin America. Love and lust, war and revolution, riches and poverty, youth and senility — the variety of life, the endlessness of death, the search for peace and truth — these universal themes dominate the novel. Whether he is describing an affair of passion or the voracity of capitalism and the corruption of government, Gabriel García Márquez always writes with the simplicity, ease, andpurity that are the mark of a master. Alternately reverential and comical, One Hundred Years of Solitude weaves the political, personal, and spiritual to bring a new consciousness to storytelling. Translated into dozens of languages, this stunning work is no less than an accounting of the history of the human race.",
                'cover' => '51bfZSHE9jL.jpg',
                'category' => $categories[2],
            ],
        ];

        for ($i = 0; $i < count($booksContent); $i++) {
            $content = $booksContent[$i];
            $book = new Book();
            $book->setTitle($content['title']);
            $book->setAuthor($content['author']);
            $book->setDescription($content['description']);
            $book->setCover($content['cover']);
            $book->setCategory($content['category']);
            $book->setUrl('Proust-1.pdf');
            $manager->persist($book);
        }

        $manager->flush();
    }
}
