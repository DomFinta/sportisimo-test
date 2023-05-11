<?php

declare(strict_types=1);

namespace App\Model\Facade;

use App\Model\Entity\User;
use Nette\Database\Explorer;

class UserFacade
{
    public function __construct(
        private Explorer $database
    ) {}

    public function findUser(string $name): ?User
    {
        return User::create($this->database->table('User')->where('name', $name)->fetch());
    }
}