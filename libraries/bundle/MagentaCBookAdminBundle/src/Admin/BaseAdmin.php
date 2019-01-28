<?php

namespace Magenta\Bundle\CBookAdminBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;

class BaseAdmin extends AbstractAdmin
{
    const AUTO_CONFIG = true;
    const ENTITY = null;
    const CONTROLLER = null;
    const CHILDREN = null;
    const ADMIN_CODE = null;
    const TEMPLATES = null;

    protected $translationDomain = 'MagentaCBookAdminBundle'; // default is 'messages'

    use BaseAdminTrait;
}
