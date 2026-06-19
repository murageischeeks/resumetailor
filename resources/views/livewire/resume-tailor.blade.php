<div>
    {{-- Error Alert --}}
    @if ($errorMessage)
        <div class="mb-8 flex items-start gap-3 px-5 py-4 rounded-2xl border border-red-500/20 bg-red-500/10 text-red-300">
            <svg class="w-5 h-5 mt-0.5 shrink-0 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>
                <p class="font-semibold text-sm text-red-300">Something went wrong</p>
                <p class="text-sm text-red-400/80 mt-0.5">{{ $errorMessage }}</p>
            </div>
        </div>
    @endif

    {{-- ========================= RESULT VIEW ========================= --}}
    @if($tailoredResume)
        <div class="space-y-6">
            {{-- Success Banner --}}
            <div class="flex items-center gap-4 glass-card-bright rounded-2xl px-6 py-4">
                <div class="check-animate w-10 h-10 rounded-full bg-emerald-500/20 border border-emerald-500/30 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-white font-semibold text-sm">Resume tailored successfully!</p>
                    <p class="text-white/40 text-xs mt-0.5">Your document has been restructured and optimized for this role.</p>
                </div>
                <div class="flex items-center gap-3">
                    @if($requestId)
                    <a href="{{ route('download.pdf', $requestId) }}" target="_blank"
                       class="btn-download flex items-center gap-2 px-4 py-2.5 rounded-xl text-white text-sm font-semibold shadow-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Download PDF
                    </a>
                    @endif
                    <button wire:click="startOver"
                            class="btn-secondary flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        New Resume
                    </button>
                </div>
            </div>

            {{-- Document Preview --}}
            <div class="glass-card rounded-2xl overflow-hidden">
                {{-- Doc Header Bar --}}
                <div class="flex items-center gap-2 px-5 py-3 border-b border-white/5">
                    <div class="w-3 h-3 rounded-full bg-red-500/60"></div>
                    <div class="w-3 h-3 rounded-full bg-yellow-500/60"></div>
                    <div class="w-3 h-3 rounded-full bg-emerald-500/60"></div>
                    <span class="ml-3 text-white/25 text-xs font-medium">Tailored Resume Preview</span>
                </div>
                {{-- Prose Content --}}
                <div class="result-prose max-w-none p-8 md:p-10 text-sm md:text-base max-h-[70vh] overflow-y-auto">
                    {!! Str::markdown($tailoredResume) !!}
                </div>
            </div>

            {{-- Bottom CTA Row --}}
            <div class="flex items-center justify-between px-1">
                <p class="text-white/25 text-xs">Tip: Review and personalize before sending.</p>
                <div class="flex gap-3">
                    @if($requestId)
                    <a href="{{ route('download.pdf', $requestId) }}" target="_blank"
                       class="btn-download flex items-center gap-2 px-5 py-2.5 rounded-xl text-white text-sm font-semibold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Download PDF
                    </a>
                    @endif
                    <button wire:click="startOver"
                            class="btn-secondary flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold">
                        ← Start Over
                    </button>
                </div>
            </div>
        </div>

    {{-- ========================= FORM VIEW ========================= --}}
    @else
        <form wire:submit="process">
            <div class="grid md:grid-cols-2 gap-6">

                {{-- LEFT COLUMN --}}
                <div class="space-y-5">
                    {{-- Step label --}}
                    <div class="flex items-center gap-3 mb-2">
                        <div class="step-dot active">1</div>
                        <span class="text-white/50 text-sm font-medium">Paste job description</span>
                    </div>

                    {{-- Job Description Textarea --}}
                    <div class="glass-card rounded-2xl p-5">
                        <label class="block text-xs font-semibold uppercase tracking-widest mb-3">
                            Job Description
                        </label>
                        <textarea
                            id="jobDescriptionText"
                            wire:model="jobDescriptionText"
                            rows="10"
                            class="dark-input w-full rounded-xl p-4 text-sm resize-none"
                            placeholder="Copy the entire job posting page and paste it here — messy text, navigation links and all. Our AI will extract what matters."
                            required
                        ></textarea>
                        @error('jobDescriptionText')
                            <p class="mt-2 text-red-400 text-xs flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                {{-- RIGHT COLUMN --}}
                <div class="space-y-5">
                    {{-- Step label --}}
                    <div class="flex items-center gap-3 mb-2">
                        <div class="step-dot active">2</div>
                        <span class="text-white/50 text-sm font-medium">Upload your document</span>
                    </div>

                    {{-- Document Type Selector --}}
                    <div class="glass-card rounded-2xl p-5">
                        <label class="block text-xs font-semibold uppercase tracking-widest mb-3">
                            Document Type
                        </label>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="type-card rounded-xl p-4 flex flex-col gap-1.5 {{ old('documentType', 'resume') === 'resume' ? '' : '' }}"
                                   id="card-resume"
                                   onclick="selectType('resume')">
                                <div class="flex items-center justify-between">
                                    <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <div id="dot-resume" class="w-4 h-4 rounded-full border-2 border-indigo-500 bg-indigo-500 flex items-center justify-center">
                                        <div class="w-1.5 h-1.5 rounded-full bg-white"></div>
                                    </div>
                                </div>
                                <p class="text-white font-semibold text-sm">Resume</p>
                                <p class="text-white/35 text-xs leading-relaxed">Concise, 1–2 pages, job-focused</p>
                            </label>

                            <label class="type-card rounded-xl p-4 flex flex-col gap-1.5"
                                   id="card-cv"
                                   onclick="selectType('cv')">
                                <div class="flex items-center justify-between">
                                    <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                    </svg>
                                    <div id="dot-cv" class="w-4 h-4 rounded-full border-2 border-white/20 bg-transparent flex items-center justify-center">
                                    </div>
                                </div>
                                <p class="text-white font-semibold text-sm">CV</p>
                                <p class="text-white/35 text-xs leading-relaxed">Comprehensive, detailed, academic</p>
                            </label>
                        </div>
                        <input type="hidden" id="documentType" wire:model="documentType" value="resume">
                    </div>

                    {{-- File Upload --}}
                    <div class="glass-card rounded-2xl p-5">
                        <label class="block text-xs font-semibold uppercase tracking-widest mb-3">
                            Upload Document
                        </label>
                        <label for="resumeFile" class="drop-zone {{ $resumeFile ? 'has-file' : '' }} rounded-xl p-6 flex flex-col items-center justify-center gap-3 cursor-pointer">
                            @if ($resumeFile)
                                <div class="check-animate w-12 h-12 rounded-full bg-emerald-500/20 border border-emerald-500/30 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <div class="text-center">
                                    <p class="text-emerald-400 font-semibold text-sm">{{ $resumeFile->getClientOriginalName() }}</p>
                                    <p class="text-white/30 text-xs mt-1">Click to replace</p>
                                </div>
                            @else
                                <div class="w-12 h-12 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                    </svg>
                                </div>
                                <div class="text-center">
                                    <p class="text-white/60 text-sm font-medium">Drop your file here</p>
                                    <p class="text-white/25 text-xs mt-0.5">or click to browse</p>
                                </div>
                                <span class="text-xs text-white/20 border border-white/10 rounded-full px-3 py-1">PDF or DOCX · Max 10MB</span>
                            @endif
                            <input id="resumeFile" wire:model="resumeFile" type="file" class="sr-only" accept=".pdf,.docx" required>
                        </label>
                        @error('resumeFile')
                            <p class="mt-2 text-red-400 text-xs flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Submit --}}
                    <button type="submit"
                            wire:loading.attr="disabled"
                            class="btn-primary relative w-full flex items-center justify-center gap-3 px-6 py-4 rounded-2xl text-white font-bold text-base shadow-xl disabled:opacity-60 disabled:cursor-not-allowed">
                        <span wire:loading.remove wire:target="process" class="flex items-center gap-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                            Tailor My Document
                        </span>
                        <span wire:loading wire:target="process" class="flex items-center gap-3">
                            <svg class="animate-spin w-5 h-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            AI is tailoring your document...
                        </span>
                    </button>

                    {{-- Trust signals --}}
                    <div class="flex items-center justify-center gap-6 pt-2">
                        <div class="flex items-center gap-1.5 text-white/20">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            <span class="text-xs">Secure</span>
                        </div>
                        <div class="flex items-center gap-1.5 text-white/20">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            <span class="text-xs">AI-Powered</span>
                        </div>
                        <div class="flex items-center gap-1.5 text-white/20">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span class="text-xs">Free to Use</span>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    @endif
</div>

<script>
function selectType(type) {
    const resumeCard = document.getElementById('card-resume');
    const cvCard = document.getElementById('card-cv');
    const dotResume = document.getElementById('dot-resume');
    const dotCv = document.getElementById('dot-cv');
    const input = document.getElementById('documentType');

    if (type === 'resume') {
        resumeCard.classList.add('selected');
        cvCard.classList.remove('selected');
        dotResume.innerHTML = '<div class="w-1.5 h-1.5 rounded-full bg-white"></div>';
        dotResume.classList.add('bg-indigo-500', 'border-indigo-500');
        dotResume.classList.remove('border-white/20');
        dotCv.innerHTML = '';
        dotCv.classList.remove('bg-indigo-500', 'border-indigo-500');
        dotCv.classList.add('border-white/20');
    } else {
        cvCard.classList.add('selected');
        resumeCard.classList.remove('selected');
        dotCv.innerHTML = '<div class="w-1.5 h-1.5 rounded-full bg-white"></div>';
        dotCv.classList.add('bg-indigo-500', 'border-indigo-500');
        dotCv.classList.remove('border-white/20');
        dotResume.innerHTML = '';
        dotResume.classList.remove('bg-indigo-500', 'border-indigo-500');
        dotResume.classList.add('border-white/20');
    }

    // Sync with Livewire
    if (input) {
        input.value = type;
        input.dispatchEvent(new Event('input'));
    }
}

// Init selection
document.addEventListener('DOMContentLoaded', () => selectType('resume'));
</script>
