<?php

declare(strict_types=1);

namespace App\Model;

use App\Model\Facade\UserFacade;
use Nette\Database\Explorer;
use Nette\Security\AuthenticationException;
use Nette\Security\Authenticator as IAutAuthenticator;
use Nette\Security\IIdentity;
use Nette\Security\Passwords;
use Nette\Security\SimpleIdentity;

class Authenticator implements IAutAuthenticator
{
    public function __construct(
        private UserFacade $userFacade,
        private Passwords $passwords
    ) {}

    public function authenticate(string $user, string $password): IIdentity
    {
        $userEntity = $this->userFacade->findUser($user);

        if(!$userEntity) {
            throw new AuthenticationException('Uživatel nenalezen');
        }


        if(!$this->passwords->verify($password, $userEntity->pwd)) {
            throw new AuthenticationException('Špatné heslo');
        }

        return new SimpleIdentity(
            $userEntity->userId,
            $userEntity->role,
            ['username' => $userEntity->name]
        );
    }
}