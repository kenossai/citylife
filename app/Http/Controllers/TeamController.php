<?php

namespace App\Http\Controllers;

use App\Models\TeamMember;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TeamController extends Controller
{
    /**
     * Display the pastoral team page.
     */
    public function pastoral(): View
    {
        $pastoralTeam = TeamMember::active()
            ->pastoral()
            ->ordered()
            ->get();

        $featuredPastor = $pastoralTeam->where('is_featured', true)->first() 
            ?? $pastoralTeam->first();

        return view('pages.team.pastoral', compact('pastoralTeam', 'featuredPastor'));
    }

    /**
     * Display the leadership team page.
     */
    public function leadership(): View
    {
        $leadershipTeam = TeamMember::active()
            ->leadership()
            ->ordered()
            ->get();

        $featuredLeaders = $leadershipTeam->where('is_featured', true);

        return view('pages.team.leadership', compact('leadershipTeam', 'featuredLeaders'));
    }

    /**
     * Display the combined team page.
     */
    public function index(): View
    {
        $pastoralTeam = TeamMember::active()
            ->pastoral()
            ->ordered()
            ->get();

        $leadershipTeam = TeamMember::active()
            ->leadership()
            ->ordered()
            ->get();

        return view('pages.team.index', compact('pastoralTeam', 'leadershipTeam'));
    }

    /**
     * Display the specified team member.
     */
    public function show(string $slug): View
    {
        $teamMember = TeamMember::active()
            ->bySlug($slug)
            ->firstOrFail();

        // Get related team members (same team type, excluding current member)
        $relatedMembers = TeamMember::active()
            ->where('team_type', $teamMember->team_type)
            ->where('id', '!=', $teamMember->id)
            ->ordered()
            ->limit(3)
            ->get();

        return view('pages.team.member', compact('teamMember', 'relatedMembers'));
    }

    /**
     * Display team members by team type (API endpoint)
     */
    public function byTeamType(string $teamType)
    {
        if (!in_array($teamType, ['pastoral', 'leadership'])) {
            return response()->json(['error' => 'Invalid team type'], 400);
        }

        $teamMembers = TeamMember::active()
            ->where('team_type', $teamType)
            ->ordered()
            ->get()
            ->map(function ($member) {
                return [
                    'id' => $member->id,
                    'name' => $member->display_name,
                    'full_name' => $member->full_name,
                    'position' => $member->position,
                    'profile_image_url' => $member->profile_image_url,
                    'excerpt' => $member->excerpt,
                    'slug' => $member->slug,
                ];
            });

        return response()->json([
            'data' => $teamMembers,
            'count' => $teamMembers->count(),
            'team_type' => $teamType,
        ]);
    }

    /**
     * Search team members
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        $teamType = $request->get('team_type', '');

        $teamMembers = TeamMember::active()
            ->when($query, function ($queryBuilder) use ($query) {
                $queryBuilder->where(function ($q) use ($query) {
                    $q->where('first_name', 'like', "%{$query}%")
                      ->orWhere('last_name', 'like', "%{$query}%")
                      ->orWhere('position', 'like', "%{$query}%")
                      ->orWhere('ministry_focus', 'like', "%{$query}%");
                });
            })
            ->when($teamType, function ($queryBuilder) use ($teamType) {
                $queryBuilder->where('team_type', $teamType);
            })
            ->ordered()
            ->limit(10)
            ->get();

        return response()->json([
            'data' => $teamMembers->map(function ($member) {
                return [
                    'id' => $member->id,
                    'name' => $member->display_name,
                    'position' => $member->position,
                    'team_type' => $member->team_type,
                    'profile_image_url' => $member->profile_image_url,
                    'slug' => $member->slug,
                ];
            }),
            'query' => $query,
            'count' => $teamMembers->count(),
        ]);
    }
}
