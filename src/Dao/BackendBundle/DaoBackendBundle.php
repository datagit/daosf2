<?php

namespace Dao\BackendBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class DaoBackendBundle extends Bundle
{

    public function getParent()
    {
        return 'EasyAdminBundle';
    }

}
