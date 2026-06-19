<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\TailorRequest;
use Smalot\PdfParser\Parser;

class ResumeTailor extends Component
{
    use WithFileUploads;

    public $resumeFile;
    public $jobDescriptionText;
    public $documentType = 'resume'; // default to resume
    public $isProcessing = false;
    public $tailoredResume = null;
    public $errorMessage = null;
    public $requestId = null;

    public function process(\App\Services\AITailoringService $aiTailor)
    {
        set_time_limit(120); // Allow up to 2 minutes for AI processing

        $this->validate([
            'resumeFile' => 'required|file|mimes:pdf,docx|max:10240', // 10MB Max
            'documentType' => 'required|in:resume,cv',
            'jobDescriptionText' => 'required|string|min:10'
        ]);

        $this->isProcessing = true;
        $this->errorMessage = null;
        $this->requestId = null;

        try {
            // 1. Parse File based on extension
            $extension = strtolower($this->resumeFile->getClientOriginalExtension());
            $resumeText = '';

            if ($extension === 'pdf') {
                $parser = new Parser();
                $pdf = $parser->parseFile($this->resumeFile->getRealPath());
                $resumeText = $pdf->getText();
            } elseif ($extension === 'docx') {
                $resumeText = $this->readDocx($this->resumeFile->getRealPath());
            }

            if (empty(trim($resumeText))) {
                throw new \Exception("Could not extract text from the document. It might be scanned, image-based, or an invalid format.");
            }

            $jobDescription = $this->jobDescriptionText;

            // 3. Create Request Record
            $request = TailorRequest::create([
                'original_content' => $resumeText,
                'job_url' => 'pasted-text', // legacy field, just set a dummy value
                'job_description' => $jobDescription,
                'status' => 'processing'
            ]);

            // 4. Tailor Resume via AI
            $tailoredContent = $aiTailor->tailor($resumeText, $jobDescription, $this->documentType);

            // 5. Update Record
            $request->update([
                'tailored_content' => $tailoredContent,
                'status' => 'completed'
            ]);

            $this->tailoredResume = $tailoredContent;
            $this->requestId = $request->id;
            
            session()->flash('message', 'Resume tailored successfully!');

        } catch (\Exception $e) {
            $this->errorMessage = "Error: " . $e->getMessage();
        }

        $this->isProcessing = false;
    }

    public function startOver()
    {
        $this->tailoredResume = null;
        $this->requestId = null;
        $this->jobDescriptionText = '';
        $this->resumeFile = null;
    }

    private function readDocx($filePath)
    {
        $zip = new \ZipArchive;
        $content = '';
        if ($zip->open($filePath) === TRUE) {
            $xml = $zip->getFromName('word/document.xml');
            if ($xml) {
                $dom = new \DOMDocument;
                $dom->loadXML($xml, LIBXML_NOENT | LIBXML_XINCLUDE | LIBXML_NOERROR | LIBXML_NOWARNING);
                $content = $dom->textContent;
            }
            $zip->close();
        }
        return $content;
    }

    public function render()
    {
        return view('livewire.resume-tailor');
    }
}
