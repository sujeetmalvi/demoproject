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
class ReportsController extends Controller
{

    public function rpt_usersbtdistances(){
        $company_id = Auth::user()->company_id;
        $data = UsersBluetoothToken::leftjoin('users','users.id','=','usersbluetoothtoken.user_id')
                ->leftjoin('users as user2','user2.id','=','usersbluetoothtoken.bluetoothtoken')
                ->where('usersbluetoothtoken.distance','<=',2)
                ->select('usersbluetoothtoken.bluetoothtoken',
                    'usersbluetoothtoken.distance','users.name','user2.name as user2name','usersbluetoothtoken.created_at')
                ->where('users.company_id',$company_id)
                ->orderBy('usersbluetoothtoken.created_at', 'DESC')
                ->get();
        return view('usersbtdistances', ['data'=>$data]);
    }

    public function exl_usersbtdistances(){
        $company_id = Auth::user()->company_id;
        $data = UsersBluetoothToken::leftjoin('users','users.id','=','usersbluetoothtoken.user_id')
                ->leftjoin('users as user2','user2.id','=','usersbluetoothtoken.bluetoothtoken')
                ->where('usersbluetoothtoken.distance','<=',2)
                ->select('usersbluetoothtoken.bluetoothtoken',
                    'usersbluetoothtoken.distance','users.name','user2.name as user2name','usersbluetoothtoken.created_at')
                ->where('users.company_id',$company_id)
                ->orderBy('usersbluetoothtoken.created_at', 'DESC')
                ->get();
        return view('excel_reports.usersbtdistances', ['data'=>$data]);
    }




    public function rpt_active_cases(){
        $company_id = Auth::user()->company_id;
        $data = User::join('usershealth','usershealth.user_id','=','users.id') 
                ->select('name','usershealth.created_at','users.id')
                ->where('usershealth.condition_type','1')
                ->where('users.company_id',$company_id)
                ->orderBy('usershealth.created_at', 'DESC')
                ->get();
        return view('rpt_active_cases',['data'=>$data]);
    }

    public function rpt_1stdegree_endangered($user_id){
        $company_id = Auth::user()->company_id;
        $data = UsersBluetoothToken::leftjoin('users','users.id','=','usersbluetoothtoken.bluetoothtoken')
                ->where('usersbluetoothtoken.distance','<=',2)
                ->where('usersbluetoothtoken.user_id','=',$user_id)
                ->where('users.company_id',$company_id)
                ->select('usersbluetoothtoken.distance','users.name as user2name','usersbluetoothtoken.created_at')
                ->orderBy('usersbluetoothtoken.created_at', 'DESC')
                ->get();
    // ->dd();
    // $sql = str_replace_array('?', $data->getBindings(), $data->toSql());
    // return dd($sql);    
        return view('rpt_1stdegree_endangered', ['data'=>$data]);
    }

    public function rpt_2nddegree_endangered($user_id){
        $company_id = Auth::user()->company_id;
        $data_lvl1 = UsersBluetoothToken::leftjoin('users','users.id','=','usersbluetoothtoken.bluetoothtoken')
                ->where('usersbluetoothtoken.distance','<=',2)
                ->where('usersbluetoothtoken.user_id','=',$user_id)
                ->where('users.company_id',$company_id)
                ->selectRaw('group_concat(distinct(usersbluetoothtoken.bluetoothtoken)) as users_ids')
                ->first();

     // ->dd();
     // $sql = str_replace_array('?', $data->getBindings(), $data->toSql());
     // return dd($sql);   
        $data = UsersBluetoothToken::leftjoin('users','users.id','=','usersbluetoothtoken.bluetoothtoken')
                ->where('usersbluetoothtoken.distance','<=',2)
                ->where('usersbluetoothtoken.bluetoothtoken','!=',$user_id)
                ->wherein('usersbluetoothtoken.user_id',[$data_lvl1->users_ids])
                ->select('usersbluetoothtoken.distance','users.name as user2name','usersbluetoothtoken.created_at')
                ->where('users.company_id',$company_id)
                ->orderBy('usersbluetoothtoken.created_at', 'DESC')
                ->get();
      //             ->dd();
      // $sql = str_replace_array('?', $data->getBindings(), $data->toSql());
      // return dd($sql);   
        return view('rpt_2nddegree_endangered', ['data'=>$data]);
    }


    public function rpt_usershealth(){
        $company_id = Auth::user()->company_id;
        $data = UsersHealth::leftjoin('users','users.id','=','usershealth.user_id')
                ->where('users.company_id',$company_id)
                ->select('users.name','usershealth.created_at','usershealth.condition_type')
                ->orderBy('usershealth.created_at', 'DESC')
                ->get();
    // ->dd();
    // $sql = str_replace_array('?', $data->getBindings(), $data->toSql());
    // return dd($sql);    
        return view('rpt_usershealth',['data'=>$data]);
    }

    public function exl_usershealth(){
        $company_id = Auth::user()->company_id;
        $data = UsersHealth::leftjoin('users','users.id','=','usershealth.user_id')
                ->where('users.company_id',$company_id)
                ->select('users.name','usershealth.created_at','usershealth.condition_type')
                ->orderBy('usershealth.created_at', 'DESC')
                ->get();
        return view('excel_reports.usershealth', ['data'=>$data]);
    }


    public function rpt_defaulters(){
        $company_id = Auth::user()->company_id;
        $data = UsersBluetoothToken::leftjoin('users','users.id','=','usersbluetoothtoken.bluetoothtoken')
                ->where('users.company_id',$company_id)
                ->select('users.name',\DB::raw('count(usersbluetoothtoken.bluetoothtoken) as voilation'),\DB::raw('date_format(usersbluetoothtoken.created_at,"%d-%m-%Y") as ddate'),\DB::raw('date_format(usersbluetoothtoken.created_at,"%Y-%m-%d") as orderdate'))
                ->orderBy('usersbluetoothtoken.id', 'DESC')
                ->groupBy(\DB::raw('ddate,usersbluetoothtoken.bluetoothtoken'))
                ->having('voilation','>','2')
                ->get();
    // ->dd();
    // $sql = str_replace_array('?', $data->getBindings(), $data->toSql());
    // return dd($sql);    
        return view('rpt_defaulters',['data'=>$data]);
    }

    public function exl_defaulters(){
        $company_id = Auth::user()->company_id;
        $data = UsersBluetoothToken::leftjoin('users','users.id','=','usersbluetoothtoken.bluetoothtoken')
                ->where('users.company_id',$company_id)
                ->select('users.name',\DB::raw('count(usersbluetoothtoken.bluetoothtoken) as voilation'),\DB::raw('date_format(usersbluetoothtoken.created_at,"%d-%m-%Y") as ddate'),\DB::raw('date_format(usersbluetoothtoken.created_at,"%Y-%m-%d") as orderdate'))
                ->orderBy('usersbluetoothtoken.id', 'DESC')
                ->groupBy(\DB::raw('ddate,usersbluetoothtoken.bluetoothtoken'))
                ->having('voilation','>','2')
                ->get();
        return view('excel_reports.defaulters', ['data'=>$data]);
    }





    public function rpt_breaches(){
        $company_id = Auth::user()->company_id;
        $data = UsersBluetoothToken::leftjoin('users','users.id','=','usersbluetoothtoken.bluetoothtoken')
                ->where('users.company_id',$company_id)
                ->select('users.name',\DB::raw('count(usersbluetoothtoken.bluetoothtoken) as voilation'))
                ->orderBy('usersbluetoothtoken.id', 'DESC')
                ->groupBy(\DB::raw('usersbluetoothtoken.bluetoothtoken'))
                ->get();
    // ->dd();
    // $sql = str_replace_array('?', $data->getBindings(), $data->toSql());
    // return dd($sql);    
        return view('rpt_breaches',['data'=>$data]);
    }

    public function exl_breaches(){
        $company_id = Auth::user()->company_id;
        $data = UsersBluetoothToken::leftjoin('users','users.id','=','usersbluetoothtoken.bluetoothtoken')
                ->where('users.company_id',$company_id)
                ->select('users.name',\DB::raw('count(usersbluetoothtoken.bluetoothtoken) as voilation'))
                ->orderBy('usersbluetoothtoken.id', 'DESC')
                ->groupBy(\DB::raw('usersbluetoothtoken.bluetoothtoken'))
                ->get();
        return view('excel_reports.breaches', ['data'=>$data]);
    }


    
}