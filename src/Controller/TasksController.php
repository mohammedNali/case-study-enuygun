<?php


namespace App\Controller;


use App\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TasksController extends AbstractController
{
    /**
     * @Route("/tasks/{provider}", methods="GET")
     */
    public function addTasks($provider, EntityManagerInterface $em)
    {
        $client = HttpClient::create();
        $response = $client->request('GET', ''.$provider.'');

        $url = substr($provider, strrpos($provider, '/') + 1);

        dd($url);

        $statusCode = $response->getStatusCode();
        // $statusCode = 200
        $contentType = $response->getHeaders()['content-type'][0];
        // $contentType = 'application/json'
//        $content = $response->getContent();
        // $content = '{"id":521583, "name":"symfony-docs", ...}'
        $content = $response->toArray();
        // $content = ['id' => 521583, 'name' => 'symfony-docs', ...]
//        $decoded_data = json_decode($content, true);
//        dd($content);

        foreach ($content as $task) {
//            dump($task['id']);
            $newTask = new Task();
            $newTask->setTitle($task['id'])
                ->setDuration($task['sure'])
                ->setDifficulty($task['zorluk'])
                ->setUrl($url);
            $em->persist($newTask);
        }
//        dd('hello');
//        return new Response('space rocks... include comets, asteroids & meteoroids');
        $em->flush();

        return new Response(sprintf(
            'Hiya! New Article id: #%d slug: %s',
            $newTask->getId(),
            $newTask->getDifficulty()
        ));

    }


    public function getTasks($url, EntityManagerInterface $em)
    {
        $repository = $em->getRepository(Task::class);
    }

//    /**
//     * @Rest\Get("/movies")
//     *
//     * @returnResponse
////     */
//    public function getProviderTasks()
//    {
//        $repository=$this->getDoctrine()->getRepository(Task::class);
//        $movies=$repository->findall();
//        return$this->handleView($this->view($movies));
//    }
}