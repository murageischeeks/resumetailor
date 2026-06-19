<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Exception;

class AITailoringService
{
    /**
     * Tailors a resume using Groq's Llama 3 API.
     */
    public function tailor(string $resumeText, string $jobDescription, string $documentType = 'resume'): string
    {
        $apiKey = env('GROQ_API_KEY');

        if (empty($apiKey)) {
            // For the sake of the prototype, if no API key is provided, we return a mock response.
            return "### ⚠️ API Key Missing\n\nPlease add `GROQ_API_KEY` to your `.env` file to see the actual AI-tailored resume.\n\n" . 
                   "**Mock Tailored Resume:**\n\n" .
                   "**Professional Summary:**\nHighly motivated professional with skills aligning perfectly with the job description. Ready to deliver immediate impact.\n\n" .
                   "**Key Skills:**\n- Tailored Skill 1\n- Tailored Skill 2\n- Tailored Skill 3\n\n" .
                   "**Experience:**\n- Restructured bullet points to emphasize impact and relevance to the target role.";
        }

        $url = 'https://api.groq.com/openai/v1/chat/completions';

        $docTypeName = $documentType === 'cv' ? 'Curriculum Vitae (CV)' : 'Resume';
        $styleInstruction = $documentType === 'cv' 
            ? "Since this is a CV, keep it comprehensive and detailed, highlighting academic, research, and extensive professional background while tailoring it to the job."
            : "Since this is a Resume, keep it concise, punchy, and highly focused on the immediate requirements of the job posting.";

        $template = $documentType === 'cv' ? 
            "**TEMPLATE SKELETON TO FOLLOW (CV):**\n" .
            "[NAME UPPERCASE]\n" .
            "[Phone] | [Email]\n" .
            "LinkedIn | [Location]\n" .
            "[Target Job Title / Key Roles]\n\n" .
            "**Professional Summary**\n[Comprehensive paragraph tailored to the job]\n\n" .
            "**Technical Experience**\n• [Category]: [Skills]\n\n" .
            "**Soft Skills**\n• [Skill Name]: [Detailed context showing application]\n\n" .
            "**Work Experience**\n1. [Company Name]\n[Location]\n[Job Title] — [Duration]\n[Brief context paragraph]\n• [Achievement Bullet 1]\n• [Achievement Bullet 2]\n\n" .
            "**Projects**\n[Project Name]\n[Project Summary]\n◦ [Technical/Functional Bullet 1]\nTechnologies: [Tech Stack]\n\n" .
            "**Education**\n[Institution Name] – [Location]\n[Degree Name] – [Dates]\n• Coursework: [Relevant Courses]\n\n" .
            "**Certifications**\n• [Certification Name]\n\n" .
            "**Languages**\n• [Language]: [Proficiency]\n\n" .
            "**Interests**\n• [List of interests]"
            : 
            "**TEMPLATE SKELETON TO FOLLOW (Resume):**\n" .
            "[NAME UPPERCASE]\n" .
            "[Phone] | [Email] | [Location] | LinkedIn\n" .
            "[Target Job Title]\n\n" .
            "**Professional Summary**\n[Punchy, results-driven paragraph tailored to the job]\n\n" .
            "**Technical Skills**\n• [Category]: [Comma-separated skills]\n\n" .
            "**Work Experience**\n[Company Name] | [Location]\n[Job Title] | [Dates]\n• [Impact-focused Bullet 1]\n• [Impact-focused Bullet 2]\n\n" .
            "**Projects**\n[Project Name]\n• [Impact-focused Bullet 1]\n\n" .
            "**Education & Professional Development**\n[Institution Name] | [Location]\n[Degree Name] | [Dates]\n• Relevant Coursework: [Courses]\n\n" .
            "**Certifications**\n• [Certification Name]";

        $prompt = "I will provide you with a user's current {$docTypeName} and raw text pasted from a job posting webpage.\n" .
                  "Your first task is to extract the core job requirements.\n" .
                  "Then, completely rewrite and tailor the {$docTypeName} to specifically target those extracted requirements.\n\n" .
                  $styleInstruction . "\n\n" .
                  "**CRITICAL FORMATTING INSTRUCTIONS:**\n" .
                  "You MUST format the final document using the EXACT structure and typography style shown in the template below. " .
                  "Do not deviate from the headings. Map the user's data into this exact markdown skeleton.\n\n" .
                  $template . "\n\n" .
                  "OUTPUT FORMAT: Return ONLY the tailored {$docTypeName} in Markdown format matching the skeleton above. Do not include conversational text.\n\n" .
                  "--- RAW PASTED JOB DESCRIPTION ---\n" . $jobDescription . "\n\n" .
                  "--- CURRENT " . strtoupper($documentType) . " ---\n" . $resumeText;

        $response = Http::timeout(120)
            ->withoutVerifying()
            ->withToken($apiKey)
            ->post($url, [
                'model' => 'llama-3.3-70b-versatile',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are an expert career coach and professional resume writer.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'temperature' => 0.7,
            ]);

        if (!$response->successful()) {
            throw new Exception("AI Service Error: " . $response->body());
        }

        $data = $response->json();
        
        return $data['choices'][0]['message']['content'] ?? "Error extracting AI response.";
    }
}
