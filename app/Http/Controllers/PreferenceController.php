<?php

namespace App\Http\Controllers;

use App\Preference;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use DB;
use Auth;
use Validator;
use App\User;


class PreferenceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('preference.index');
    }

    public function fetch(Request $request)
    {
        $columns = array( 
                            0 =>'id', 
                            1 =>'option',
                            2=> 'description',
                            3=> 'value',
                            4=> 'created_by',
                            5=> 'created_at',
                            6=> 'modified_by',
                            7=> 'updated_at',
                            8=> 'id',
                            
                        );

         $totalData = Preference::count();   
         $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
            
        if(empty($request->input('search.value')))
        {            
            
                $data = Preference::offset($start)->limit($limit)
                         ->orderBy($order, $dir)
                         ->get();
        }else{
            $search = $request->input('search.value'); 

            $data = Preference::where('id', 'LIKE', '%' . $search . '%')
                              ->orwhere('deptname', 'LIKE', '%' . $search . '%')
                              ->orwhere('status', 'LIKE', '%' . $search . '%')
                              ->offset($start)
                              ->limit($limit)
                              ->orderBy($order, $dir)
                              ->get();


            $totalFiltered = Preference::where('id', 'LIKE', '%' . $search . '%')
                              ->orwhere('deptname', 'LIKE', '%' . $search . '%')
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
                $nestedData['created_by'] = $row->createdby->fname .' '.$row->createdby->lname;
                $nestedData['option'] = $row->option;
                $nestedData['description'] = $row->description;
                $nestedData['value'] = $row->value;
                $nestedData['modified_by'] = $row->modifiedby->fname .' '.$row->modifiedby->lname;
                $nestedData['updated_at'] = $row->updated_at->format('d-M-Y');
                $nestedData['created_at'] = $row->created_at->format('d-M-Y');
                $statusaction="";
                $editdept="";
                $deletedept="";
                $token=csrf_token();
                if(Auth::user()->can('status-preference')){
                    $statusaction="<a class='btn btn-danger status' href='#' data-id='{$id}' data-action='0'><i class='fa fa-times'></i></a>";
                }
                
                if(Auth::user()->can('edit-preference')){
                    $editdept="<a class='btn btn-primary edit' href='javascript:void(0)' data-id='{$id}'><i class='fa fa-edit'></i></a> ";
                }
                if(Auth::user()->can('delete-preference')){
                    $deletedept=" <a class='btn btn-danger delete' href='javascript:void(0)' data-id='{$id}'><i class='fa fa-trash'></i></a>
                    <form id=\"form$id\" action=\"{{action('PreferenceController@destroy', $id)}}\" method=\"post\" role='form'>
                      <input name=\"_token\" type=\"hidden\" value=\"$token\">
                      <input name=\"id\" type=\"hidden\" value=\"$id\">
                      <input name=\"_method\" type=\"hidden\" value=\"DELETE\">
                      </form>";
                }
                $nestedData['options'] = $editdept.$statusaction.$deletedept;                             
                
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        exit;
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $this->authorize('create-preference');
        $rules=[
            'option' => 'required|unique:preferences',
            'value' => 'required',
        ];
        $errormessage=['option.required'=>"Preference name is required.", 'option.unique'=>"Preference name must be unquie."];
        $validator = Validator::make($request->all(), $rules,$errormessage);
        if ($validator->fails()) {
            //pass validator errors as errors object for ajax response
            return response()->json(['errors'=>$validator->errors()]);
        }

        $preference= new \App\Preference;
        $preference->option=$request->get('option');
        $preference->description=$request->get('description');
        $preference->value=$request->get('value');      
        $preference->created_by=auth()->user()->id;
        $preference->modified_by=auth()->user()->id;
        $date=date_create($request->get('date'));
        $format = date_format($date,"Y-m-d H:i:s");
        $preference->created_at = strtotime($format);
        $preference->updated_at = strtotime($format);
		$preference->save();

        return response()->json(['success'=>'Preference created successfully.']);
        
    }
    
    /**
     * Display the specified resource.
     *
     * @param  \App\Preference  $preference
     * @return \Illuminate\Http\Preference
     */
    public function show(Department $department)
    {
        exit; 
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Preference  $department
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $this->authorize('edit-preference');
        $data = Preference::findOrFail($request->id);
        return response()->json($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Preference  $preference
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $this->authorize('edit-preference');
        $preference = \App\Preference::findOrFail($request->get('id'));
        $rules=[
            'option' => 'required|unique:preferences,option,'.$preference->id,
            'value' => 'required',
        ];
        $errormessage=['option.required'=>"Preference name is required.", 'option.unique'=>"Preference name must be unquie."];
        $validator = Validator::make($request->all(), $rules,$errormessage);
        if ($validator->fails()) {
            //pass validator errors as errors object for ajax response
            
            return response()->json(['errors'=>$validator->errors()]);
        }
        $preference->option=$request->get('option');
        $preference->description=$request->get('description');
        $preference->value=$request->get('value');        
        $preference->modified_by=auth()->user()->id;
        $date=date_create($request->get('date'));
        $format = date_format($date,"Y-m-d H:i:s");
        $preference->updated_at = strtotime($format);
		$preference->save();
        return response()->json(['success'=>'Preference updated successfully.']);
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Preference  $preference
     * @return \Illuminate\Http\Response
     */
    

    public function destroy(Request $request, $id)
    {
        $this->authorize('delete-preference');
        try{
            $id=$id;
            $preference = \App\Preference::findOrFail($id);
            $preference->delete();
            return response()->json(['success'=>'Preference deleted successfully.']);
        } catch(\Illuminate\Database\QueryException $ex){ 
            return response()->json(['success'=>'Unable to delete, this preference has linked record(s) in system.']);
        }
    }
}
