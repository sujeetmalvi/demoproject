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
use Config;
use Log;

class UsersController extends Controller
{

    public function login(){
        return view('login',['status'=>true,'message' => '']);
    }

    public function process_login(Request $request){
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        $credentials = request(['email', 'password']);
        if(Auth::attempt($credentials)){
            if(Auth::user()->role_id!=2 && Auth::user()->role_id!=1){
                return view('login',['status'=>false,'message' => 'Unauthorized Access']);    
            }
            return redirect()->action('UsersController@dashboard');
        }else{
            return view('login',['status'=>false,'message' => 'Invalid Credentials']);
        }
    } 

    public function dashboard(){
        $data = UsersHealth::select('condition_type',\DB::raw('count(condition_type) as usercount'))
                ->groupBy('condition_type')
                ->orderBy('condition_type','ASC')
                ->get(); 
                //  ->dd();
                //  $sql = str_replace_array('?', $data->getBindings(), $data->toSql());
                // return dd($sql);   
                $ddata_arr = array('Infected'=>'0','Symptoms'=>'0','Well'=>'0','Not Infected'=>'0');
            foreach($data as $d){
                $ddata_arr[Config::get('constants.CONDITION_TYPES.'.$d->condition_type)] = $d->usercount;
                //$ddata_arr[$d->condition_type] = $d->usercount;
            }    
            
            //print_r($ddata_arr); die();
        return view('dashboard',['data'=>$ddata_arr]);
    }

    public function users($view='list'){

      $v = explode('_', $view);
      $view = (isset($v[0]))?$v[0]:'list';
      $id  = (isset($v[1]))?$v[1]:0;

      $data = '';

      if($view=='edit'){
        $data = User::where('id',$id)->first();
      }

      if($view=='delete'){
        $data = User::where('id',$id)->delete();
        return redirect('/users/list');
      }


      if($view=='list'){
        $company_id = Auth::user()->company_id;
        $data = User::join('company','company.id','=','users.company_id')
                ->select('users.id','users.name','users.email','company.company_name','users.mobile','users.location');
        if(Auth::user()->role_id!=1){
          $data = $data->where('users.company_id',$company_id);
        }
        $data = $data->orderby('users.name')->get();
      }   



        $company = Company::select('id','company_name')->orderby('company_name')->get();
        return view('users',['view'=>$view,'data'=>$data,'company'=>$company]);
    }

    public function create_user(Request $request){

      try{
        $name = $request->name;
        $email = $request->email;
        $mobile = $request->mobile;
        $location = $request->location;
        $password = $request->password;
        $company_id = $request->company_id;
        $role_id = $request->role_id;

        $id = User::insertGetId([
            'name' => $name,
            'email' => $email,
            'mobile' => $mobile,
            'location' => $location,
            'password' => bcrypt($password),
            'company_id' => $company_id,
            'role_id' => $role_id,
            'created_at'=> now()->setTimezone('UTC')
        ]);        
  
          $this->sendEmail('Your Superfence account has been created', url('/login'),$email,$password, $email, $emailFrom = "");

          return response()->json(['status'=>true,'message' => 'New User Created Successfully']);

        }catch(Exception $e){       
          
          Log::info($e->getMessage() . '' . $e->getLine());
            
          return response()->json(['status'=>false,'message' => $e->getMessage()]);
        
        }
    }

    public function edit_user(Request $request){

      try{
        $name = $request->name;
        $email = $request->email;
        $mobile = $request->mobile;
        $location = $request->location;        
        $password = $request->password;
        $company_id = $request->company_id;
        $role_id = $request->role_id;
        $id = $request->id;

        if($request->oldpassword!=''){
          $count = User::where('password',bcrypt($request->oldpassword))->where('id',$id)->count();
          if($count>0){
            $updated = User::where('id',$id)->update([
                'name' => $name,
                'email' => $email,
                'mobile' => $mobile,
                'location' => $location,
                'password' => bcrypt($password),
                'company_id' => $company_id,
                'role_id' => $role_id,
                'updated_at'=> now()->setTimezone('UTC')
            ]); 
          }
        }else{
              $updated = User::where('id',$id)->update([
                'name' => $name,
                'email' => $email,
                'mobile' => $mobile,
                'location' => $location,
                'company_id' => $company_id,
                'role_id' => $role_id,
                'updated_at'=> now()->setTimezone('UTC')
            ]);
          }

       //$this->sendEmail('BLE Account Creation', url('/login'),$email,$password, $email, $emailFrom = "");

           return response()->json(['status'=>true,'message' => 'User updated Successfully']);

        }catch(Exception $e){       
          
          Log::info($e->getMessage() . '' . $e->getLine());
            
          return response()->json(['status'=>false,'message' => $e->getMessage()]);
        
        }
    }

    public function userslocations(Request $request){
        $company_id = Auth::user()->company_id;
        $data = UsersLocations::leftjoin('users','users.id','=','userslocations.user_id')
                ->select('userslocations.locationlat','userslocations.locationlong','userslocations.locationdatetime','users.name')
                ->where('users.company_id',$company_id)
                ->orderBy('userslocations.id', 'DESC')
                ->get();
        return view('userslocations', ['data'=>$data]);
    }


public function importCsvUsers(Request $request){

    if ($request->input('submit') != null  ){

      $file = $request->file('file');
      $company_id = Auth::user()->company_id;

      // File Details 
      $filename = $file->getClientOriginalName();
      $extension = $file->getClientOriginalExtension();
      $tempPath = $file->getRealPath();
      $fileSize = $file->getSize();
      $mimeType = $file->getMimeType();
      // Valid File Extensions
      $valid_extension = array("csv");

      // 2MB in Bytes
      $maxFileSize = 2097152; 

      // Check file extension
      if(in_array(strtolower($extension),$valid_extension)){

        // Check file size
        if($fileSize <= $maxFileSize){

          // File upload location
          $location = 'uploads';

          // Upload file
          $file->move($location,$filename);

          // Import CSV to Database
          $filepath = public_path($location."/".$filename);

          // Reading file
          $file = fopen($filepath,"r");

          $importData_arr = array();
          $i = 0;

          while (($filedata = fgetcsv($file, 1000, ",")) !== FALSE) {
             $num = count($filedata );
             
             // Skip first row (Remove below comment if you want to skip the first row)
             /*if($i == 0){
                $i++;
                continue; 
             }*/
             for ($c=0; $c < $num; $c++) {
                $importData_arr[$i][] = $filedata [$c];
             }
             $i++;
          }
          fclose($file);    
          $iData=0;
          // Insert to MySQL database
          foreach($importData_arr as $importData){
            if($iData>0){ 
                $insertData = array(
                'name' => $importData[0],
                'email' => $importData[1],
                'password' => bcrypt($importData[2]),
                'company_id' => $company_id,
                'role_id' => '3',
                'created_at'=> now()->setTimezone('UTC')
                );
                User::insertGetId($insertData);
            }

            $this->sendEmail('BLE Account Creation', url('/login'),$importData[1],$importData[2], $importData[1], $emailFrom = "");
            $iData++;            
          }

          return response()->json(['status'=>true,'message' => 'File imported successfully']);
        }else{
          return response()->json(['status'=>false,'message' => 'File must be less than 2MB']);
        }

      }else{
         return response()->json(['status'=>false,'message' => 'Invalid File Extension.']);
      }

    }

    // Redirect to index
    //return redirect()->action('UsersController@user');
  }

}