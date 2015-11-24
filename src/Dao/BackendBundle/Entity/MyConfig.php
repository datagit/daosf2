<?php

namespace Dao\BackendBundle\Entity;
/**
 * Created by PhpStorm.
 * User: root
 * Date: 11/24/15
 * Time: 5:42 PM
 */
class MyConfig
{
    protected $lang;

    /**
     * @return mixed
     */
    public function getLang()
    {
        return $this->lang;
    }

    /**
     * @param mixed $lang
     */
    public function setLang($lang)
    {
        $this->lang = $lang;
    }


}