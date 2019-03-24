<?php

namespace App\Controller;

use App\Entity\Author;
use Doctrine\Common\Persistence\ObjectManager;
use FOS\RestBundle\Controller\FOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;

use FOS\RestBundle\Controller\Annotations as FOSRest;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AuthorController extends FOSRestController
{
    /**
     * @FOSRest\Get("/api/authors")
     *
     * @param ObjectManager $manager
     *
     * @return Response
     */
    public function getAuthorsAction(ObjectManager $manager)
    {
        $authorRepository = $manager->getRepository(Author::class);
        $authors = $authorRepository->findAll();

        return $this->json($authors, Response::HTTP_OK);
    }

    /**
     * @FOSRest\Get("/api/authors/{id}")
     *
     * @param ObjectManager $manager
     *
     * @param $id
     * @return Response
     */
    public function getAuthorAction(ObjectManager $manager, $id)
    {
        $authorRepository = $manager->getRepository(Author::class);
        $author = $authorRepository->find($id);

        if ($author instanceof author) {
            return $this->json([
               'success' => false,
               'error' => 'author not found'
            ], Response::HTTP_NOT_FOUND);
        }

        return $this->json($author, Response::HTTP_OK);
    }

    /**
     * @FOSRest\Post("/api/authors")
     *
     * @ParamConverter("author", converter="fos_rest.request_body")
     *
     * @param author $author
     * @param ObjectManager $manager
     * @param ValidatorInterface $validator
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function postAuthorAction(author $author ,ObjectManager $manager, ValidatorInterface $validator)
    {
        $errors = $validator->validate($author);

        if(!count($errors)) {
            $manager->persist($author);
            $manager->flush();

            return $this->json($author, Response::HTTP_CREATED);
        } else {
            return $this->json([
                'success' => false,
                'error' => $errors[0]->getMessage(). '('. $errors[0]->getPropertyPath() . ')'
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @FOSRest\Delete("/api/authors/{id}")
     *
     * @param ObjectManager $manager

     * @param $id
     * @return Response
     */
    public function deleteAuthorAction(ObjectManager $manager, $id)
    {

        $authorRepository = $manager->getRepository(Author::class);
        $author = $authorRepository->find($id);

        if($author instanceof author) {
            $manager->remove($author);
            $manager->flush();

            return $this->json([
                'success'=> true
            ], Response::HTTP_OK);
        } else {
            return $this->json([
                'success' => false,
                'error' => 'author not found'
            ], Response::HTTP_NOT_FOUND);
        }
    }
}
