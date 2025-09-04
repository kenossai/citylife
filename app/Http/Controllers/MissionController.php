<?php

namespace App\Http\Controllers;

use App\Models\Mission;
use Illuminate\Http\Request;

class MissionController extends Controller
{
    public function index()
    {
        $missions = Mission::active()->orderBy('sort_order')->get();
        return view('pages.mission.index', compact('missions'));
    }

    public function home()
    {
        $homeMissions = Mission::active()
            ->byType('home')
            ->orderBy('sort_order')
            ->get();

        return view('pages.mission.home', compact('homeMissions'));
    }

    public function abroad()
    {
        $abroadMissions = Mission::active()
            ->byType('abroad')
            ->orderBy('sort_order')
            ->get();

        return view('pages.mission.abroad', compact('abroadMissions'));
    }

    public function show(Mission $mission)
    {
        if (!$mission->is_active) {
            abort(404);
        }

        return view('pages.mission.show', compact('mission'));
    }
}
