<?php

namespace App\Services;

use App\Imports\ResultsLotteryImport;
use App\Models\LotteryResults;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Maatwebsite\Excel\Facades\Excel;

class LotteryService
{
    // Can exclude numbers for combinations
    private $excludedNumbers = [];

    /**
     * @var bool - Active debug mode for view WrongResponses
     */
    public $debug;

    public function importResults($file){
        throw_if(!$file, new FileNotFoundException, "There's no file");
        try {
            // Importar los resultados desde el archivo
            $sheetCount = count(Excel::toArray([], $file)); // Leer antes de importar

            Excel::import(new ResultsLotteryImport($sheetCount), $file);
            return true;
        }catch(\Exception $e){
            throw new \Exception($e->getMessage());
        }
    }

    function getNumbersCombinations()
    {
        $results = LotteryResults::all();
        $frequencies = $this->getNumberFrequencies($results);

        // Ordenar los números por frecuencia
        arsort($frequencies);
        $distribution = $this->getDistribution($frequencies); // Ejemplo 4 más frecuentes y 2 menos frecuentes;

        // Separar en más frecuentes y menos frecuentes
        $half = intdiv(count($frequencies), 2);
        $mostFrequent = array_slice($frequencies, 0, $half, true);
        $leastFrequent = array_slice($frequencies, $half, null, true);

        // Generar combinaciones siguiendo las reglas
        $combinations = $this->generateCombinations($results, $distribution, $mostFrequent, $leastFrequent);

        return array_slice($combinations, 0, 10);
    }

    /**
     * Obtiene la distrbución de los números más y menos frecuentes 3-3, 4-2...
     * @param $frequencies
     * @param $distribution
     * @return array
     */
    function getDistribution($frequencies, $distribution = [3, 3])
    {
        // Obtener los números más frecuentes
        $mostFrequent = array_slice($frequencies, 0, $distribution[0], true);

        // Obtener los números menos frecuentes
        $leastFrequent = array_slice($frequencies, -$distribution[1], $distribution[1], true);

        return [
            count(array_keys($mostFrequent)),
            count(array_keys($leastFrequent))
        ];
    }

    function getNumberFrequencies($results)
    {
        $frequencies = array_fill(1, 49, 0);

        foreach ($results as $result) {
            $numbers = json_decode($result->numbers, true);
            foreach ($numbers as $number) {
                $frequencies[$number]++;
            }
        }

        arsort($frequencies); // Ordenar por frecuencia en orden descendente

        return $frequencies;
    }

    public function setExcludedNumbers(array $numbers)
    {
        $this->excludedNumbers = $numbers;
    }


    private function generateCombinations($results, $distribution, $mostFrequent, $leastFrequent)
    {
        $combinations = [];
        $numbersByLastAppearance = array_keys(array_slice($this->getNumbersByLastAppearance($results),0,5, true));

        // Regla 1: 3-3 o 4-2 distribución entre más y menos frecuentes
        $distributionPatterns = [$distribution];

        foreach ($distributionPatterns as $pattern) {
            list($mostCount, $leastCount) = $pattern;

            // Generar combinaciones iniciales de números
            $mostCombos = $this->selectNumbers($mostFrequent, $mostCount);
            $leastCombos = $this->selectNumbers($leastFrequent, $leastCount);

            foreach ($mostCombos as $mostCombo) {
                foreach ($leastCombos as $leastCombo) {
                    $combo = array_merge($mostCombo, $leastCombo);

                    // Aplicar las reglas adicionales de validación
                    if ($this->isValidCombination($combo, $numbersByLastAppearance)) {
                        $combinations[] = $combo;
                    }
                }
            }
        }

        return $combinations;
    }

    private function selectNumbers($numbers, $count)
    {
        $keys = array_keys($numbers);
        return $this->getCombinations($keys, $count);
    }

    private function getCombinations($array, $count)
    {
        $results = [];
        if ($count == 1) {
            foreach ($array as $value) {
                $results[] = [$value];
            }
        } else {
            for ($i = 0; $i <= count($array) - $count; $i++) {
                $sub_combinations = $this->getCombinations(array_slice($array, $i + 1), $count - 1);
                foreach ($sub_combinations as $sub_combination) {
                    $results[] = array_merge([$array[$i]], $sub_combination);
                }
            }
        }
        return $results;
    }

    public function getNumbersByLastAppearance($results)
    {
        // Crear un array para almacenar la última aparición de cada número
        $lastAppearance = [];

        foreach ($results as $result) {
            $numbers = json_decode($result->numbers);
            foreach ($numbers as $number) {
                // Si el número no está en el array o la fecha es más reciente, actualizarla
                if (!isset($lastAppearance[$number]) || $lastAppearance[$number] < $result->date_at) {
                    $lastAppearance[$number] = $result->date_at;
                }
            }
        }

        // Ordenar el array por la última fecha de aparición en orden descendente
        uasort($lastAppearance, function($a, $b) {
            return strtotime($a) - strtotime($b);
        });

        // Retornar el array ordenado
        return $lastAppearance;
    }

    private function isValidCombination($combination, $numbersByLastAppearance)
    {
        // Balance de Pares e Impares (3 pares y 3 impares)
        $evens = array_filter($combination, fn($n) => $n % 2 == 0);
        $odds = array_filter($combination, fn($n) => $n % 2 != 0);
        if (count($evens) != 3 || count($odds) != 3) {
            return false;
        }

        // Distribución entre Números Altos y Bajos (3 números bajos y 3 números altos)
        $lows = array_filter($combination, fn($n) => $n <= 25);
        $highs = array_filter($combination, fn($n) => $n > 25);
        if (count($lows) != 3 || count($highs) != 3) {
            return false;
        }

        // Evitar Múltiplos de una Misma Cifra
        foreach ($combination as $number) {
            foreach ($combination as $otherNumber) {
                if ($number != $otherNumber && $otherNumber % $number == 0) {
                    return false;
                }
            }
        }

        // Evitar Secuencias Consecutivas
        sort($combination);
        $len = count($combination);
        for ($i = 1; $i < $len; $i++) {
            if ($combination[$i] - $combination[$i - 1] === 1) {
                return false;
            }
        }

        // Escoger un número que no ha salido en mucho tiempo pero no mas de dos
        $dueNumbers = array_diff($combination, $numbersByLastAppearance);
        if (count($dueNumbers) <=4 || count($dueNumbers) === 6) {
            return false;
        }

        // Suma Total de los Números (debe estar entre 100 y 150)
        $sum = array_sum($combination);
        if ($sum < 100 || $sum > 150) {
            return false;
        }

        // Comprueba que la distribucón de números escogidos tenga todas las decenas y sólo haya una decena con dos números
        if(!$this->checkDecenas($combination)){
            return false;
        }

        // Comprueba que no haya números con las mismas terminaciones
        if($this->hasSameEnding($combination)){
            return false;
        }

        // Comprueba si los números excluidos estan en la combinación
        if($this->checkNumbersExlosionExists($combination)){
            return false;
        }

        return true;
    }

    private function checkDecenas($numeros){
        // Verificar que el array tenga exactamente 6 números y estén en el rango permitido
        if (count($numeros) !== 6 || !array_reduce($numeros, fn($carry, $n) => $carry && is_int($n) && $n >= 1 && $n <= 49, true)) {
            return false;
        }

        $conteoDecenas = array_fill(0, 5, 0); // Contador para decenas 1-9, 10-19, 20-29, 30-39, 40-49

        foreach ($numeros as $numero) {
            $decena = intdiv($numero - 1, 10); // Calcula la decena (0 a 4)
            $conteoDecenas[$decena]++;
        }

        $decenasConMasDeUnNumero = array_filter($conteoDecenas, fn($count) => $count > 1);

        // Verificar que haya al menos un número en cada decena y que solo una decena se repita
        return count(array_filter($conteoDecenas, fn($count) => $count > 0)) === 5 && count($decenasConMasDeUnNumero) === 1;
    }

    /**
     * Comprueba si existen números con la misma terminación en un array.
     *
     * @param array $numbers
     * @return bool
     */
    public static function hasSameEnding(array $numbers): bool
    {
        // Extraer las terminaciones de los números
        $endings = array_map(function($number) {
            return $number % 10; // Obtener el último dígito de cada número
        }, $numbers);

        // Verificar si hay duplicados en las terminaciones
        return count($endings) !== count(array_unique($endings));
    }

    private function checkNumbersExlosionExists(array $numbers): bool
    {
        if(count($this->excludedNumbers) === 0) return true;
        return count(array_diff($numbers, $this->excludedNumbers)) < 6;
    }
}
