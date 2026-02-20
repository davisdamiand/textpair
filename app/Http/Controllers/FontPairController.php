<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FontPair;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class FontPairController extends Controller
{
    /**
     * Show the typography builder UI.
     */
    public function index()
    {
        // Cache the fonts for 24 hours (86,400 seconds)
        $fonts = Cache::remember('google_fonts_top_20', 86400, function () {

            // 1. Hit the Google Fonts API (Sorted by popularity)
            $apiKey = env('GOOGLE_FONTS_API_KEY'); // We'll add this to your .env file
            $response = Http::get("https://www.googleapis.com/webfonts/v1/webfonts?key={$apiKey}&sort=popularity");

            // 2. If the API request is successful
            if ($response->successful()) {

                // Use Laravel Collections to grab the first 20, format them, and sort alphabetically
                return collect($response->json('items'))
                    ->take(20) // Limit to the top 20 most popular!
                    ->map(function ($font) {
                        return [
                            'name' => $font['family'],
                            'category' => $font['category'],
                        ];
                    })
                    ->sortBy('name')
                    ->values()
                    ->toArray();
            }

            // 3. Fallback just in case the Google API goes down
            return [
                ['name' => 'Inter', 'category' => 'sans-serif'],
                ['name' => 'Roboto', 'category' => 'sans-serif'],
                ['name' => 'Playfair Display', 'category' => 'serif'],
            ];
        });

        // Get the latest pairs and send only the heading and body attributes
        $savedPairs = FontPair::with(['heading', 'body'])->latest()->get();

        // Get the most recent one to set the initial UI state (like dark mode)
        $latestPair = $savedPairs->first();

        // Pass the cached array to your Blade view
        return view('fontpair', compact('fonts', 'savedPairs', 'latestPair'));
    }

    /**
     * Store a new font pairing.
     */
    public function store(Request $request)
    {
        // Validate incoming data
        $validated = $request->validate([
            'projectName' => 'required|string|max:255',
            'darkMode' => 'nullable|boolean',
            'sameFontAllowed' => 'nullable|boolean',

            'headingFont' => 'required|string|max:255',
            'headingWeight' => 'nullable|numeric',
            'headingLetterSpacing' => 'nullable|numeric',

            'bodyFont' => 'required|string|max:255',
            'bodyWeight' => 'nullable|numeric',
            'bodyLetterSpacing' => 'nullable|numeric',
            'bodyBaseSize' => 'nullable|numeric',
            'bodyLineHeight' => 'nullable|numeric',
            'bodyParagraphWidth' => 'nullable|numeric',
        ]);

        // Prevent same font if not allowed
        $sameFontAllowed = $validated['sameFontAllowed'] ?? false;

        if (!$sameFontAllowed && $validated['headingFont'] === $validated['bodyFont']) {
            return back()->with('error', 'Heading and Body fonts cannot be the same');
        }




        //dd($validated);

        // 1. Search ONLY by name. 
        // This ensures that "My Project" is always "My Project" regardless of settings.
        $pair = FontPair::updateOrCreate(
            ['name' => $validated['projectName']], // PART 1: SEARCH CRITERIA
            [                                      // PART 2: DATA TO UPDATE/SAVE
                'is_dark_mode' => $request->has('darkMode'),
                'same_font_allowed' => $request->has('sameFontAllowed'),
            ]
        );

        // Create heading record
        $pair->heading()->updateOrCreate(
            ['font_pair_id' => $pair->id], // The search for the object
            [                              // The values to chagne at this point
                'name' => $validated['headingFont'],
                'weight' => $validated['headingWeight'] ?? 700,
                'letter_spacing' => $validated['headingLetterSpacing'] ?? 0,
            ]
        );

        // Create body record
        $pair->body()->updateOrCreate(
            ['font_pair_id' => $pair->id], // The search for the object
            [                              // The values to chagne at this point
                'name' => $validated['bodyFont'],
                'weight' => $validated['bodyWeight'] ?? 400,
                'letter_spacing' => $validated['bodyLetterSpacing'] ?? 0,
                'base_size' => $validated['bodyBaseSize'] ?? 16,
                'line_height' => $validated['bodyLineHeight'] ?? 1.5,
                'paragraph_width' => $validated['bodyParagraphWidth'] ?? 65,
            ]
        );

        $message = $pair->wasRecentlyCreated ? 'Project Created!' : 'Project Updated!';
        return back()->with('message', $message);
    }

    public function delete(Request $request, $id)
    {
        $pair = FontPair::findOrFail($id);
        $pair->delete();
        return redirect()->back()->with('message', 'Font pair deleted successfully');
    }
}