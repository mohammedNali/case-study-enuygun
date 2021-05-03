<?php


namespace App\Controller;


use App\Entity\Provider;
use App\Entity\Task;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_homepage")
     */
    public function homepage(Environment $environment, EntityManagerInterface $em, TaskRepository $taskRepository)
    {
        $repository = $em->getRepository(Provider::class);
        /** @var Provider $provider */
        $provider = $repository->findAll();

//        $totalWeek =  ;

        return $this->render('provider/homepage.html.twig', [
            'provider' => $provider,
//            'totalWeek' => $totalWeek
        ]);
    }


}