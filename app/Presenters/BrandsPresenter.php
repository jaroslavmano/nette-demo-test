<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;


final class BrandsPresenter extends Nette\Application\UI\Presenter
{
    public function __construct(
        protected \App\Model\BrandsManager $brandsManager,
    )
    {
    }

    protected function createComponentAddBrandsForm(): Form
    {
        $form = new Form;
        $form->addText('B_Name', 'Jméno značky:')
            ->setRequired('Prosím vyplňte jméno značky.');

        $form->addSubmit('send', 'Add');

        $form->onSuccess[] = [$this, 'formSucceeded'];
        return $form;
    }

    public function formSucceeded(Form $form, $data): void
    {
        // here we will process the data sent by the form
        $this->brandsManager->addBrands($data->B_Name);
        $this->flashMessage('Úspěšně jste vytvořil/a novou značku');
        $this->redirect('Brands:');
    }

    function renderDefault(int $page = 1, int $per = 1)
    {
        // Získání počtu značek
        $brandsCount = $this->brandsManager->getBrandsCount();

        // Tvorba a nastavení paginatoru
        $paginator = new Nette\Utils\Paginator;
        $paginator->setItemCount($brandsCount);
        $paginator->setItemsPerPage($per);
        $paginator->setPage($page);

        $brands = $this->brandsManager->getBrands($paginator->getLength(), $paginator->getOffset());

        $this->template->brands = $brands;

        $this->template->paginator = $paginator;
    }

    function handleDelete($name)
    {
        $this->brandsManager->removeBrand($name);
        $this->flashMessage("Značka byla smazána!");
        $this->redirect("this");
    }

}
