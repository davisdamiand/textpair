<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FontPair</title>

    @php
        // Server-side initial font loading
        $fontNames = collect($fonts)->pluck('name')->map(fn($f) => str_replace(' ', '+', $f))->implode('&family=');
        $googleFontsUrl = "https://fonts.googleapis.com/css2?family={$fontNames}&display=swap";
    @endphp

    <link href="{{ $googleFontsUrl }}" rel="stylesheet" crossorigin="anonymous">

    <!-- 1. TAILWIND V3 (Compatible with html2canvas) -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- 2. TAILWIND CONFIGURATION -->
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Instrument Sans', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <!-- 3. LIBRARIES -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

    <style>
        /* Custom styles compatible with Tailwind v3 */
        input[type='range']::-webkit-slider-thumb {
            -webkit-appearance: none;
            height: 16px;
            width: 16px;
            border-radius: 50%;
            background: #18181b;
            border: 2px solid white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        /* Toast notification */
        .toast-popup {
            position: fixed;
            top: -80px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 9999;
            transition: top 0.4s cubic-bezier(0.34, 1.56, 0.64, 1), opacity 0.3s ease;
            opacity: 0;
        }

        .toast-popup.show {
            top: 24px;
            opacity: 1;
        }
    </style>
</head>

<body class="min-h-screen transition-colors duration-500 font-sans antialiased"
    :class="darkMode ? 'bg-zinc-950 text-zinc-100' : 'bg-[#f8f8f6] text-zinc-900'" x-data="fontApp()">

    <!-- Toast Notification -->
    <div id="css-toast" class="toast-popup">
        <div
            class="flex items-center gap-3 bg-zinc-900 text-white px-6 py-4 rounded-2xl shadow-2xl shadow-black/20 border border-zinc-700/50">
            <svg class="w-5 h-5 text-emerald-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="text-sm font-semibold tracking-wide">CSS copied to clipboard</span>
        </div>
    </div>

    @php
        $weightOptions = [
            '200' => 'Light',
            '400' => 'Regular',
            '700' => 'Bold'
        ];
    @endphp

    <div class="max-w-[1600px] mx-auto p-6 lg:p-10">

        <!-- Flash Messages -->
        <div class="max-w-[1600px] mx-auto px-6">
            <x-flash type="message" />
            <x-flash type="error" />
        </div>

        <div class="grid grid-cols-12 gap-8 lg:gap-12">

            {{-- LEFT PANEL (1/3) - CONTROLS --}}
            <aside class="col-span-12 lg:col-span-4">
                <form action="{{ route('fontpair.store') }}" method="POST" class="space-y-6" x-ref="mainForm">
                    @csrf
                    <div :class="darkMode ? 'bg-zinc-900 border-zinc-800' : 'bg-white border-zinc-200'"
                        class="rounded-3xl p-8 shadow-sm border sticky top-10 max-h-[90vh] overflow-y-auto hide-scrollbar">

                        <header class="mb-8">
                            <input type="text" name="projectName" x-model="projectName"
                                @input="userHasEditedName = true" placeholder="Project Name..."
                                class="text-2xl font-bold bg-transparent border-b border-zinc-200 focus:border-zinc-900 outline-none w-full pb-1">
                        </header>

                        {{-- FONT SELECTION --}}
                        <div class="space-y-6">
                            <h3 class="text-[10px] font-black tracking-widest text-zinc-400 uppercase">Typography</h3>

                            <!-- Heading Font -->
                            <div>
                                <x-font-selector label="Heading Font" model="headingFont" />
                                <div class="mt-3">
                                    <label class="text-[10px] text-zinc-400">Weight</label>
                                    <div class="mb-4">
                                        <x-option-selector label="Heading Weight" model="headingWeight"
                                            :options="$weightOptions" />
                                    </div>

                                    <div class="flex justify-between text-[10px] mb-1"><span>Letter Spacing</span><span
                                            x-text="headingLetterSpacing"></span></div>
                                    <input type="range" name="headingLetterSpacing" x-model="headingLetterSpacing"
                                        min="-0.1" max="1" step="0.001"
                                        class="w-full h-1 bg-zinc-200 rounded-lg appearance-none">
                                </div>
                            </div>

                            <hr class="border-zinc-100" />

                            <!-- Body Font -->
                            <div>
                                <x-font-selector label="Body Font" model="bodyFont" />

                                <div class="grid grid-cols-2 gap-4 mt-4">
                                    <div>
                                        <x-option-selector label="Body Weight" model="bodyWeight"
                                            :options="$weightOptions" />
                                    </div>
                                    <div>
                                        <label class="text-[10px] text-zinc-400">Size (px)</label>
                                        <input type="number" name="bodyBaseSize" x-model="bodyBaseSize"
                                            class="w-full text-xs p-2 rounded-lg border border-zinc-100 mt-2"
                                            :class="darkMode ? 'bg-zinc-700' : 'bg-white'">
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- TYPE SETTINGS --}}
                        <div class="mt-8 space-y-4">
                            <h3 class="text-[10px] font-black tracking-widest text-zinc-400 uppercase">Advanced Settings
                            </h3>

                            <div>
                                <div class="flex justify-between text-[10px] mb-1"><span>Line Height</span><span
                                        x-text="bodyLineHeight"></span></div>
                                <input type="range" name="bodyLineHeight" x-model="bodyLineHeight" min="0.5" max="2.5"
                                    step="0.01" class="w-full h-1 bg-zinc-200 rounded-lg appearance-none">
                            </div>

                            <div>
                                <div class="flex justify-between text-[10px] mb-1"><span>Paragraph Width
                                        (ch)</span><span x-text="bodyParagraphWidth"></span></div>
                                <input type="range" name="bodyParagraphWidth" x-model="bodyParagraphWidth" min="20"
                                    max="120" class="w-full h-1 bg-zinc-200 rounded-lg appearance-none">
                            </div>

                            <div>
                                <div class="flex justify-between text-[10px] mb-1"><span>Body Letter Spacing</span><span
                                        x-text="bodyLetterSpacing"></span></div>
                                <input type="range" name="bodyLetterSpacing" x-model="bodyLetterSpacing" min="-0.05"
                                    max="0.1" step="0.001" class="w-full h-1 bg-zinc-200 rounded-lg appearance-none">
                            </div>
                        </div>

                        {{-- OPTIONS --}}
                        <div class="mt-8 space-y-3">
                            <label class="flex items-center gap-3 text-xs font-medium cursor-pointer">
                                <input type="checkbox" name="darkMode" x-model="darkMode" value="1"
                                    class="w-4 h-4 rounded border-zinc-300">
                                Dark Mode
                            </label>
                            <label class="flex items-center gap-3 text-xs font-medium cursor-pointer">
                                <input type="checkbox" name="sameFontAllowed" x-model="sameFontAllowed" value="1"
                                    class="w-4 h-4 rounded border-zinc-300">
                                Allow Same Font
                            </label>
                        </div>

                        {{-- ACTIONS --}}

                        <div class="mt-10 space-y-3">
                            <x-primary-button @click="randomizePair()">
                                Random Font Pair
                            </x-primary-button>
                        </div>

                        <div class="mt-3">
                            <x-primary-button @click="showConfirmModal = true">
                                Save Favourite
                            </x-primary-button>
                        </div>

                        <div class="mt-3">
                            <x-primary-button id="export-img-btn" @click="exportImage()">
                                Export Image
                            </x-primary-button>
                        </div>

                        <div class="mt-3">
                            <x-primary-button id="export-css-btn" @click="exportCSS()">
                                Export CSS
                            </x-primary-button>
                        </div>

                    </div>
                </form>

                {{-- SAVED PAIRS --}}
                <x-save-display :savedPairs="$savedPairs" />
            </aside>

            {{-- RIGHT PANEL (2/3) - PREVIEW --}}
            <main class="col-span-12 lg:col-span-8" id="capture-area">
                <div :class="darkMode ? 'bg-zinc-900 border-zinc-800' : 'bg-white border-zinc-200'"
                    class="rounded-[3rem] p-12 lg:p-20 shadow-sm border min-h-[85vh] transition-all duration-500">

                    <div class="max-w-3xl">
                        <header class="mb-12">
                            <h2 :style="{ 
                                fontFamily: headingFont, 
                                fontWeight: headingWeight,
                                letterSpacing: headingLetterSpacing + 'em',
                                lineHeight: 1.1 
                            }" class="text-5xl lg:text-7xl tracking-tight mb-6 transition-all duration-500 uppercase">
                                Crafting the Perfect Visual Rhythm
                            </h2>
                            <p class="text-zinc-400 text-lg uppercase tracking-[0.3em]"
                                :style="{ fontFamily: headingFont, fontWeight: headingWeight }">Typography Pairing
                                Laboratory</p>
                        </header>

                        <div :style="{ 
                            fontFamily: bodyFont, 
                            fontWeight: bodyWeight, 
                            fontSize: bodyBaseSize + 'px', 
                            lineHeight: bodyLineHeight, 
                            letterSpacing: bodyLetterSpacing + 'em',
                            maxWidth: bodyParagraphWidth + 'ch' 
                        }" class="space-y-8 leading-relaxed transition-all duration-300 opacity-90">
                            <p>
                                FontPair is your creative space to experiment with the delicate balance between headings
                                and copy. Whether you're building a sleek SaaS landing page or a deep-reading editorial,
                                the right font pairing dictates the soul of your interface.
                            </p>
                            <p>
                                Use these sliders to find the "sweet spot" where legibility meets personality. A great
                                pair isn't just about contrast—it's about how the fonts converse with one another. When
                                they harmonize, your content speaks louder than the words themselves. This longer
                                passage
                                allows you to truly feel the texture of the typeface as it settles into a block of text,
                                helping you verify that the line height and paragraph width work together to prevent eye
                                fatigue during sustained reading sessions across any device or screen.
                            </p>
                        </div>
                    </div>

                </div>
            </main>

        </div>
    </div>

    <script>
        function fontApp() {
            const latest = @json($latestPair);
            return {
                fonts: @json($fonts),
                userHasEditedName: latest ? true : false,
                showConfirmModal: false,

                // INITIALIZE FROM DATABASE
                projectName: latest ? latest.name : 'New Pairing',
                darkMode: latest ? Boolean(latest.is_dark_mode) : false,
                sameFontAllowed: latest ? Boolean(latest.same_font_allowed) : true,

                headingFont: latest ? latest.heading.name : 'Aboreto',
                headingWeight: latest ? latest.heading.weight : 700,
                headingLetterSpacing: latest ? latest.heading.letter_spacing : 0.355,

                bodyFont: latest ? latest.body.name : 'Mukta Vaani',
                bodyWeight: latest ? latest.body.weight : 200,
                bodyBaseSize: latest ? latest.body.base_size : 43,
                bodyLineHeight: latest ? latest.body.line_height : 0.92,
                bodyParagraphWidth: latest ? latest.body.paragraph_width : 81,
                bodyLetterSpacing: latest ? latest.body.letter_spacing : -0.027,

                init() {
                    this.loadFont(this.headingFont);
                    this.loadFont(this.bodyFont);

                    // If we don't have a saved pair yet, start the auto-namer
                    if (!latest) {
                        this.updateDefaultName();
                        this.$watch('headingFont', () => {
                            this.loadFont(this.headingFont);
                            this.updateDefaultName();
                        });
                        this.$watch('bodyFont', () => {
                            this.loadFont(this.bodyFont);
                            this.updateDefaultName();
                        });
                    } else {
                        // Just watch for changes to load fonts, don't change name
                        this.$watch('headingFont', () => this.loadFont(this.headingFont));
                        this.$watch('bodyFont', () => this.loadFont(this.bodyFont));
                    }
                },

                updateDefaultName() {
                    if (!this.userHasEditedName) {
                        this.projectName = `${this.headingFont} + ${this.bodyFont}`;
                    }
                },

                randomizePair() {
                    const hIndex = Math.floor(Math.random() * this.fonts.length);
                    let bIndex = Math.floor(Math.random() * this.fonts.length);

                    // Avoid same font if not allowed
                    if (!this.sameFontAllowed && hIndex === bIndex && this.fonts.length > 1) {
                        while (bIndex === hIndex) {
                            bIndex = Math.floor(Math.random() * this.fonts.length);
                        }
                    }

                    this.headingFont = this.fonts[hIndex].name;
                    this.bodyFont = this.fonts[bIndex].name;

                    // Randomize weights from the options we have (200, 400, 700)
                    const weights = [200, 400, 700];
                    this.headingWeight = weights[Math.floor(Math.random() * weights.length)];
                    this.bodyWeight = weights[Math.floor(Math.random() * weights.length)];

                    // Randomize some other values for fun
                    this.headingLetterSpacing = (Math.random() * 0.4).toFixed(3);
                    this.bodyLetterSpacing = (Math.random() * 0.1 - 0.05).toFixed(3);
                    this.bodyLineHeight = (Math.random() * 1.5 + 0.8).toFixed(2);
                    this.bodyParagraphWidth = (Math.random() * 100 + 20).toFixed(0);

                    this.bodyBaseSize = Math.floor(Math.random() * 35) + 1;
                },

                exportImage() {
                    const captureArea = document.getElementById('capture-area');
                    const exportBtn = document.getElementById('export-img-btn');

                    if (exportBtn) {
                        exportBtn.innerText = "Processing...";
                        exportBtn.disabled = true;
                    }

                    // 1. Wait for Google Fonts to render
                    document.fonts.ready.then(() => {

                        // 2. Use html2canvas (Now works because Tailwind v3 uses standard HEX colors)
                        html2canvas(captureArea, {
                            useCORS: true, // Required for Google Fonts
                            scale: 2, // High Resolution
                            backgroundColor: this.darkMode ? '#18181b' : '#ffffff',
                        }).then(canvas => {
                            const link = document.createElement('a');
                            let safeName = (this.projectName || 'typography').replace(/[^a-z0-9]/gi, '-').toLowerCase();
                            link.download = `${safeName}.png`;
                            link.href = canvas.toDataURL("image/png");
                            document.body.appendChild(link);
                            link.click();
                            document.body.removeChild(link);
                        })
                            .catch(err => {
                                console.error("Export failed:", err);
                                alert("Could not generate image. Check console.");
                            })
                            .finally(() => {
                                if (exportBtn) {
                                    exportBtn.innerText = "Export Image";
                                    exportBtn.disabled = false;
                                }
                            });
                    });
                },

                exportCSS() {
                    const btn = document.getElementById('export-css-btn');
                    const formattedHeading = this.headingFont.replace(/\s+/g, '+');
                    const formattedBody = this.bodyFont.replace(/\s+/g, '+');

                    const css = [
                        `/* ${this.projectName || 'Typography'} */`,
                        `/* Google Fonts Import */`,
                        `@import url('https://fonts.googleapis.com/css2?family=${formattedHeading}:wght@200;400;700&family=${formattedBody}:wght@200;400;700&display=swap');`,
                        ``,
                        `/* Heading */`,
                        `h1, h2, h3 {`,
                        `    font-family: '${this.headingFont}', sans-serif;`,
                        `    font-weight: ${this.headingWeight};`,
                        `    letter-spacing: ${this.headingLetterSpacing}em;`,
                        `    line-height: 1.1;`,
                        `}`,
                        ``,
                        `/* Body */`,
                        `p, body {`,
                        `    font-family: '${this.bodyFont}', sans-serif;`,
                        `    font-weight: ${this.bodyWeight};`,
                        `    font-size: ${this.bodyBaseSize}px;`,
                        `    line-height: ${this.bodyLineHeight};`,
                        `    letter-spacing: ${this.bodyLetterSpacing}em;`,
                        `    max-width: ${this.bodyParagraphWidth}ch;`,
                        `}`,
                    ].join('\n');

                    // 1. Download as .txt
                    const blob = new Blob([css], { type: 'text/plain' });
                    const url = URL.createObjectURL(blob);
                    const link = document.createElement('a');
                    let safeName = (this.projectName || 'typography').replace(/[^a-z0-9]/gi, '-').toLowerCase();
                    link.download = `${safeName}.txt`;
                    link.href = url;
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                    URL.revokeObjectURL(url);

                    // 2. Copy to clipboard + show toast
                    navigator.clipboard.writeText(css)
                        .then(() => this.showToast())
                        .catch(() => { }); // Download already happened above
                },

                showToast() {
                    const toast = document.getElementById('css-toast');
                    if (!toast) return;
                    toast.classList.add('show');
                    setTimeout(() => toast.classList.remove('show'), 2800);
                },

                // CLEAN FONT LOADING FUNCTION
                loadFont(font) {
                    if (!font) return;

                    // Regex replaces ALL spaces with + (Fixes URL crashes)
                    const formattedFont = font.replace(/\s+/g, '+');
                    const href = `https://fonts.googleapis.com/css2?family=${formattedFont}:wght@200;300;400;500;600;700;800&display=swap`;

                    // Check if font is already loaded
                    if (document.querySelector(`link[href="${href}"]`)) return;

                    const link = document.createElement('link');
                    link.rel = 'stylesheet';
                    link.href = href;
                    document.head.appendChild(link);
                },

                loadSavedPair(pair) {
                    this.userHasEditedName = true;
                    this.projectName = pair.name;
                    this.darkMode = Boolean(pair.is_dark_mode);
                    this.sameFontAllowed = Boolean(pair.same_font_allowed);

                    this.headingFont = pair.heading.name;
                    this.headingLetterSpacing = pair.heading.letter_spacing;

                    this.bodyFont = pair.body.name;
                    this.bodyWeight = pair.body.weight;
                    this.bodyBaseSize = pair.body.base_size;
                    this.bodyLineHeight = pair.body.line_height;
                    this.bodyParagraphWidth = pair.body.paragraph_width;
                    this.bodyLetterSpacing = pair.body.letter_spacing;

                    // Load the fonts immediately
                    this.loadFont(this.headingFont);
                    this.loadFont(this.bodyFont);
                }
            }
        }
    </script>
</body>
<x-confirm_box show="showConfirmModal" name="projectName" form-ref="mainForm" />

</html>