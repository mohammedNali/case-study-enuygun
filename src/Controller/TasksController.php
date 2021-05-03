<?php


namespace App\Controller;


use App\Entity\Provider;
use App\Entity\Task;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TasksController extends AbstractController
{
    /**
     * @Route("/tasks/{provider}", methods="POST")
     */
    public function addTasks($provider, EntityManagerInterface $em)
    {
        $client = HttpClient::create();
        $response = $client->request('GET', 'http://www.mocky.io/v2/'.$provider);

//        $url = substr($provider, strrpos($provider, '/') + 1);

        $content = $response->toArray();

        $repository = $em->getRepository(Provider::class);
        /** @var Provider $provider_exists */
        $provider_exists = $repository->findBy(['url' => $provider]);
        if (!$provider_exists) {
            $newProvider = new Provider();
            $newProvider->setUrl($provider);
            $em->persist($newProvider);
            $em->flush();
            if (sizeof($content[0]) === 1) {
                foreach ($content as $key => $value) {
                    foreach ($value as $item => $items) {
                        $newTask = new Task();
                        $newTask->setTitle($item)
                            ->setDuration($items['estimated_duration'])
                            ->setDifficulty($items['level'])
                            ->setUrl($provider);
                        $em->persist($newTask);
                    }
                }
                $em->flush();
                return $this->json(['response' => 'the STRANGE data saved to database']);
            } else {
                foreach ($content as $key => $value) {
                    $newTask = new Task();
                    $newTask->setTitle($value['id'])
                        ->setDuration($value['sure'])
                        ->setDifficulty($value['zorluk'])
                        ->setUrl($provider);
                    $em->persist($newTask);
                }
                $em->flush();
                return $this->json(['response' => 'the data saved to database']);
            }
        } else {
            return $this->json(['response' => 'The Provider already exists']);
        }
    }


    /**
     * @Route("/provider/{url}", name="app_provider_show")
     * @param $url
     * @param EntityManagerInterface $em
     * @param TaskRepository $taskRepository
     * @return Response
     */
    public function show($url, EntityManagerInterface $em, TaskRepository $taskRepository)
    {
        $repository = $em->getRepository(Task::class);
//        /** @var Task $tasks */
//        $tasks = $repository->findBy(['url' => $url, 'difficulty' => 1], ['difficulty' => 'ASC']);
        $tasks = $repository->findBy(['url' => $url], ['difficulty' => 'ASC']);

        $kacHafta = $taskRepository->calculate($url, $taskRepository) ;

        return $this->render('provider/show.html.twig', [
            'tasks' => $tasks,
            'totalWeek' => $kacHafta
        ]);
    }



    /**
     * @Route ("/delete-provider/{url}", name="delete_provider")
     * @param $url
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteProvider($url, EntityManagerInterface $em)
    {
        $tasks = $em->getRepository('App:Task')->findBy(array('url' => $url));
        $provider = $em->getRepository('App:Provider')->findOneBy(['url' => $url]);
        foreach ($tasks as $task) {
            $em->remove($task);
        }
        $em->remove($provider);
        $em->flush();

        return $this->redirect($this->generateUrl('app_homepage'));
    }

}