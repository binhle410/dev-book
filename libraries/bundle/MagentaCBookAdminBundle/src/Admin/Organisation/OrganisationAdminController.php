<?php

namespace Magenta\Bundle\CBookAdminBundle\Admin\Organisation;

use Magenta\Bundle\CBookAdminBundle\Admin\BaseCRUDAdminController;
use Magenta\Bundle\CBookModelBundle\Entity\Organisation\Organisation;
use Magenta\Bundle\CBookModelBundle\Entity\System\DataProcessing\DPJob;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class OrganisationAdminController extends BaseCRUDAdminController
{
    public function importMembersAction(Request $request, $id = null)
    {
        $request = $this->getRequest();
//        $id = $request->get($this->admin->getIdParameter());

        /** @var Organisation $object */
        $object = $this->admin->getObject($id);

        if (!$object || !$object instanceof Organisation) {
            throw $this->createNotFoundException(sprintf('unable to find the object with id: %s', $id));
        }
        if ($request->isMethod('post')) {
            $dpRepo = $this->getDoctrine()->getRepository(DPJob::class);
            if (!empty($dp = $dpRepo->findOneBy([
                'status' => DPJob::STATUS_WORK_IN_PROGRESS,
                'type' => DPJob::TYPE_MEMBER_IMPORT,
                'ownerId' => $id]))) {
                $this->addFlash('sonata_type_error', 'An Import of Members is still being processed. You can only upload a new file after the processing is finished for ' . $object->getName());
                return $this->redirectToList();
            }

            $fileFieldName = 'member-list';
            if (isset($_FILES[$fileFieldName])) {
                $errors = array();
                $file_name = $_FILES[$fileFieldName]['name'];
                $file_size = $_FILES[$fileFieldName]['size'];
                $file_tmp = $_FILES[$fileFieldName]['tmp_name'];
                $file_type = $_FILES[$fileFieldName]['type'];
                $explodedFileName = explode('.', $_FILES[$fileFieldName]['name']);
                $file_ext = strtolower(end($explodedFileName));

                $filePath = $this->get('magenta_book.spreadsheet_service')->getMemberImportFolder() . $id . '_' . $file_name;
                file_exists($filePath) ? unlink($filePath) : "";

                $expensions = array("xls", "xlsx");

                if (in_array($file_ext, $expensions) === false) {
                    $errors[] = "Extension not allowed, please choose a valid Microsoft Excel file.";
                }

                if ($file_size > 6291456) {
                    $errors[] = 'File size must be less than 6 MB';
                }

                if (empty($errors) == true) {
                    move_uploaded_file($file_tmp, $filePath);
                    $dp = new DPJob();
                    $dp->setOwnerId($id);
                    $dp->setType(DPJob::TYPE_MEMBER_IMPORT);
                    $dp->setResourceName($id . '_' . $file_name);
                    $manager = $this->get('doctrine.orm.default_entity_manager');
                    $manager->persist($dp);
                    $manager->flush($dp);
                    $this->get('sonata.notification.backend')->createAndPublish('member-import', array(
                        'job-id' => $dp->getId()
                    ));
                    $this->addFlash('sonata_flash_success', 'Member List was uploaded successfully! Start importing now...');
                } else {
                    $this->addFlash('sonata_flash_error', implode(', ', $errors));
                }
            }
        } else {
            return new JsonResponse(['message' => 'Import is in progress']);
        }
        return $this->redirectToList();
    }

    public function listAction()
    {
        $this->admin->setTemplate('list', '@MagentaCBookAdmin/Admin/Organisation/Organisation/CRUD/list.html.twig');
        return parent::listAction();
    }
}