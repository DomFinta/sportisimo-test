<?php

declare(strict_types=1);

namespace App\Presenters;

use App\Model\Authenticator;
use Nette\Application\UI\Presenter;
use Nette\Application\UI\Form;
use Nette\Security\AuthenticationException;

final class SignPresenter extends Presenter
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function createComponentSignInForm(): Form
    {
        $form = new Form;
        $form->addText('name', 'Jméno:')
            ->setRequired('Prosím vyplňte jméno.');

        $form->addPassword('pwd', 'Heslo:')
            ->setRequired('Prosím vyplňte heslo.');

        $form->addSubmit('send', 'Přihlásit')
            ->setHtmlAttribute('class', ['waves-effect', 'waves-light', 'btn']);

        $form->onSuccess[] = [$this, 'signIn'];
        return $form;
    }

    public function signIn(Form $form, \stdClass $data): void
    {
        try {
            $this->getUser()->login($data->name, $data->pwd);
            $this->redirect('Admin:');

        } catch (AuthenticationException $e) {
            $form->addError('Nesprávné přihlašovací jméno nebo heslo.');
        }
    }

    public function actionSignOut(): void
    {
        $user = $this->getUser();

        if ($user->isLoggedIn()) {
            $user->logout();
            $this->redirect('Sign:');
        }
    }
}