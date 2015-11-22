<?php
/**
 * Created by PhpStorm.
 * User: ubuntu
 * Date: 14/11/2015
 * Time: 14:12
 */

namespace Dao\DataSourceBundle\Utilities;

class StringHelper {

    public static function isMatch($fullEntity, array $entities) {
        $pattern = "/^([\\w\\\\]+\\\\)(\\w+)$/i";
        if(preg_match($pattern, $fullEntity, $matches, PREG_OFFSET_CAPTURE)) {
            foreach($entities as $entity) {
                if(strtolower($matches[2][0]) == strtolower($entity)) {
                    return true;
                }
            }
        }
        return false;
    }
}