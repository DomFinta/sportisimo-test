<?php

declare(strict_types=1);

namespace App\Presenters;

use App\Model\Brand;
use Nette;
use Nette\Utils\Paginator;


final class AdminPresenter extends Nette\Application\UI\Presenter
{
    private const BRAND_PAGE_LIMIT = 4;
    public function __construct(
        private Brand $brandModel
    ) {
        parent::__construct();
    }

    public function startup(): void
    {
        parent::startup();

        if (!$this->getUser()->isLoggedIn()) {
            $this->redirect('Sign:');
        }
    }

    public function renderDefault(int $page = 1, string $nameOrder = 'ASC'): void
    {
        $brandCount = $this->brandModel->getBrandCount();
        $paginator = $this->createPaginator($brandCount, self::BRAND_PAGE_LIMIT, $page);

        $this->template->brands = $this->brandModel->getBrands($paginator->getLength(), $paginator->getOffset(), $nameOrder);
        $this->template->paginator = $paginator;
        $this->template->userName = $this->getUser()->identity->getData()['username'];
        $this->template->nameOrder = $nameOrder;
    }

    private function createPaginator(int $count, int $limit, int $page): Paginator
    {
        $paginator = new Paginator();
        $paginator->setItemCount($count);
        $paginator->setItemsPerPage($limit);
        $paginator->setPage($page);

        return $paginator;
    }
}
