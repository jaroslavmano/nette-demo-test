<?php

namespace App\Model;

class BrandsManager
{
    public function __construct(
        private \Nette\Database\Connection $database,
    )
    {
    }

    public function getBrands(){


        return $this->database->query("SELECT * FROM Brands");
    }

    public  function addBrands($name)
    {
        return $this->database->query("INSERT INTO Brands ?", [
            "B_Name" => $name,
            "B_CreateDate" => strtotime("now"),
            "B_LastEditDate" => strtotime("now"),

        ]);
    }

}