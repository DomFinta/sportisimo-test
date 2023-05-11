<?php

declare(strict_types=1);

namespace App\Presenters;

use App\Model\Facade\BrandFacade;
use Nette;
use Nette\Application\UI\Form;
use Nette\Utils\Paginator;


final class BrandPresenter extends Nette\Application\UI\Presenter
{
    private const BRAND_PAGE_LIMIT = 4;
    public function __construct(
        private BrandFacade $brandFacade
    ) {
        parent::__construct();
    }

    public function startup(): void
    {
        parent::startup();

        if (!$this->getUser()->isLoggedIn()) {
            $this->redirect('Sign:');
        }

        $this->template->userName = $this->getUser()->identity->getData()['username'];
    }

    public function renderDefault(int $page = 1, string $nameOrder = 'ASC'): void
    {
        $brandCount = $this->brandFacade->getBrandCount();
        $paginator = $this->createPaginator($brandCount, self::BRAND_PAGE_LIMIT, $page);

        $this->template->brands = $this->brandFacade->getBrands($paginator->getLength(), $paginator->getOffset(), $nameOrder);
        $this->template->paginator = $paginator;
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

    protected function createComponentBrandForm(): Form
    {
        $form = new Form;
        $form->addText('name', 'Jméno')
            ->setRequired();
        $form->addText('seoId', 'Seo-id')
            ->setDisabled();
        $form->addCheckbox('isActive', 'Aktivní')
        ->setHtmlAttribute('class', ['indeterminate-checkbox']);

        $form->addText('timeCreated', 'Vytvořeno dne (vyplněno automaticky)')
            ->setDisabled();

        $form->addSubmit('send', 'Uložit');
        $form->onSuccess[] = [$this, 'OnSuccess'];

        return $form;
    }

    public function OnSuccess(array $data): void
    {
        $brandId = $this->getParameter('id');

        if ($brandId) {
            $this->brandFacade->editBrand((int)$brandId, $data);
            $this->flashMessage("Uloženo", 'success');
        } else {
            $brandId = $this->brandFacade->addBrand($data);
            $this->flashMessage("Značka vytvořena", 'success');
            $this->redirect('Brand:edit', $brandId);
        }
    }

    public function renderEdit(int $id): void
    {
        $brand = $this->brandFacade->getById($id);

        if(!$brand) {
            $this->redirect('Brand:');
        }

        $this->getComponent('brandForm')
            ->setDefaults($brand->toArray());
    }

    public function actionDelete(int $id, int $pageId, $nameOrder): void
    {
        $this->brandFacade->deleteBrand($id);
        $this->flashMessage("Značka smazána", 'success');
        $this->redirect('Brand:', ['page' => $pageId, 'nameOrder' => $nameOrder]);
    }
}
