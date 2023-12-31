<?php

namespace App\Http\Controllers\Operator;

use App\Models\Temuan;
use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Request;
use App\Exports\TemuansExport;
use App\Imports\TemuansImport;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Division;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;

class OperatorController extends Controller
{

    // public function showTemuan(){
    //     $user = Auth::user();
    //     $divisionId = $user->division_id;
    //     $userId = $user->id;
    //     $division = Division::all();
    //     $temuan = Temuan::with('user');
    //         $temuan->whereIn('status', [2, 3])
    //                ->whereHas('user', function ($query) use ($divisionId) {
    //                    $query->where('role_id', 3);
    //                });
    //     $temuan = $temuan->get();
    //     return view('operator.temuan',compact('temuan','division'));
    // }

    public function showTemuan(Request $request){
        dd($request->all());
        $user = Auth::user();
        $divisionId = $user->division_id;
        $userId = $user->id;
        $division = Division::all();
        $temuan = Temuan::with('user');
    
        // Get the selected division from the request
        $selectedDivisionId = $request->input('division', null);
    
        // Check if a specific division is selected
        if ($selectedDivisionId !== null) {
            // Filter by the selected division
            $temuan->whereHas('user', function ($query) use ($selectedDivisionId) {
                $query->where('division_id', $selectedDivisionId);
            });
        }
    
        // Continue with the existing logic for filtering by status and role
        if ($divisionId === 1) {
            $temuan->whereIn('status', [2, 3])
                   ->whereHas('user', function ($query) {
                       $query->where('role_id', 3);
                   });
        } else {
            $temuan->whereIn('status', [1, 2])
                   ->whereHas('user', function ($query) use ($userId) {
                       $query->where('id', $userId);
                   });
        }
    
        $temuan = $temuan->get();
        return view('operator.temuan', compact('temuan', 'division', 'selectedDivisionId'));
    }
    

    public function showEdit($id){
        $temuan = Temuan::find($id);
        return view('operator.edit', compact('temuan'));
    }

    public function updateTemuan(Request $request,$id){
        $user = Auth::user();
        $temuan = Temuan::find($id);
        try {
            $updated = Temuan::where('id', $id)->update([
                'id' => $id,
                'user_id' => $temuan->user_id,
                'no' => $temuan->no,
                'object_pemeriksaan' => $request->object_pemeriksaan,
                'jenis_audit' => $request->jenis_audit,
                'auditor' => $request->auditor,
                'risk' => $request->risk,
                'issue_summary' => $request->issue_summary,
                'issue_detail' => $request->issue_detail,
                'recomendation' => $request->recomendation,
                'corrective_action_plan' => $request->corrective_action_plan,
                'status' => $temuan->status,
            ]);
    
            if ($updated) {
                Alert::success('Success', 'Temuan updated successfully');
                if ($user->role_id == 2) {
                    return redirect()->route('show.supervisor.temuan.page');
                }
                return redirect()->route('show.operator.temuan.page');
            } else {
                Alert::warning('No Changes', 'Temuan remains the same');
            }
    
            return redirect()->back();
        } catch (\Exception $e) {
            Log::error('Error updating temuan: ' . $e->getMessage());
            Alert::error('Error', 'Failed to update temuan. Contact developer for assistance.');
            return redirect()->back();
        }
    }

    public function updateStatusTemuan($id){
        try {
            $updated = Temuan::where('id', $id)->update(['status' => '3']);
    
            if ($updated) {
                Alert::success('Success', 'Status updated successfully');
            } else {
                Alert::warning('No Changes', 'Status remains the same');
            }
    
            return redirect()->back();
        } catch (\Exception $e) {
            Log::error('Error updating status: ' . $e->getMessage());
            Alert::error('Error', 'Failed to update status. Contact developer for assistance.');
            return redirect()->back();
        }
    }

    // public function downloadPdfFile(){
    //     $data = [];
    //     $pdf = PDF::loadView('operator.pdf', $data ,['orientation' => 'portrait']);
    //     $pdf->setPaper('A4', 'portrait');
    //     return $pdf->download('Temuan.pdf');
    // }
    
    public function deleteTemuan($id){
        try {
            $deleted = Temuan::where('id', $id)->delete();
            if ($deleted) {
                Alert::success('Success', 'Deleted successfully');
                return redirect()->route('show.operator.temuan.page');
            } else {
                Alert::warning('No Changes', 'Delete remains the same');
            }
    
            return redirect()->back();
        } catch (\Exception $e) {
            Log::error('Error deleting temuan: ' . $e->getMessage());
            Alert::error('Error', 'Failed to delete temuan. Contact developer for assistance.');
            return redirect()->back();
        }
    }
}
