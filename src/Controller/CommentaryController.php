<?php

namespace App\Controller;

use App\Entity\Commentary;
use Doctrine\Common\Persistence\ObjectManager;
use FOS\RestBundle\Controller\FOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;

use FOS\RestBundle\Controller\Annotations as FOSRest;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CommentaryController extends FOSRestController
{
    /**
     * @FOSRest\Get("/api/commentaries")
     *
     * @param ObjectManager $manager
     *
     * @return Response
     */
    public function getCommentariesAction(ObjectManager $manager)
    {
        $commentaryRepository = $manager->getRepository(Commentary::class);
        $commentaries = $commentaryRepository->findAll();

        return $this->json($commentaries, Response::HTTP_OK);
    }

    /**
     * @FOSRest\Get("/api/commentaries/{id}")
     *
     * @param ObjectManager $manager
     *
     * @param $id
     * @return Response
     */
    public function getCommentaryAction(ObjectManager $manager, $id)
    {
        $commentaryRepository = $manager->getRepository(Commentary::class);
        $commentary = $commentaryRepository->find($id);

        if ($commentary instanceof commentary) {
            return $this->json([
               'success' => false,
               'error' => 'commentary not found'
            ], Response::HTTP_NOT_FOUND);
        }

        return $this->json($commentary, Response::HTTP_OK);
    }

    /**
     * @FOSRest\Post("/api/commentaries")
     *
     * @ParamConverter("commentary", converter="fos_rest.request_body")
     *
     * @param commentary $commentary
     * @param ObjectManager $manager
     * @param ValidatorInterface $validator
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function postCommentaryAction(commentary $commentary ,ObjectManager $manager, ValidatorInterface $validator)
    {
        $errors = $validator->validate($commentary);

        if(!count($errors)) {
            $manager->persist($commentary);
            $manager->flush();

            return $this->json($commentary, Response::HTTP_CREATED);
        } else {
            return $this->json([
                'success' => false,
                'error' => $errors[0]->getMessage(). '('. $errors[0]->getPropertyPath() . ')'
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @FOSRest\Delete("/api/commentaries/{id}")
     *
     * @param ObjectManager $manager

     * @param $id
     * @return Response
     */
    public function deleteCommentaryAction(ObjectManager $manager, $id)
    {

        $commentaryRepository = $manager->getRepository(Commentary::class);
        $commentary = $commentaryRepository->find($id);

        if($commentary instanceof commentary) {
            $manager->remove($commentary);
            $manager->flush();

            return $this->json([
                'success'=> true
            ], Response::HTTP_OK);
        } else {
            return $this->json([
                'success' => false,
                'error' => 'commentary not found'
            ], Response::HTTP_NOT_FOUND);
        }
    }
}
