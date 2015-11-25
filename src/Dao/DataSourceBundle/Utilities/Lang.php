<?php
/**
 * Created by PhpStorm.
 * User: ubuntu
 * Date: 23/11/2015
 * Time: 06:40
 */

namespace Dao\DataSourceBundle\Utilities;


abstract class Lang {
    const __default = self::IdVietNam;

    const IdVietNam = 1;
    const IdEnglish = 2;

    public static function toArray() {
        return array(Lang::IdVietNam, lang::IdEnglish);
    }
}