<?php

namespace App\Utils;

use Carbon\Carbon;
use Spatie\DbDumper\Databases\MySql;

class Maintenance
{
    private string $db_name;
    private string $username;
    private string $password;

    public function __construct(string $db_name, string $username, string $password)
    {
        $this->db_name = $db_name;
        $this->username = $username;
        $this->password = $password;
    }

    public function dump(): void
    {
        $file_name = $this->db_name . '_backup@';
        $file_name .= str_replace([':', ' '], ['', '_'], Carbon::now()->toDateTimeString());
        $file_name .= '.sql';

        MySql::create()
            ->setDbName($this->db_name)
            ->setUserName($this->username)
            ->setPassword($this->password)
            ->doNotUseColumnStatistics()
            ->dumpToFile('backup/' . $file_name);
    }

}