<?php 


class LoginController extends Controller
{

public function loginSSO(Request $request, $key){

    if(!empty($key)){

//BY DEFAULT HANYA SSO_ID PERLU DIUBAH - Helpdesk
//settings-----------------------------------------
$SSO_ID = 361;
$KEY = $key; // $_GET['key'];
$SSO_URL = "https://sso.mbpj.gov.my/auth/$SSO_ID/$KEY";
//-------------------------------------------------


//GET INFORMATION FROM SSO SYSTEM ABOUT CURRENT USER BASE ON KEY GIVEN
$curl = curl_init();
curl_setopt_array($curl, array(
CURLOPT_RETURNTRANSFER => 1,
CURLOPT_URL => $SSO_URL,
));

if (!curl_exec($curl)) {
    die('Error: "' . curl_error($curl) . '" - Code: ' . curl_errno($curl));
}

$resp = curl_exec($curl);
curl_close($curl);
$data = json_decode($resp);

/*
$data akan return:
    - $data->status
        - return 'ok' or 'error'
    - $data->no_ic 	
    - $data->staff_id
    - $data->email
*/
if ($data->status == 'ok') {

/*
isikan logic code untuk login berdasarkan data yang diperolehi dari sistem sso, iaitu di antara login menggunakan
$data->no_ic, $data->staff_id, atau $data->email tanpa mengguna password

//code for dashboar view based on access level -NOR22122022

contoh:
*/

//$sqlLogin = "select * from user u where staff_id='".$data->staff_id."'";
//	$login = mysql_query($sqlLogin);
//$rowLogin = mysql_fetch_array($login);
/* $_SESSION['id']=$rowLogin[0];
$_SESSION['user_login']=$rowLogin[2];
$_SESSION['user_name']=$rowLogin[1];
$_SESSION['user_email']=$rowLogin[4];
$_SESSION['user_notel']=$rowLogin[5];
*/
                  
// echo '<META HTTP-EQUIV="Refresh" Content="0; URL=main.php">';

$user = User::where('username', $data->no_ic)
->first();

if (!$user) {
    return $this->sendFailedLoginResponse($request);
}

$ret = $this->getUserInfoFromPrUser(  $data->no_ic );

    $user->email = $data->email;
    $user->departmentcode = $ret->departmentcode;
    $user->save();


$logRole = new Role;
$logRole->user_id = $user->id;
$logRole->roles = 'user';
$logRole->is_admin = 0; //set sendiri based on acl system ejkp
$logRole->save();
Auth::login($user);
session(['acl' => 'normal']); //semua acl
    return redirect('/dashboard');//->intended($this->redirectPath()); //redirect to file dashboard -> landing page after login from sso -NOR
} else {
    if ($user->role->is_admin == 1) {
        if ($user->role->is_admin == 1) {
            Auth::login($user);
            session(['acl' => 'admin']);
       
            return redirect('admin/dashboard');
        } else {
            return back()->with('error', 'Anda tiada akses sebagai Admin');
        }
    } else {
        Auth::login($user);
        session(['acl' => 'normal']);
        return redirect()->intended($this->redirectPath());
    }
}

}else {
    echo "error SSO";
}
    echo "error no key";
}







function getUserInfoFromPrUser( $username ){
    $u = DB::select("SELECT * from MAJLIS.PRUSER where ".  strtoupper( "username" )
                    ." = ? "
                   ,[$username]);



        //->first();

        //dd($u[0]->email);

        if(!empty($u) && count($u)>0)
            return $u[0];

        return FALSE;
}


}

?>