<?php

namespace Magenta\Bundle\CBookAdminBundle\Admin;

use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class BaseCRUDAdminController extends CRUDController
{
    /**
     * @var BaseAdmin
     */
    protected $admin;

    use BaseCRUDAdminControllerTrait;

    public function contentEditAction(Request $request, $id = null)
    {
        $id = $request->get($this->admin->getIdParameter());

        $object = $this->admin->getObject($id);

        if (!$object) {
            throw $this->createNotFoundException(sprintf('unable to find the book with id: %s', $id));
        }

        $field = $request->get('field', 'name');
        if ('name' === $field) {
            $content = strip_tags($request->get('content'), '<strong><b><i><u>');
        } else {
            $content = $request->get('content');
        }
        $setter = 'set'.ucfirst($field);
        $object->{$setter}($content);

        $manager = $this->get('doctrine.orm.default_entity_manager');
        $manager->persist($object);
        $manager->flush();

        return new JsonResponse(['content edited '.$content]);
    }
}
