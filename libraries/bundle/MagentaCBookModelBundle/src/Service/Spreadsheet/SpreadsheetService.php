<?php

namespace Magenta\Bundle\CBookModelBundle\Service\Spreadsheet;

use Magenta\Bundle\CBookModelBundle\Service\BaseService;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SpreadsheetService extends BaseService
{
    const PATH_MEMBER_IMPORT = '/uploads/import/member';

    public function getMemberImportFolder()
    {
        return $this->container->getParameter(
                'kernel.root_dir'
            ) . '/../public' . self::PATH_MEMBER_IMPORT . '/';
    }

    public function createReader($filepath)
    {
        return IOFactory::createReaderForFile($filepath);
    }

    public function createWriter($object)
    {
        $sheet = null;
        if ($object instanceof Worksheet) {
            $sheet = $object;
        } elseif ($object instanceof Spreadsheet) {
            $object->setActiveSheetIndex(0);
            $sheet = $object->getActiveSheet();
        } else {
            throw new \InvalidArgumentException('$object does not have a valid type');
        }

        $sWriter = new SpreadsheetWriter($sheet);
        return $sWriter;
    }

    public function createBinaryResponse(Spreadsheet $phpExcelObject, $filename)
    {
        // create the writer
        $writer = new Xlsx($phpExcelObject);
//			$writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
        // create the response
//			$response = $this->get('phpexcel')->createStreamedResponse($writer);
        $response = new StreamedResponse(
            function () use ($writer) {
                $writer->save('php://output');
            },
            200,
            []
        );

        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');

        $response->headers->set('Content-Disposition', 'attachment;filename=' . $filename);
        return $response;
    }
}