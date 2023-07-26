<?php

namespace App\Model;

class BrandsManager
{
    public function __construct(
        private \Nette\Database\Connection $database,
    )
    {
    }

    public function getBrands(int $limit, int $offset, $order = "ASC"){


        return $this->database->query("SELECT * FROM Brands ORDER BY B_Name $order LIMIT ? OFFSET ?", $limit, $offset);
    }

    public function getBrandsCount() : int
    {


        return $this->database->fetchField("SELECT COUNT(*) FROM Brands");
    }

    public  function addBrands($name)
    {
        $this->addLogBrands("",$name);
        return $this->database->query("INSERT INTO Brands ?", [
            "B_Name" => $name,
            "B_CreateDate" => strtotime("now"),
            "B_LastEditDate" => strtotime("now"),

        ]);
    }

    public function controllBrands($name){

        $row =  $this->database->fetch("SELECT B_CreateDate FROM Brands WHERE B_Name = ?",$name);


        if(isset($row) && is_array($row)){
            return true;

        } else {
            return false;
        }

    }
    public  function editBrands($oname,$name)
    {
        $this->addLogBrands($oname,$name);
        return $this->database->query("UPDATE Brands SET", [
            "B_Name" => $name,
            "B_LastEditDate" => strtotime("now"),
            ], 'WHERE B_Name = ?', $oname);
    }

    public  function addLogBrands($oldname,$name)
    {
        return $this->database->query("INSERT INTO BrandsLog ?", [
            "BL_OldName" => $oldname,
            "BL_NewName" => $name,
            "BL_Date" => strtotime("now"),

        ]);
    }
    public function removeBrand($name)
    {
        $this->addLogBrands($name,"");
        return $this->database->query('DELETE FROM Brands WHERE B_Name = ?', $name);
    }

}