<?php

namespace Btask\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class BtaskUserBundle extends Bundle
{
	public function getParent()
    {
        return 'FOSUserBundle';
    }
}
