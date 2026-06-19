<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ResumeAI — Tailor Your Resume with AI</title>
    <meta name="description" content="Paste any job description and upload your resume. Our AI instantly tailors it to the role, so you always put your best foot forward.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <style>
        * { font-family: 'Inter', sans-serif; }

        body {
            background: #0a0a0f;
            min-height: 100vh;
        }

        /* Animated mesh background */
        .bg-mesh {
            background-color: #0a0a0f;
            background-image:
                radial-gradient(ellipse 80% 80% at 20% -10%, rgba(99, 102, 241, 0.15) 0%, transparent 60%),
                radial-gradient(ellipse 60% 60% at 80% 110%, rgba(168, 85, 247, 0.12) 0%, transparent 60%),
                radial-gradient(ellipse 40% 40% at 50% 50%, rgba(14, 165, 233, 0.05) 0%, transparent 70%);
        }

        /* Glassmorphism card */
        .glass-card {
            background: rgba(255, 255, 255, 0.04);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        .glass-card-bright {
            background: rgba(255, 255, 255, 0.06);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* Gradient text */
        .gradient-text {
            background: linear-gradient(135deg, #a5b4fc 0%, #818cf8 40%, #c084fc 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Gradient border on focus */
        .gradient-focus:focus {
            outline: none;
            box-shadow: 0 0 0 2px rgba(129, 140, 248, 0.5);
        }

        /* Primary button */
        .btn-primary {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0; left: -100%;
            width: 100%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.15), transparent);
            transition: left 0.5s ease;
        }
        .btn-primary:hover::before { left: 100%; }
        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 32px rgba(99, 102, 241, 0.4);
        }
        .btn-primary:active { transform: translateY(0); }

        /* Secondary button */
        .btn-secondary {
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.12);
            color: rgba(255,255,255,0.7);
            transition: all 0.3s ease;
        }
        .btn-secondary:hover {
            background: rgba(255,255,255,0.1);
            border-color: rgba(255,255,255,0.2);
            color: white;
            transform: translateY(-1px);
        }

        /* Download button */
        .btn-download {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            transition: all 0.3s ease;
        }
        .btn-download:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 24px rgba(16, 185, 129, 0.35);
        }

        /* Textarea & select */
        .dark-input {
            background: rgba(255,255,255,0.05) !important;
            border: 1px solid rgba(255,255,255,0.1) !important;
            color: rgba(255,255,255,0.9) !important;
            transition: all 0.2s ease;
        }
        .dark-input::placeholder { color: rgba(255,255,255,0.3) !important; }
        .dark-input:focus {
            background: rgba(255,255,255,0.08) !important;
            border-color: rgba(129, 140, 248, 0.6) !important;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15) !important;
            outline: none !important;
        }
        .dark-input option { background: #1e1e2e; color: white; }

        /* Drop zone */
        .drop-zone {
            border: 2px dashed rgba(255,255,255,0.12);
            background: rgba(255,255,255,0.03);
            transition: all 0.3s ease;
            cursor: pointer;
        }
        .drop-zone:hover {
            border-color: rgba(129, 140, 248, 0.5);
            background: rgba(99, 102, 241, 0.06);
        }
        .drop-zone.has-file {
            border-color: rgba(16, 185, 129, 0.5);
            background: rgba(16, 185, 129, 0.05);
        }

        /* Type selector card */
        .type-card {
            border: 2px solid rgba(255,255,255,0.08);
            background: rgba(255,255,255,0.03);
            transition: all 0.2s ease;
            cursor: pointer;
        }
        .type-card:hover {
            border-color: rgba(129, 140, 248, 0.4);
            background: rgba(99, 102, 241, 0.06);
        }
        .type-card.selected {
            border-color: rgba(129, 140, 248, 0.7);
            background: rgba(99, 102, 241, 0.12);
        }

        /* Result prose */
        .result-prose {
            background: #111118;
            border: 1px solid rgba(255,255,255,0.08);
            color: rgba(255, 255, 255, 0.88);
            line-height: 1.75;
        }
        .result-prose h1, .result-prose h2, .result-prose h3 { color: white; font-weight: 700; }
        .result-prose h1 { font-size: 1.6rem; margin-bottom: 0.25rem; letter-spacing: -0.02em; }
        .result-prose h2 {
            font-size: 1.05rem;
            margin-top: 1.5rem;
            margin-bottom: 0.5rem;
            padding-bottom: 0.4rem;
            border-bottom: 1px solid rgba(129, 140, 248, 0.25);
            color: #a5b4fc;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }
        .result-prose h3 { font-size: 1rem; color: rgba(255,255,255,0.9); margin-bottom: 0.2rem; }
        .result-prose p { color: rgba(255,255,255,0.75); margin-bottom: 0.6rem; }
        .result-prose ul { list-style: none; padding: 0; }
        .result-prose ul li { padding-left: 1.2rem; position: relative; color: rgba(255,255,255,0.75); margin-bottom: 0.3rem; }
        .result-prose ul li::before { content: '•'; position: absolute; left: 0; color: #818cf8; font-weight: bold; }
        .result-prose strong { color: rgba(255,255,255,0.95); font-weight: 600; }
        .result-prose a { color: #818cf8; text-decoration: underline; }

        /* Step indicator */
        .step-dot {
            width: 28px; height: 28px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.75rem; font-weight: 700;
            transition: all 0.3s ease;
        }
        .step-dot.active {
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            color: white;
            box-shadow: 0 0 0 4px rgba(99,102,241,0.2);
        }
        .step-dot.inactive {
            background: rgba(255,255,255,0.06);
            color: rgba(255,255,255,0.3);
            border: 1px solid rgba(255,255,255,0.08);
        }

        /* Spinner overlay */
        .processing-overlay {
            background: rgba(10, 10, 15, 0.85);
            backdrop-filter: blur(8px);
        }

        /* Glowing dot animation */
        @keyframes pulse-glow {
            0%, 100% { opacity: 1; box-shadow: 0 0 8px rgba(99, 102, 241, 0.8); }
            50% { opacity: 0.7; box-shadow: 0 0 20px rgba(99, 102, 241, 1); }
        }
        .glow-dot { animation: pulse-glow 2s ease-in-out infinite; }

        /* Badge */
        .ai-badge {
            background: linear-gradient(135deg, rgba(99,102,241,0.15), rgba(139,92,246,0.15));
            border: 1px solid rgba(129, 140, 248, 0.25);
            color: #a5b4fc;
        }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: rgba(255,255,255,0.03); }
        ::-webkit-scrollbar-thumb { background: rgba(129, 140, 248, 0.3); border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(129, 140, 248, 0.5); }

        /* Floating particles */
        @keyframes float-particle {
            0%, 100% { transform: translateY(0px) translateX(0px); opacity: 0.3; }
            33% { transform: translateY(-20px) translateX(10px); opacity: 0.6; }
            66% { transform: translateY(10px) translateX(-8px); opacity: 0.2; }
        }
        .particle { animation: float-particle linear infinite; }

        /* Success checkmark animation */
        @keyframes check-appear {
            from { transform: scale(0) rotate(-45deg); opacity: 0; }
            to { transform: scale(1) rotate(0deg); opacity: 1; }
        }
        .check-animate { animation: check-appear 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards; }

        label { color: rgba(255,255,255,0.6) !important; }
    </style>
</head>
<body class="bg-mesh antialiased">

    {{-- Floating particles --}}
    <div class="fixed inset-0 pointer-events-none overflow-hidden z-0">
        <div class="particle absolute top-1/4 left-1/5 w-1.5 h-1.5 rounded-full bg-indigo-400" style="animation-duration: 8s; animation-delay: 0s;"></div>
        <div class="particle absolute top-1/3 right-1/4 w-1 h-1 rounded-full bg-purple-400" style="animation-duration: 11s; animation-delay: 2s;"></div>
        <div class="particle absolute bottom-1/3 left-1/3 w-2 h-2 rounded-full bg-indigo-300 opacity-20" style="animation-duration: 14s; animation-delay: 5s;"></div>
        <div class="particle absolute bottom-1/4 right-1/3 w-1 h-1 rounded-full bg-violet-400" style="animation-duration: 9s; animation-delay: 1s;"></div>
    </div>

    {{-- Header --}}
    <header class="relative z-10 border-b border-white/5">
        <div class="max-w-6xl mx-auto px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center shadow-lg shadow-indigo-500/30">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                    </svg>
                </div>
                <span class="text-white font-bold text-lg tracking-tight">ResumeAI</span>
                <span class="ai-badge text-xs font-semibold px-2.5 py-1 rounded-full ml-1">Powered by Groq</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="glow-dot w-2 h-2 rounded-full bg-indigo-400"></div>
                <span class="text-white/40 text-xs font-medium">Live</span>
            </div>
        </div>
    </header>

    {{-- Main --}}
    <main class="relative z-10 max-w-5xl mx-auto px-6 py-12">

        {{-- Hero --}}
        <div class="text-center mb-12">
            <div class="inline-flex items-center gap-2 ai-badge px-4 py-2 rounded-full text-sm font-medium mb-6">
                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
                AI-Powered · Llama 3.3 70B · Instant Results
            </div>
            <h1 class="text-5xl md:text-6xl font-black text-white tracking-tight leading-none mb-4">
                Tailor your resume<br><span class="gradient-text">to any job, instantly.</span>
            </h1>
            <p class="text-white/50 text-lg max-w-xl mx-auto leading-relaxed">
                Paste a job description, upload your document. Our AI rebuilds your resume around the role in seconds.
            </p>
        </div>

        {{-- Component --}}
        <livewire:resume-tailor />

    </main>

    {{-- Footer --}}
    <footer class="relative z-10 border-t border-white/5 mt-16">
        <div class="max-w-6xl mx-auto px-6 py-6 flex flex-col sm:flex-row items-center justify-between gap-3">
            <p class="text-white/25 text-sm">© {{ date('Y') }} ResumeAI. All rights reserved.</p>
            <p class="text-white/20 text-xs">Your data is processed securely and never stored permanently.</p>
        </div>
    </footer>

    @livewireScripts
</body>
</html>
