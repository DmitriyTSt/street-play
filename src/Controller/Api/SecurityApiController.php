<?php


namespace App\Controller\Api;


use App\Entity\Device;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;

class SecurityApiController extends AbstractApiController
{
    /**
     * @param Request $request
     * @return Response
     *
     * @Route("/registartion", name="api_registration")
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

    /**
     * @param Request $request
     * @return Response
     *
     * @Route("/login", name="api_login")
     */
    public function loginAction(Request $request): Response
    {
        $content = json_decode($request->getContent(), true);
        if ($content != null) {
            $repository = $this->getDoctrine()->getRepository(User::class);
            $login = $content['login'];
            $password = $content['password'];
            if ($login != null && $password != null) {
                /** @var User $user */
                $user = $repository->findOneBy(['login' => $login]);
                if ($user != null) {
                    $defaultEncoder = new MessageDigestPasswordEncoder('sha512', true, 5000);
                    $encoders = [User::class => $defaultEncoder];
                    $encoderFactory = new EncoderFactory($encoders);
                    $encoder = $encoderFactory->getEncoder($user);
                    if ($encoder->encodePassword($password, $user->getSalt()) == $user->getPassword()) {
                        $em = $this->getDoctrine()->getManager();
                        $device = new Device();
                        $device->setUser($user);
                        $em->persist($device);
                        $em->flush();

                        $json = $this->serializer->serialize(['uuid' => $user->getId(), 'token' => $device->getToken()],"json");
                        return $this->createResponse($json);
                    } else {
                        throw new HttpException(Response::HTTP_FORBIDDEN, "Login failed");
                    }
                } else {
                    throw new HttpException(Response::HTTP_FORBIDDEN, "Login failed");
                }
            } else {
                throw new HttpException(Response::HTTP_FORBIDDEN, "Login failed");
            }
        } else {
            throw new HttpException(Response::HTTP_FORBIDDEN, "Login failed");
        }
    }
}
