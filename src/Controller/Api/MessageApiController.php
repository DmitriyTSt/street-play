<?php


namespace App\Controller\Api;

use App\Entity\Message;
use App\Entity\Place;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MessageApiController extends AbstractApiController
{
    /**
     * @param Request $request
     * @return Response
     *
     * @Route("/send-message")
     */
    public function sendMessage(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $em = $this->getDoctrine()->getManager();

        $placeId = $data['placeId'];
        $place = $em->getRepository(Place::class)->find($placeId);
        $text = $data['text'];
        $message = new Message();
        $message->setAuthor($this->getUser());
        $message->setPlace($place);
        $message->setText($text);

        $em->persist($message);
        $em->flush();

        return $this->createResponse(json_encode('Message created'), Response::HTTP_OK);
    }
}