<?php

namespace App\Controller;

use App\Entity\Article;
use Doctrine\Common\Persistence\ObjectManager;
use FOS\RestBundle\Controller\FOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;

use FOS\RestBundle\Controller\Annotations as FOSRest;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ArticleController extends FOSRestController
{
    /**
     * @FOSRest\Get("/api/articles")
     *
     * @param ObjectManager $manager
     *
     * @return Response
     */
    public function getArticlesAction(ObjectManager $manager)
    {
        $articleRepository = $manager->getRepository(Article::class);
        $articles = $articleRepository->findAll();

        return $this->json($articles, Response::HTTP_OK);
    }

    /**
     * @FOSRest\Get("/api/articles/{id}")
     *
     * @param ObjectManager $manager
     *
     * @param $id
     * @return Response
     */
    public function getArticleAction(ObjectManager $manager, $id)
    {
        $articleRepository = $manager->getRepository(Article::class);
        $article = $articleRepository->find($id);

        if ($article instanceof article) {
            return $this->json([
               'success' => false,
               'error' => 'article not found'
            ], Response::HTTP_NOT_FOUND);
        }

        return $this->json($article, Response::HTTP_OK);
    }

    /**
     * @FOSRest\Post("/api/articles")
     *
     * @ParamConverter("article", converter="fos_rest.request_body")
     *
     * @param article $article
     * @param ObjectManager $manager
     * @param ValidatorInterface $validator
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function postArticleAction(article $article ,ObjectManager $manager, ValidatorInterface $validator)
    {
        $errors = $validator->validate($article);

        if(!count($errors)) {
            $manager->persist($article);
            $manager->flush();

            return $this->json($article, Response::HTTP_CREATED);
        } else {
            return $this->json([
                'success' => false,
                'error' => $errors[0]->getMessage(). '('. $errors[0]->getPropertyPath() . ')'
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @FOSRest\Delete("/api/articles/{id}")
     *
     * @param ObjectManager $manager

     * @param $id
     * @return Response
     */
    public function deleteArticleAction(ObjectManager $manager, $id)
    {

        $articleRepository = $manager->getRepository(Article::class);
        $article = $articleRepository->find($id);

        if($article instanceof article) {
            $manager->remove($article);
            $manager->flush();

            return $this->json([
                'success'=> true
            ], Response::HTTP_OK);
        } else {
            return $this->json([
                'success' => false,
                'error' => 'article not found'
            ], Response::HTTP_NOT_FOUND);
        }
    }
}
