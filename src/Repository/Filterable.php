<?php /** @noinspection PhpPrivateFieldCanBeLocalVariableInspection */


namespace App\Repository;


use Doctrine\Common\Collections\Criteria;

trait Filterable
{
    private Criteria $criteria;

    public function getFilterCriteria(array $filter = []): Criteria
    {
        $this->criteria = Criteria::create();

        $filter = self::standardizeFilterParameters($filter);
        $filter_translator = $this->filter_translator ?? [];

        foreach($filter as $filter_name => $filter_value){            
            $filter_function = $filter_translator[$filter_name] ?? null;
            if(!empty($filter_function)){
                $this->{$filter_function}($filter_value);
            }
        }
        
        return $this->criteria;
    }


    /** will convert the criteria array given in the json-request (example)
     * ["isOldEnough", "hasMobile" => "false", "isYoungerThan" => 65]
     * to a standardized form of
     * ["isOldEnough" => true, "hasMobile" => false, "isYoungerThan" => 65]
     */
    public static function standardizeFilterParameters(array $filter = []): array
    {
        [$keys, $values] = [array_keys($filter), array_values($filter)];
        $booleans = [0 => false, 1 => true, 'false' => false, 'true' => true];

        foreach ($keys as $k => $v) {
            if (is_numeric($v)) {
                $keys[$k] = $values[$k];
                $values[$k] = true;
            } elseif (array_key_exists($values[$k], $booleans)){
                $values[$k] = $booleans[$values[$k]];
            }
        }
        return array_combine($keys, $values);
    }

}