<?php

class IndexController{
    function index(){
        //echo "Hello";

        $view = new View();
        $view->render('index',["poruka"=>"Dobar dan"]);

    }

    function ooops(){
        //echo "Hello";

        $view = new View();
        $view->render('ooops');

    }

    function mail(){
        //https://artisansweb.net/send-email-using-gmail-smtp-server-swift-mailer-library/

require_once 'vendor/autoload.php';
// Create the Transport
$transport = (new Swift_SmtpTransport('smtp.googlemail.com', 465, 'ssl'))
  ->setUsername('tjakopec@gmail.com')
  ->setPassword('xxxxxxxx')
;
 
// Create the Mailer using your created Transport
$mailer = new Swift_Mailer($transport);
 
// Create a message
$body = 'Dobar dan svima';
 
$message = (new Swift_Message('Naslov poruke'))
  ->setFrom(['tjakopec@gmail.com' => 'Tomislav Jakopec'])
  ->setTo(['alosinac111@gmail.com'])
  ->setCc(['zeljaos@gmail.com'])
  ->setBcc(['josip.dasovic22@gmail.com'])
  ->setBody($body)
  ->setContentType('text/html')
;
 
// Send the message
$mailer->send($message);
    }

    function pdf(){
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetFont('dejavusans', '', 14, '', true);
        $pdf->AddPage();
        foreach(Grupa::read() as $grupa){
            $html .= $grupa->naziv . "<br />";
        }
        
        $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
        $pdf->Output('example_001.pdf', 'I');
    }

    function word(){
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();

        $fontStyle = new \PhpOffice\PhpWord\Style\Font();
        $fontStyle->setBold(true);
        $fontStyle->setName('Arial');
        $fontStyle->setSize(16);

        
        foreach(Grupa::read() as $grupa){
        $myTextElement = $section->addText($grupa->naziv);
        $myTextElement->setFontStyle($fontStyle);
        }


        $filename = 'MyFile.docx';
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save($filename);

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename='.$filename);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filename));
        flush();
        readfile($filename);
        unlink($filename);
    }


    function excel(){

        $excelDoc = new PHPExcel();
        $excelDoc->setActiveSheetIndex(0);
        $excelDoc->getActiveSheet()->getCell('A1')
        ->setValue(3);
        $excelDoc->getActiveSheet()->getCell('A2')
        ->setValue(3);
        $excelDoc->getActiveSheet()->getCell('A3')
        ->setValue(3);
        $excelDoc->getActiveSheet()->getCell('A4')
        ->setValue("=SUM(A1:A3)");
        $filename = 'persons.xlsx';
        $writer = PHPExcel_IOFactory::createWriter($excelDoc, 'Excel2007');
        $writer->save($filename);
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename='.$filename);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filename));
        flush();
        readfile($filename);
        unlink($filename);
    }

}