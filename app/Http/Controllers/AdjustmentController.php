<?php

namespace App\Http\Controllers;

use App\Adjustment;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Validator;
use DB;
use \App\User;
use Carbon;
use DateTime;

class AdjustmentController extends Controller
{
    public function index()
    {
        $employees = User::where('iscustomer', 0)->where('status', 1)->orderBy('fname', 'ASC')->get();
        return view('staffpayroll.adjustments',compact('employees'));
    }

    public function fetch(Request $request)
    {
     
        $columns = array( 
            'id', 
            'dated',
            'type',
            'amount',
            'description',
            'status',
            'user_id',
            'user_id',
            'created_by',
            'modified_by',
            'created_at',
            'updated_at',
            'id',
            
        );

         $totalData = Adjustment::count();   
         $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
            
        if(empty($request->input('search.value')))
        {            
            
                $data = Adjustment::offset($start)->limit($limit)
                         ->orderBy($order, $dir)
                         ->get();
        }else{
            $search = $request->input('search.value'); 

            $data = Adjustment::where('id', 'LIKE', '%' . $search . '%')
                              ->orwhere('dated', 'LIKE', '%' . $search . '%')
                              ->orwhere('description', 'LIKE', '%' . $search . '%')
                              ->orwhere('amount', 'LIKE', '%' . $search . '%')
                              ->orwhere('status', 'LIKE', '%' . $search . '%')
                              ->offset($start)
                              ->limit($limit)
                              ->orderBy($order, $dir)
                              ->get();


            $totalFiltered = Adjustment::where('id', 'LIKE', '%' . $search . '%')
                                    ->orwhere('dated', 'LIKE', '%' . $search . '%')
                                    ->orwhere('description', 'LIKE', '%' . $search . '%')
                                    ->orwhere('amount', 'LIKE', '%' . $search . '%')
                                    ->orwhere('status', 'LIKE', '%' . $search . '%')
                                    ->count();                  
           
        }


        $dataArray = array();
        if(!empty($data))
        {
            foreach ($data as $row)
            {
                $id = $row->id;               
                $nestedData['id']  = $row->id;
                $nestedData['user_id'] = $row->applicant->fname.' '.$row->applicant->lname;
                if($row->type==1)
                {
                    $nestedData['type'] = "Addition";
                }else{
                    $nestedData['type'] = "Deduction";
                }

                $nestedData['amount'] = $row->amount;
                $nestedData['description'] = $row->description;
                $nestedData['dated'] = $row->dated->format('d-M-Y');
                $nestedData['created_by'] = $row->createdby->fname .' '.$row->createdby->lname;
                $nestedData['modified_by'] = $row->modifiedby->fname .' '.$row->modifiedby->lname;
                $nestedData['updated_at'] = $row->updated_at->format('d-M-Y');
                $nestedData['created_at'] = $row->created_at->format('d-M-Y');
                $editdept="";
                $deletedept="";
                $token=csrf_token();
                if($row->status=='Approved') {
                  $nestedData['status'] = '<span class="btn btn-success btn-sm">Approved</span>';
                }else if($row->status=='Rejected'){
                  $nestedData['status'] = '<span class="btn btn-danger btn-sm">Rejected</span>';
                  
                }
                if(Auth::user()->can('edit-adjustment')){
                    $editdept="<a class='btn btn-primary edit' href='javascript:void(0)' data-id='{$id}'><i class='fa fa-edit'></i></a> ";
                }
                if(Auth::user()->can('delete-adjustment')){
                    $deletedept=" <a class='btn btn-danger delete' href='javascript:void(0)' data-id='{$id}'><i class='fa fa-trash'></i></a>
                    <form id=\"form$id\" action=\"{{action('AdjustmentController@destroy', $id)}}\" method=\"post\" role='form'>
                      <input name=\"_token\" type=\"hidden\" value=\"$token\">
                      <input name=\"id\" type=\"hidden\" value=\"$id\">
                      <input name=\"_method\" type=\"hidden\" value=\"DELETE\">
                      </form>";
                }
                $nestedData['options'] = $editdept.$deletedept;                             
                
                $dataArray[] = $nestedData;

            }
        }
          
        $json_data = array(
                    "draw"            => intval($request->input('draw')),  
                    "recordsTotal"    => intval($totalData),  
                    "recordsFiltered" => intval($totalFiltered), 
                    "data"            => $dataArray   
                    );
          return response()->json($json_data);   
    }

   
    public function create()
    {
        exit;
        //
    }

   
    public function store(Request $request)
    {
        
        $this->authorize('create-adjustment');
        $rules=[
            'user_id' => 'required',
            'dated' => 'required',
            'description' => 'required',
            'type' => 'required',
            'amount' => 'required|numeric',
            'status' => 'required',
        ];
        $errormessage=[
            'user_id.required'=>"Employee is required.",
            'dated.required'=>"Date is required.",
            'description.required'=>"Description is required.",
            'type.required'=>"Type is required.",
            'amount.required'=>"Amount is required.",
            'amount.numeric'=>"Amount must be number.",
            'status.required'=>"Status field is required.",
         ];
        $validator = Validator::make($request->all(), $rules,$errormessage);
        if ($validator->fails()) {
            //pass validator errors as errors object for ajax response
            return response()->json(['errors'=>$validator->errors()]);
        }

        $adjustment= new \App\Adjustment;
        $adjustment->dated=$request->get('dated');
        $adjustment->description=$request->get('description'); 
        $adjustment->type=$request->get('type');
        $adjustment->status=$request->get('status');     
        $adjustment->amount=$request->get('amount');
        $adjustment->user_id=$request->get('user_id');
        $adjustment->created_by=auth()->user()->id;
        $adjustment->modified_by=auth()->user()->id;
        $date=date_create($request->get('date'));
        $format = date_format($date,"Y-m-d H:i:s");
        $adjustment->created_at = strtotime($format);
        $adjustment->updated_at = strtotime($format);
		$adjustment->save();

        return response()->json(['success'=>'Created successfully.']);
        
    }
    
    
    public function show(Department $department)
    {
        exit; 
    }

    public function edit(Request $request)
    {
        $this->authorize('edit-adjustment');
        $data = Adjustment::findOrFail($request->id);
        return response()->json($data);
    }

  
    public function update(Request $request)
    {
        $this->authorize('edit-adjustment');
        $adjustment = \App\Adjustment::findOrFail($request->get('id'));
        $rules=[
            'user_id' => 'required',
            'dated' => 'required',
            'description' => 'required',
            'type' => 'required',
            'amount' => 'required|numeric',
            'status' => 'required',
        ];
        $errormessage=[
            'user_id.required'=>"Employee is required.",
            'dated.required'=>"Date is required.",
            'description.required'=>"Description is required.",
            'type.required'=>"Type is required.",
            'amount.required'=>"Amount is required.",
            'amount.numeric'=>"Amount must be number.",
            'status.required'=>"Status field is required.",
         ];
        $validator = Validator::make($request->all(), $rules,$errormessage);
        if ($validator->fails()) {
            //pass validator errors as errors object for ajax response
            return response()->json(['errors'=>$validator->errors()]);
        }
        $adjustment->dated=$request->get('dated');
        $adjustment->description=$request->get('description'); 
        $adjustment->type=$request->get('type');
        $adjustment->status=$request->get('status');     
        $adjustment->amount=$request->get('amount');
        $adjustment->user_id=$request->get('user_id');
        $adjustment->created_by=auth()->user()->id;
        $adjustment->modified_by=auth()->user()->id;
        $date=date_create($request->get('date'));
        $format = date_format($date,"Y-m-d H:i:s");
        $adjustment->created_at = strtotime($format);
        $adjustment->updated_at = strtotime($format);
		$adjustment->save();
        return response()->json(['success'=>'Updated successfully.']);
    }
    

    public function destroy(Request $request, $id)
    {
        $this->authorize('delete-adjustment');
        try{
            $id=$id;
            $department = \App\Adjustment::findOrFail($id);
            $department->delete();
            return response()->json(['success'=>'Deleted successfully.']);
        } catch(\Illuminate\Database\QueryException $ex){ 
            return response()->json(['success'=>'Unable to delete, this record may have linked record(s) in system.']);
        }
    }
}
