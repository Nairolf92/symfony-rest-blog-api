<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Author;
use App\Entity\Category;
use App\Entity\Commentary;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->encoder = $passwordEncoder;
    }


    public function load(ObjectManager $manager)
    {

        // Author
        for($i=0;$i<10;$i++) {
            $author = new Author();
            $author->setFirstName('First name'. $i);
            $author->setLastName('Last name'. $i);

            $manager->persist($author);
        }

        // Commentary
        for($i=0;$i<10;$i++) {
            $commentary = new Commentary();
            $commentary->setDescription('Description'. $i);
            $commentary->setCommentaryAuthor(rand(1,10));

            $manager->persist($commentary);
        }

        for($i=0;$i<10;$i++) {
            $category = new Category();
            $category->setName( 'Category'. $i);

            $manager->persist($category);
        }

        for($i=0;$i<10;$i++) {
            $article = new Article();
            $article->setName( 'Article'. $i);

            $manager->persist($article);
        }

        $user = new User();
        $user->setEmail("toto@toto.com");
        $user->setRoles([
            'ROLE_ADMIN',
            'ROLE_API'
        ]);
        $user->setPassword($this->encoder->encodePassword($user, "toto"));

        $manager->persist($user);

        $manager->flush();
    }
}
