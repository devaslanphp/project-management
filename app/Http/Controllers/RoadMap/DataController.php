<?php

namespace App\Http\Controllers\RoadMap;

use App\Http\Controllers\Controller;
use App\Models\Epic;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

class DataController extends Controller
{

    /**
     * Get project epics data
     *
     * @param Project $project
     * @return JsonResponse
     */
    public function data(Project $project): JsonResponse
    {
        $epics = Epic::where('project_id', $project->id)->get();
        return response()->json($this->formatResponse($epics));
    }

    /**
     * Format epics to JSON data
     *
     * @param Collection $epics
     * @return Collection
     */
    private function formatResponse(Collection $epics): Collection
    {
        $results = collect();
        foreach ($epics as $epic) {
            $results->push(collect([
                "pID" => $epic->id,
                "pName" => $epic->name,
                "pStart" => $epic->starts_at->format('Y-m-d'),
                "pEnd" => $epic->ends_at->format('Y-m-d') . " 23:59:59",
                "pClass" => "g-custom-task",
                "pLink" => "",
                "pMile" => 0,
                "pRes" => "",
                "pComp" => "",
                "pGroup" => 1,
                "pParent" => 0,
                "pOpen" => 1,
                "pDepend" => "",
                "pCaption" => "",
                "pNotes" => "",
                "pBarText" => "",
                "meta" => [
                    "id" => $epic->id,
                    "epic" => true
                ]
            ]));
            foreach ($epic->tickets as $ticket) {
                $pComp = round($ticket->completudePercentage, 0);
                $results->push(collect([
                    "pID" => $epic->id . $ticket->id,
                    "pName" => $ticket->name,
                    "pStart" => "",
                    "pEnd" => "",
                    "pClass" => "g-custom-task",
                    "pLink" => "",
                    "pMile" => 0,
                    "pRes" => "",
                    "pComp" => min($pComp, 100),
                    "pGroup" => 0,
                    "pParent" => $epic->id,
                    "pOpen" => 1,
                    "pDepend" => "",
                    "pCaption" => "",
                    "pNotes" => "",
                    "pBarText" => "",
                    "meta" => [
                        "id" => $ticket->id,
                        "epic" => false,
                        "parent" => $epic->id
                    ]
                ]));
            }
        }
        return $results;
    }

}
