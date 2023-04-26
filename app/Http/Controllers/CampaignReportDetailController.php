<?php

namespace App\Http\Controllers;

use App\Models\CampaignReportDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CampaignReportDetailController extends Controller
{
    public function index(Request $request)
    {
        $current_page = $request->query('current_page', 1);
        $data = new CampaignReportDetail;

        // Apply filters
        $fillable_column = (new CampaignReportDetail())->getFillable();
        foreach ($fillable_column as $column) {
            if ($request->query($column)) {
                $data = $data->where($column, 'like', '%' . $request->query($column) . '%');
            }
        }

        // Include related data
        if ($request->query('include')) {
            $includes = $request->query('include');
            foreach ($includes as $include) {
                $data = $data->with($include);
            }
        }

        // Apply is_active condition and paginate
        $data = $data->where('is_deleted', false)->paginate(10, ['*'], 'page', $current_page);

        return response()->json([
            'status' => 'success',
            'data' => $data->items(),
            'meta' => [
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'total_records' => $data->total(),
            ],
            'server_time' => (int) round(microtime(true) * 1000),
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_campaign_report' => 'required|integer',
            'date_time' => 'required|date',
            'amount' => 'required|integer',
            'description' => 'required|string|max:255',
            'evidence' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors(),
                'server_time' => (int) round(microtime(true) * 1000),
            ], 422);
        }

        $data = CampaignReportDetail::create([
            'id_campaign_report' => $request->id_campaign_report,
            'date_time' => $request->date_time,
            'amount' => $request->amount,
            'description' => $request->description,
            'evidence' => $request->evidence,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Data created successfully',
            'data' => $data,
            'server_time' => (int) round(microtime(true) * 1000),
        ]);
    }

    public function show($id)
    {
        $data = CampaignReportDetail::find($id);
        return response()->json([
            'status' => 'success',
            'message' => 'Data retrieved successfully',
            'data' => $data,
            'server_time' => (int) round(microtime(true) * 1000),
        ]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'id_campaign_report' => 'required|integer',
            'date_time' => 'required|date',
            'amount' => 'required|integer',
            'description' => 'required|string|max:255',
            'evidence' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors(),
                'server_time' => (int) round(microtime(true) * 1000),
            ], 422);
        }

        $data = CampaignReportDetail::find($id);
        $data->id_campaign_report = $request->id_campaign_report;
        $data->date_time = $request->date_time;
        $data->amount = $request->amount;
        $data->description = $request->description;
        $data->evidence = $request->evidence;
        $data->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Data updated successfully',
            'data' => $data,
            'server_time' => (int) round(microtime(true) * 1000),
        ]);
    }


    public function destroy($id)
    {
        $data = CampaignReportDetail::find($id);
        $data->is_deleted = true;
        $data->save();
        // $data->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data deleted successfully',
            'data' => $data,
            'server_time' => (int) round(microtime(true) * 1000),
        ]);
    }
}
