<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrderPartApproval;
use Illuminate\Support\Facades\DB;

class OrderApprovalController extends Controller
{
    public function pending_oow()
    {
        // View pending OOW
        $approvals = OrderPartApproval::with(['order.customer'])
            ->where('type', 'oow')
            ->where('approval_status', 'pending')
            ->get();
            
        $data = [
            'page_title' => 'OOW Part Approval - Pending',
            'approvals' => $approvals,
            'count' => $approvals->count(),
        ];
        
        return view('order.part_approval_oow', $data);
    }

    public function pending_iw()
    {
        // View pending IW
        $approvals = OrderPartApproval::with(['order.customer'])
            ->where('type', 'iw')
            ->where('approval_status', 'pending')
            ->get();
            
        $data = [
            'page_title' => 'IW Part Approval - Pending',
            'approvals' => $approvals,
            'count' => $approvals->count(),
        ];
        
        return view('order.part_approval_iw', $data);
    }

    public function approve(Request $request, $id)
    {
        if (!$request->ajax()) {
            return response()->json(['success' => false, 'message' => 'AJAX request required'], 400);
        }
        
        $approval = OrderPartApproval::find($id);
        if (!$approval) {
            return response()->json(['success' => false, 'message' => 'Approval tidak ditemukan'], 404);
        }
        
        $approval->update([
            'approval_status' => 'approved',
            'approved_by' => 1, // session('karyawan_id') in reality
            'approved_at' => now(),
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Part order approved',
            'id' => $id
        ]);
    }

    public function reject(Request $request, $id)
    {
        if (!$request->ajax()) {
            return response()->json(['success' => false, 'message' => 'AJAX request required'], 400);
        }
        
        $reason = $request->input('reject_reason');
        if (empty($reason)) {
            return response()->json(['success' => false, 'message' => 'Alasan penolakan harus diisi'], 400);
        }
        
        $approval = OrderPartApproval::find($id);
        $approval->update([
            'approval_status' => 'rejected',
            'rejected_reason' => $reason,
            'approved_by' => 1, // session('karyawan_id')
            'rejected_at' => now(),
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Part order rejected'
        ]);
    }
}
