<?php

namespace App\Presenters;

use Nette\Application\UI\Presenter;
use Nette\Application\UI\Form;
use Nette\Security\AuthenticationException;

final class SignPresenter extends Presenter
{
    protected function createComponentSignInForm(): Form
    {
        $form = new Form;
        $form->addText('username', 'Jméno:')
            ->setRequired('Prosím vyplňte jméno.');

        $form->addPassword('password', 'Heslo:')
            ->setRequired('Prosím vyplňte heslo.');

        $form->addSubmit('send', 'Přihlásit');

        $form->onSuccess[] = [$this, 'signIn'];
        return $form;
    }

    public function signIn(Form $form, \stdClass $data): void
    {
        try {
            $this->getUser()->login($data->username, $data->password);
            $this->redirect('Home:');

        } catch (AuthenticationException $e) {
            $form->addError('Nesprávné přihlašovací jméno nebo heslo.');
        }
    }

    public function actionSignOut(): void
    {
        $user = $this->getUser();

        if ($user->isLoggedIn()) {
            $user->logout();
            $this->flashMessage('Odhlášení bylo úspěšné.');
            $this->redirect('Home:');
        }
    }
}