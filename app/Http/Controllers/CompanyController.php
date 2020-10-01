<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\User;
use App\UsersLocations;
use App\UsersBluetoothToken;
use App\UsersHealth;
use App\Company;

class CompanyController extends Controller
{

    public function company($view='list'){

        $v = explode('_', $view);
        $view = (isset($v[0]))?$v[0]:'list';
        $id  = (isset($v[1]))?$v[1]:0;
        $data = '';

        if($view=='edit'){
            $data = Company::where('id',$id)->first();
        }

        if($view=='delete'){
            $data = Company::where('id',$id)->delete();
            return redirect('/company/list');
        }

        if($view=='list'){
            $data = Company::select('id','company_name')->orderby('company_name')->get();
        }

        return view('company',['view'=>$view,'data'=>$data]);
    }

    public function create_company(Request $request){

        try{
            $company_name = $request->company_name;        

            $id = Company::insertGetId([
                'company_name' => $company_name,
                'created_at'=> now()->setTimezone('UTC')
            ]);        

            return response()->json(['status'=>true,'message' => 'New Company Created Successfully']);
        
        }catch(Exception $e){       
            Log::info($e->getMessage() . '' . $e->getLine());
            return response()->json(['status'=>false,'message' => $e->getMessage()]);
        }

       
    }

    public function edit_company(Request $request){

        try{

            $company_name = $request->company_name;        
            $id = $request->id;

            $id = Company::where('id',$id)->update([
                'company_name' => $company_name,
                'updated_at'=> now()->setTimezone('UTC')
            ]);        

            return response()->json(['status'=>true,'message' => 'New Company Created Successfully']);

        }catch(Exception $e){       
            Log::info($e->getMessage() . '' . $e->getLine());
            return response()->json(['status'=>false,'message' => $e->getMessage()]);
        }
    }

}