<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;
use Nette\Database\Explorer;


final class HomePresenter extends Nette\Application\UI\Presenter
{
    public function __construct(
        private Explorer $database,
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

    public function renderDefault(): void
    {
        $this->template->brands = $this->database
            ->table('Brand')
            ->order('name ASC')
            ->limit(3);
    }
}
