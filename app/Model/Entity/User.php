<?php

declare(strict_types=1);

namespace App\Model\Entity;

use Nette\Database\Table\ActiveRow;

class User
{
    public function __construct(
        public int $userId,
        public string $name,
        public string $pwd,
        public string $role
    ) {}

    public static function create(?ActiveRow $activeRow): ?self
    {
        if ($activeRow) {
            return new User(...$activeRow->toArray());
        }

        return null;
    }
}
