<?php


namespace Statusboard\Utility;


class ArrayUtility {

    /**
     * The maximum number of recursive steps to be taken for uniqueness
     */
    const MAX_RECURSIVE_STEP = 10;

    /**
     * @param array $array
     * @param callable $accessor
     * @return array
     */
    public static function formatArray(array $array, callable $accessor){
        $response = [];
        foreach ($array as $item) {
            $response[] = call_user_func($accessor, $item);
        }
        return $response;
    }

    /**
     * Convert a CSV array input read from file() into an array of associative arrays key'd off the first row of data.
     * This assumes that there are unique column names in the source data, this also assumes that there are column names
     * for every data row.
     *
     * @param array $input
     * @return array
     * @throws \Exception when an key values is empty
     */
    public static function csvToAssociativeArray(array $input) {
        $result = array_map('str_getcsv', $input);
        $header = self::uniqueIndexValues($result[0]);
        array_walk($result, function (&$a) use ($header) {
            $a = array_combine($header, $a);
        });
        array_shift($result);
        return $result;
    }

    /**
     * Create unique array entries from the source data by appending integers to the entries that are duplicated
     * @param array $input
     * @param int $step
     * @return array
     * @throws \Exception when an empty index value is encountered
     */
    public static function uniqueIndexValues(array $input, int $step = 0) {
        if ($step === self::MAX_RECURSIVE_STEP) return $input;
        $unique = array_unique($input);
        if (count($unique) === count($input)) return $input;
        $results = $test = [];
        foreach ($input as $item) {
            if (empty($item)) throw new \Exception('An empty column name can not be used');
            if (isset($test[$item])) {
                $test[$item]++;
            } else {
                $test[$item] = 0;
            }
            if ($test[$item] === 0) {
                $results[] = $item;
            } else {
                $results[] = $item . $test[$item];
            }
        }
        return self::uniqueIndexValues($results, ++$step);
    }
}