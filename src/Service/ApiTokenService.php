<?php
/**
 * Created by PhpStorm.
 * User: zilvinasnavickas
 * Date: 2018-12-17
 * Time: 16:00
 */

namespace App\Service;


use App\Entity\User;
use App\Repository\ApiTokenRepository;

class ApiTokenService
{

    /**
     * @var ApiTokenRepository
     */
    private $tokenRepository;

    public function __construct(ApiTokenRepository $tokenRepository) {
        $this->tokenRepository = $tokenRepository;
    }

    public function deleteApiTokensForUser(User $user): void
    {
        $tokens = $this->tokenRepository->findBy(['user' => $user]);
        if (!$tokens) return;

        foreach ($tokens as $token) {

        }

        if ($user) {
            $this->userRepository->delete($user);
        }
    }

    public function deleteApiToken()
    {
    }
}