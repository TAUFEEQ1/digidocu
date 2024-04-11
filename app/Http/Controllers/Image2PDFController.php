<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Dompdf\Dompdf;
use Dompdf\Options;
use Log;

class Image2PDFController extends Controller
{
    //
    public function create(Request $request){
        return view("image2pdf.create");
    }
    
    public function store(Request $request){
        $files = $request->file('files');
    
        // Initialize dompdf options
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        
        // Create dompdf instance
        $dompdf = new Dompdf($options);
        
        // Start PDF document
        $dompdf->setPaper('A4', 'portrait');
        
        // HTML content for the PDF
        $htmlContent = '';
        
        // Loop through each uploaded image
        foreach ($files as $index => $file) {
            // Add image to HTML content with page break after each image (except the first one)
            if ($index !== 0) {
                $htmlContent .= '<div style="page-break-after: always;"></div>';
            }
            
            $imageData = file_get_contents($file->path());
            $base64Image = base64_encode($imageData);
            $htmlContent .= '<img src="data:image/jpeg;base64,'.$base64Image.'" />';
        }
        
        // Load HTML content into dompdf
        $dompdf->loadHtml($htmlContent);
        
        // Render PDF
        $dompdf->render();
        
        // Return the PDF as response
        return $dompdf->stream('images.pdf');
    }
}
