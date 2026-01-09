<?php

namespace App\Http\Controllers;

use App\Models\AboutPage;
use App\Models\CoreValue;
use App\Models\TeamMember;
use Illuminate\Http\Request;

class AboutController extends Controller
{
    /**
     * Display the about page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get the active about page with core values
        $aboutPage = AboutPage::active()
            ->with(['coreValues' => function($query) {
                $query->active()->ordered();
            }])
            ->first();

        // If no about page exists, create a default one
        if (!$aboutPage) {
            $aboutPage = $this->createDefaultAboutPage();
        }

        // Get core values separately for easier access in the view
        $coreValues = $aboutPage->coreValues;

        $pastoralTeam = TeamMember::active()
            ->pastoral()
            ->ordered()
            ->get();
        return view('pages.about.index', compact('aboutPage', 'coreValues', 'pastoralTeam'));
    }

    /**
     * Display a specific core value page.
     *
     * @param string $slug
     * @return \Illuminate\View\View
     */
    public function showCoreValue($slug)
    {
        $coreValue = CoreValue::active()
            ->with('aboutPage')
            ->bySlug($slug)
            ->firstOrFail();

        $relatedValues = CoreValue::active()
            ->where('id', '!=', $coreValue->id)
            ->where('about_page_id', $coreValue->about_page_id)
            ->ordered()
            ->limit(3)
            ->get();

        return view('pages.about.core-value', compact('coreValue', 'relatedValues'));
    }

    /**
     * Display the our ministry page.
     *
     * @return \Illuminate\View\View
     */
    public function ourMinistry()
    {
        return view('pages.about.our-ministry');
    }

    /**
     * Create a default about page if none exists.
     *
     * @return AboutPage
     */
    private function createDefaultAboutPage()
    {
        return AboutPage::create([
            'title' => 'About Us',
            'introduction' => 'Welcome to our church. We are a community of believers committed to following Jesus Christ.',
            'church_name' => 'City Life',
            'church_description' => 'A vibrant Christian community',
            'is_active' => true,
            'slug' => 'about-us',
        ]);
    }
}
