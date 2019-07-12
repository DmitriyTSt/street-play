<?php


namespace App\Controller\Api;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;

/**
 * Class UserApiController
 * @package App\Controller\Api
 */
class UserApiController extends AbstractApiController
{
    /**
     * @param $id
     * @return Response
     *
     * @Route("/users/{id}", methods={"GET"})
     */
    public function show($id): Response
    {
        $user = $this->getDoctrine()->getManager()->getRepository(User::class)->find($id);

        if ($user) {
            $json = $this->serializer->serialize(
                [
                    "nickname" => $user->getNickname(),
                ],
                "json",
                ['groups' => ["show"]]
            );
            return $this->createResponse($json);
        } else {
            return $this->createResponse(null);
        }
    }


    /**
     * @param Request $request
     * @return Response
     *
     * @Route("/users/registration", methods={"PATCH"}, name="api_add_user_login")
     */
    public function addLoginToAuthUser(Request $request): Response
    {
        $content = json_decode($request->getContent(), true);

        if ($this->getDoctrine()->getRepository(User::class)->findOneBy(['login' => $content['login']]) == null) {
            $user = $this->getUser();
            $user->setLogin($content['login']);
            $user->setPassword($content['password']);
            $defaultEncoder = new MessageDigestPasswordEncoder('sha512', true, 5000);
            $encoders = [User::class => $defaultEncoder];
            $encoderFactory = new EncoderFactory($encoders);
            $encoder = $encoderFactory->getEncoder($user);
            $user->setSalt(md5(time() . $user->getPassword()));
            $user->setPassword($encoder->encodePassword($user->getPassword(), $user->getSalt()));

            $this->getDoctrine()->getManager()->persist($user);
            $this->getDoctrine()->getManager()->flush();
            return $this->createResponse(null);
        } else {
            throw new HttpException(499, "Login already exists");
        }
    }
}