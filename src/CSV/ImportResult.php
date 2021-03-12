<?php


namespace App\CSV;


use App\Interfaces\CSV\ImportResultInterface;

class ImportResult implements ImportResultInterface
{




    public function getPersons(): iterable
    {
        // TODO: Implement getPersons() method.
    }

    public function countPersons(): int
    {
        // TODO: Implement countPersons() method.
    }

    public function getProjects(): iterable
    {
        // TODO: Implement getProjects() method.
    }

    public function countProjects(): int
    {
        // TODO: Implement countProjects() method.
    }

    public function getDonations(): iterable
    {
        // TODO: Implement getDonations() method.
    }

    public function countDonations(): int
    {
        // TODO: Implement countDonations() method.
    }
}