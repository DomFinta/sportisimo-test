<?php

declare(strict_types=1);

namespace App\Model;

use Nette\Database\Explorer;
use Nette\Database\Table\Selection;

class Brand
{
    public function __construct(
        private Explorer $database,
    ) {
    }

    public function getBrands(int $limit, int $offset, string $nameOrder): Selection
    {
        return $this->database->table('Brand')
            ->order('name ' . $nameOrder)
            ->limit($limit, $offset);
    }

    public function getBrandCount(): int {
        return $this->database->fetchField('SELECT COUNT(brandId) FROM Brand');
    }
}