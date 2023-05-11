<?php

declare(strict_types=1);

namespace App\Model\Facade;

use Nette\Database\Explorer;
use Nette\Database\Table\ActiveRow;
use Nette\Database\Table\Selection;

class BrandFacade
{
    private const TABLE_NAME = "Brand";
    public function __construct(
        private Explorer $database,
    ) {
    }

    public function getBrands(int $limit, int $offset, string $nameOrder): Selection
    {
        return $this->database->table(self::TABLE_NAME)
            ->order('name ' . $nameOrder)
            ->limit($limit, $offset);
    }

    public function getBrandCount(): int {
        return $this->database->fetchField('SELECT COUNT(brandId) FROM Brand');
    }

    public function addBrand(array $data): int
    {
        $uniqueSeoId = $this->getUniqueSeoId($data['name']);
        $data['seoId'] = $uniqueSeoId;

        $data['timeCreated'] = date("Y-m-d H:i:s");

        $brand = $this->database->table(self::TABLE_NAME)
            ->insert($data);

        return $brand->brandId;
    }

    private function getUniqueSeoId(string $name): string
    {
        $count = 1;
        $seoIdOriginal = urlencode($name);
        $seoId = $seoIdOriginal;
        while ($this->database->table(self::TABLE_NAME)->where(['seoId' => $seoId])->fetch()) {
            $seoId = $seoIdOriginal . '-' . $count;
            $count++;
        }

        return $seoId;
    }

    public function getById(int $brandId): ?ActiveRow
    {
        return $this->database->table(self::TABLE_NAME)->get($brandId);
    }

    public function editBrand(int $brandId, array $data): void
    {
        $post = $this->database
            ->table(self::TABLE_NAME)
            ->get($brandId);

        $post->update($data);
    }

    public function deleteBrand(int $brandId): void
    {
        $this->getById($brandId)?->delete();
    }
}