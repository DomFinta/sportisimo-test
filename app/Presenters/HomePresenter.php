<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;


final class HomePresenter extends Nette\Application\UI\Presenter
{
    public function __construct() {
        parent::__construct();
    }

    public function startup(): void
    {
        parent::startup();

        if (!$this->getUser()->isLoggedIn()) {
            $this->redirect('Sign:in');
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
