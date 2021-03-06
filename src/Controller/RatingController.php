<?php

namespace App\Controller;

use App\Entity\Rating;
use App\Repository\RatingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RatingController extends AbstractController
{
    #[Route('/rating', name: 'rating')]
    public function index(): Response
    {
        return $this->render('rating/index.html.twig', [
            'controller_name' => 'RatingController',
            'title' => 'Helfopets rating',
        ]);
    }

    #[Route('/rating/grade', name: 'rating_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, ManagerRegistry $doctrine): Response
    {
        $localIP = getHostByName(getHostName());
        $rating = $doctrine->getRepository(Rating::class)->findOneBy([
            'ip' => $localIP
        ]);
        if($rating != null){
            $this->addFlash(
                'success',
                'Извините! Вы можете дать вашу оценку только один раз.'); 
            return $this->redirectToRoute('about_comtroller', [], Response::HTTP_SEE_OTHER);    
        }
        $value = $request->request->get('ratin');
        $entityManager = $doctrine->getManager();
        $rating = new Rating();
        $rating->setGrade($value);
        $rating->setIp($localIP);
        $entityManager->persist($rating);
        $entityManager->flush();
        $this->addFlash(
            'success',
            'Спасибо за вашу оценку'); 
        return $this->redirectToRoute('about_comtroller', [], Response::HTTP_SEE_OTHER);    
    }
    public function ratingSumm(RatingRepository $ratingRepository){
        $ratingAll = $ratingRepository->findAll();
        $ratingSumm = 0;
        foreach($ratingAll as $elem){
            $ratingSumm += $elem->getGrade();
        }
        return $ratingSumm;
    }
    public function rating($ratingSumm, $ratingCount){
        if($ratingCount != null){
            $rating_value = round(($ratingSumm/$ratingCount), 2, PHP_ROUND_HALF_DOWN);
        }
        else{
            $rating_value = 0;
        }
        return $rating_value;
    }
}
