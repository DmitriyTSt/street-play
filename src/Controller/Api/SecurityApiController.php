<?php


namespace App\Controller\Api;


use App\Entity\Device;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;

class SecurityApiController extends AbstractApiController
{
    /**
     * @param Request $request
     * @return Response
     *
     * @Route("/registartion", name="api_login")
     */
    public function registrationAction(Request $request): Response
    {
        $content = json_decode($request->getContent(), true);
        if ($content != null) {
            $user = new User();
            $user->setNickname($content['nickname']);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $device = new Device();
            $device->setUser($user);
            $em->persist($device);
            $em->flush();

            $json = $this->serializer->serialize(['uuid' => $user->getId(), 'token' => $device->getToken()],"json");
            return $this->createResponse($json);
        } else {
            throw new HttpException(Response::HTTP_FORBIDDEN, "Login failed");
        }
    }
}
