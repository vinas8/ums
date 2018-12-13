<?php
/**
 * Created by PhpStorm.
 * User: zilvinasnavickas
 * Date: 2018-12-12
 * Time: 20:26
 */

namespace App\Security;

use App\Entity\User;
use App\Repository\ApiTokenRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class TokenAuthenticator extends AbstractGuardAuthenticator
{
    private $em;
    /**
     * @var ApiTokenRepository
     */
    private $apiTokenRepository;

    public function __construct(EntityManagerInterface $em, ApiTokenRepository $apiTokenRepository) {
        $this->em = $em;
        $this->apiTokenRepository = $apiTokenRepository;
    }

    /**
     * Called on every request to decide if this authenticator should be
     * used for the request. Returning false will cause this authenticator
     * to be skipped.
     */
    public function supports(Request $request) {
        return $request->headers->has('Authorization')
            && 0 === strpos($request->headers->get('Authorization'), 'Bearer ');
    }

    /**
     * Called on every request. Return whatever credentials you want to
     * be passed to getUser() as $credentials.
     */
    public function getCredentials(Request $request) {
        $authorizationHeader = $request->headers->get('Authorization');


        return substr($authorizationHeader, 7);
        //        return array(
        //            'token' => $request->headers->get('Authorization'),
        //        );
    }

    public function getUser($credentials, UserProviderInterface $userProvider) {
        $apiToken = $this->apiTokenRepository->findOneBy([
            'token' => $credentials
        ]);

        if (!$apiToken) {
            throw new CustomUserMessageAuthenticationException('Invalid API Token');
        }

        if ($apiToken->isExpired()) {
            throw new CustomUserMessageAuthenticationException('Token is expired');
        }

        return $apiToken->getUser();
    }

    public function checkCredentials($credentials, UserInterface $user) {
        return true;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey) {
        // on success, let the request continue
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception) {
        $data = array(
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData())

            // or to translate this message
            // $this->translator->trans($exception->getMessageKey(), $exception->getMessageData())
        );

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    /**
     * Called when authentication is needed, but it's not sent
     */
    public function start(Request $request, AuthenticationException $authException = null) {
        throw new \Exception('not used');
    }

    public function supportsRememberMe() {
        return false;
    }
}