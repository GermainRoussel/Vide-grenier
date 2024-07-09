<?php

namespace App\Models;

use PHPUnit\Framework\TestCase;
use App\Models\Cities;



class CitiesTest extends TestCase {

    /** @test */
    public function search() {
        $result = Cities::search('bordeaux'); /* recherche de la ville de Bordeaux */        
        fwrite(STDOUT, "voici les réultats de la recherche pour la recherche de 'bordeaux'\n");/* affiche un message */
        fwrite(STDOUT, print_r($result, TRUE)); /* affiche les résultats de la recherche */
        $this->assertGreaterThan(0, count($result)); /* vérifie que le nombre de résultats est supérieur à 0 */
    }
}