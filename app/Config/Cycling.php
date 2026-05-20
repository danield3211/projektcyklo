<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Cycling extends BaseConfig
{
    // Počet závodů na stránku (přehled závodů)
    public int $racesPerPage = 12;

    // Počet ročníků na stránku
    public int $raceyearsPerPage = 10;

    // Počet etap na stránku
    public int $stagesPerPage = 25;
}
