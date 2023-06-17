<?php

namespace App\Http\Controllers;

use App\Imports\CampaignReportDetailImport;
use App\Imports\DataImport;
use App\Models\CampaignReport;
use App\Models\CampaignReportDetail;
use App\Models\CampaingReportGroup;
use App\Models\Payment;
use App\Models\Receipt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class CampaignReportController extends Controller
{
    public function index(Request $request)
    {
        $current_page = $request->query('current_page', 1);
        $data = new CampaignReport;

        // Apply filters
        $fillable_column = (new CampaignReport())->getFillable();
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
        // create receipts
        $field_receipts = [];
        $receipts = Receipt::create($field_receipts);

        // create payments
        $field_payment = [];
        $field_payment['id_user'] = $request->user()->id;
        $field_payment['id_receipt'] = $receipts->id;
        $payments = Payment::create($field_payment);

        $field_campaign_reports = $request->only((new CampaignReport())->getFillable());
        $field_campaign_reports['id_payment'] = $payments->id;
        if ($request->file('file_document')) {
            $file_document = $request->file('file_document');
            $original_name = $file_document->getClientOriginalName();
            $timestamp = now()->timestamp;
            $new_file_name = $timestamp . '_' . $original_name;
            $path_of_file_document = $file_document->storeAs('public/document', $new_file_name);
            $document_url = Storage::url($path_of_file_document);
            $field_campaign_reports['document_url'] = $document_url;
        }
        $data = CampaignReport::create($field_campaign_reports);
        return response()->json([
            'status' => 'success',
            'message' => 'Data created successfully',
            'data' => $data,
            'server_time' => (int) round(microtime(true) * 1000),
        ]);
    }

    public function show(Request $request, $id)
    {
        $data = new CampaignReport();
        // Include related data
        if ($request->query('include')) {
            $includes = $request->query('include');
            foreach ($includes as $include) {
                $data = $data->with($include);
            }
        }

        $data = $data->find($id);

        return response()->json([
            'status' => 'success',
            'message' => 'Data retrieved successfully',
            'data' => $data,
        ]);
    }

    public function update(Request $request, $id)
    {
        $data = CampaignReport::find($id);
        $field_campaign_reports = $request->only((new CampaignReport())->getFillable());
        if ($request->file('file_document')) {
            $file_document = $request->file('file_document');
            $original_name = $file_document->getClientOriginalName();
            $timestamp = now()->timestamp;
            $new_file_name = $timestamp . '_' . $original_name;
            $path_of_file_document = $file_document->storeAs('public/document', $new_file_name);
            $document_url = Storage::url($path_of_file_document);
            $field_campaign_reports['document_url'] = $document_url;
        }
        $data->update($field_campaign_reports);

        if ($request->is_exported && $request->is_exported == 1) {
            $file = public_path($data->document_url);
            // $file = $request->file('file_document');
            $import = new CampaignReportDetailImport();
            // $import = new DataImport();
            $data_of_campaign_report_detail = Excel::toArray($import, $file);
            // $this->transformDate($data_of_campaign_report_detail[0][0][0]);
            // return $data_of_campaign_report_detail[0];
            foreach($data_of_campaign_report_detail[0] as $key => $value){
                // if($key != 0){
                    $field_campaign_report_detail = [];
                    $field_campaign_report_detail['date'] = $this->transformDate($value['date']);
                    $field_campaign_report_detail['amount'] = $value['amount'];
                    $field_campaign_report_detail['description'] = $value['description'];
                    $field_campaign_report_detail['evidence'] = $value['evidence'];
                    $field_campaign_report_detail['type'] = $value['type'];
                    $campaign_report_detail = CampaignReportDetail::create($field_campaign_report_detail);

                    $field_campaign_report_group = [];
                    $field_campaign_report_group['id_campaign_report'] = $data->id;
                    $field_campaign_report_group['id_campaign_report_detail'] = $campaign_report_detail->id;
                    $campaign_report_group = CampaingReportGroup::create($field_campaign_report_group);
                // }
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data updated successfully',
            'data' => $data,
            'server_time' => (int) round(microtime(true) * 1000),
        ]);
    }


    public function destroy($id)
    {
        $data = CampaignReport::find($id);
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

    public function transformDate($value, $format = 'Y-m-d')
    {
        try {
            $string = \Carbon\Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value));
            $parts = explode("T", $string); // Split the string at 'T'

            $dateString = $parts[0]; // Extract the date part

            $dateArray = explode("-", $dateString); // Split the date part at '-'
            $year = $dateArray[0]; // Extract the year
            $month = $dateArray[1]; // Extract the month
            $day = $dateArray[2]; // Extract the day

            $formattedDate = "{$year}-{$month}-{$day}"; // F
            return $formattedDate;
        } catch (\ErrorException $e) {
            return \Carbon\Carbon::createFromFormat($format, $value);
        }
    }
}
