<?php

declare(strict_types=1);

namespace App\Model;

use Nette\Database\Explorer;
use Nette\Security\AuthenticationException;
use Nette\Security\Authenticator as IAutAuthenticator;
use Nette\Security\IIdentity;
use Nette\Security\Passwords;
use Nette\Security\SimpleIdentity;

class Authenticator implements IAutAuthenticator
{
    public function __construct(
        private Explorer $database,
        private Passwords $passwords
    ) {}

    public function authenticate(string $user, string $password): IIdentity
    {
        $userRow = $this->database->table('User')
            ->where('name', $user)
            ->fetch();

        if(!$userRow) {
            throw new AuthenticationException('Uživatel nenalezen');
        }


        if(!$this->passwords->verify($password, $userRow->pwd)) {
            throw new AuthenticationException('Špatné heslo');
        }

        return new SimpleIdentity(
            $userRow->userId,
            $userRow->role,
            ['username' => $userRow->name]
        );
    }
}