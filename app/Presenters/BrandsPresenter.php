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
            ->setRequired('Prosím vyplňte jméno značky.')
            ->setMaxLength(30);

        $form->addSubmit('send', 'Přidat značku');

        $form->onSuccess[] = [$this, 'formAddSucceeded'];
        return $form;
    }

    protected function createComponentEditBrandsForm(): Form
    {

        $Oldname = isset($this->template->brandName)?$this->template->brandName:"";
        $this->template->brandName = "";

        $form = new Form;
        $form->addHidden('OldName', $Oldname);
        $form->addText('B_Name', 'Jméno značky:')
            ->setRequired('Prosím vyplňte jméno značky.')
            ->setDefaultValue($Oldname)
            ->setMaxLength(30);

        $form->addSubmit('send', 'Upravit značku');
        $form->addSubmit('exit', 'Zavřít')
            ->onClick[] = [$this, 'formSubmittedExit'];

        $form->onSuccess[] = [$this, 'formEditSucceeded'];
        return $form;
    }

    public function formAddSucceeded(Form $form, $data): void
    {
        $controll = $this->brandsManager->controllBrands($data->B_Name);

        if($controll){
            $this->brandsManager->addBrands($data->B_Name);
            $this->flashMessage('Úspěšně jste vytvořil/a novou značku');
        } else {
            $this->flashMessage('Nepodařilo se vytvořit novou značku z důvodu: Značka již existuje');
        }

        $this->template->brandName = "";
        $this->redirect('Brands:default');    }

    public function formEditSucceeded(Form $form, $data): void
    {
        if($data->OldName != $data->B_Name){
            $controll = $this->brandsManager->controllBrands($data->B_Name);
        } else {
            $controll = true;
        }

        if($controll){
            $this->brandsManager->editBrands($data->OldName,$data->B_Name);
            $this->flashMessage('Úspěšně jste upravil/a značku');
        } else {
            $this->flashMessage('Nepodařilo se aktualizovat název značky z důvodu: Značka již existuje');

        }
        $this->template->brandName = "";
        $this->redirect('Brands:default');
    }

    public function formSubmittedExit(Form $form): void
    {
        // here we will process the data sent by the form
        $this->template->brandName = "";
        $this->redirect('Brands:default');
    }

    function renderEdit($name){
        //$this->template->brandName = $name;

    }
    function renderDefault(int $page = 1, int $per = 1, bool $sort = true, $name = "")
    {
        // Získání počtu značek
        $brandsCount = $this->brandsManager->getBrandsCount();

        $this->template->sort = $sort;


        // Tvorba a nastavení paginatoru
        $paginator = new Nette\Utils\Paginator;
        $paginator->setItemCount($brandsCount);
        $paginator->setItemsPerPage($per);
        $paginator->setPage($page);



            $order = "ASC";

            if($sort){
                $offset = $paginator->getOffset();
            } else {
                $offset = $paginator->getCountdownOffset();
            }


        $this->template->brandName = $name;
        $brands = $this->brandsManager->getBrands($paginator->getLength(), $offset, $order);

        $this->template->brands = $brands;

        $this->template->paginator = $paginator;
    }

    function handleDelete($name)
    {
        $this->brandsManager->removeBrand($name);
        $this->flashMessage("Značka byla smazána!");
        $this->redirect('Brands:default');
    }

}
