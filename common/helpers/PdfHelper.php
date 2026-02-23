<?php
namespace common\helpers;

use Mpdf\Mpdf;
use Yii;

class PdfHelper
{
    /**
     * Generate and download PDF from raw HTML
     *
     * @param string $html      Fully-formed HTML (with inline CSS)
     * @param string $filename  Download filename (without .pdf)
     * @param array  $options   Optional mPDF options
     */
    public static function download(string $html, string $filename, array $options = []): void
    {
        $config = array_merge([
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 10,
            'margin_bottom' => 10,
            'default_font' => 'dejavusans',
        ], $options);

        $mpdf = new Mpdf($config);

        $mpdf->SetTitle($filename);
        $mpdf->SetAuthor(Yii::$app->name);
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->SetWatermarkText('H2H - H2H',0.09);
        $mpdf->showWatermarkText = true;
        $mpdf->WriteHTML($html);

        // Force download
        Yii::$app->response->sendContentAsFile(
            $mpdf->Output('', 'S'),
            $filename . '.pdf',
            [
                'mimeType' => 'application/pdf',
                'inline' => false,
            ]
        )->send();

        Yii::$app->end();
    }
}
