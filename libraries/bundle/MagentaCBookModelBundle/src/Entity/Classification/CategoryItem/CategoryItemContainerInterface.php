<?php

namespace Magenta\Bundle\CBookModelBundle\Entity\Classification\CategoryItem;


use Bean\Component\Organization\IoC\OrganizationAwareInterface;
use Bean\Component\Thing\Model\ThingInterface;
use Magenta\Bundle\CBookModelBundle\Entity\Classification\CategoryItem;

interface CategoryItemContainerInterface extends ThingInterface, OrganizationAwareInterface
{
    public function addCategoryItem(CategoryItem $item);

    public function removeCategoryItem(CategoryItem $item);

}