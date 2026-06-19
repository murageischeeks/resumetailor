<?php

namespace App\Http\Controllers;

use App\Models\TailorRequest;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

class PdfController extends Controller
{
    public function download($id)
    {
        $request = TailorRequest::findOrFail($id);

        if (!$request->tailored_content) {
            abort(404, 'No tailored content found for this request.');
        }

        // Convert Markdown to HTML
        $htmlContent = Str::markdown($request->tailored_content);

        // Add some basic styling for the PDF
        $styledHtml = '
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset="utf-8">
                <title>Tailored Resume</title>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        line-height: 1.6;
                        color: #333;
                        font-size: 14px;
                    }
                    h1 { color: #111; font-size: 24px; border-bottom: 2px solid #ccc; padding-bottom: 5px; }
                    h2 { color: #222; font-size: 20px; margin-top: 20px; }
                    h3 { color: #333; font-size: 16px; margin-top: 15px; }
                    p { margin-bottom: 10px; }
                    ul { margin-bottom: 10px; padding-left: 20px; }
                    li { margin-bottom: 5px; }
                </style>
            </head>
            <body>
                ' . $htmlContent . '
            </body>
            </html>
        ';

        $pdf = Pdf::loadHTML($styledHtml);
        
        return $pdf->download('Tailored_Resume_' . $request->id . '.pdf');
    }
}
