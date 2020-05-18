<?php


namespace Statusboard\Utility;


class StringUtility {

    /**
     * Append to the word, only if the string (as a word) is not already contained in the string.
     *
     * @param string $string
     * @param string $append
     * @param bool   $case_sensitive if the check should be made on a case sensitive basis
     * @param string $delimiter      a non empty string
     *
     * @return string
     */
    public static function appendIfNotInString($string, $append, $case_sensitive = false, $delimiter = ' ') {
        if (strlen($string) === 0) return $append;
        $test_string = $case_sensitive ? $string : strtolower($string);
        $test_append = $case_sensitive ? $append : strtolower($append);
        $words = explode($delimiter, $test_string);
        if (array_search($test_append, $words) === false) {
            return $string . $delimiter . $append;
        }
        return $string;
    }

    /**
     * Combine the array of strings into a single return string.  The return string is made up of the unique strings from
     * the array.
     *
     * @param string[] $strings
     * @param bool     $case_sensitive
     * @param string   $delimiter
     *
     * @return string
     * @see /tests2/unit/Utils/StrUtilTest.php
     *
     */
    public static function buildUniqueString(array $strings, $case_sensitive = false, $delimiter = ' ') {
        $string = '';
        foreach ($strings as $append) {
            $string = self::appendIfNotInString($string, $append, $case_sensitive, $delimiter);
        }
        return $string;
    }

}