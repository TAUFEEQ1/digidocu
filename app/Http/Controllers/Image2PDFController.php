<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Log;
use Imagick;
use Illuminate\Support\Facades\Storage;
use Response;
use Str;

class Image2PDFController extends Controller
{
    //
    public function create(Request $request)
    {
        return view("image2pdf.create");
    }

    public function store(Request $request)
    {
        // Array of uploaded image files
        $uploadedFiles = $request->file('files');

        // Create a new Imagick instance
        $pdf = new Imagick();

        // Loop through each uploaded image file
        foreach ($uploadedFiles as $file) {
            // Save the uploaded file to a temporary directory
            $filePath = $file->storeAs('temp', $file->getClientOriginalName());
            // Get the full path of the stored file
            $fullPath = storage_path('app/' . $filePath);

            // Add the image to the PDF
            $pdf->readImage($fullPath);
        }

        // Set PDF format
        $pdf->setImageFormat('pdf');

        // Path for storing the PDF in the public directory
        $fpath = Str::random() . '.pdf';
        $publicPdfPath = public_path("conversions/".$fpath);

        // Write the PDF to the public directory
        $pdf->writeImages($publicPdfPath, true);

        // Return the public URL to access the PDF
        $publicUrl = asset("conversions/".$fpath);

        return response()->json(['pdf_url' => $publicUrl]);
    }
}
