<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Auth extends CI_Controller
{
    function __construct() {
        parent::__construct();
        
        $this->load->config('linkedin');
        $this->load->model('user');
    }
    
    public function index(){
        $userData = array();
        
        include_once APPPATH."libraries/linkedin-oauth-client/http.php";
        include_once APPPATH."libraries/linkedin-oauth-client/oauth_client.php";
        
        $oauthStatus = $this->session->userdata('oauth_status');
        $sessUserData = $this->session->userdata('userData');
        $sessPosition = $this->session->userdata('position');
        
        if(isset($oauthStatus) && $oauthStatus == 'verified'){
            $userData = $sessUserData;
            $position = $sessPosition;
        }elseif((isset($_REQUEST["oauth_init"]) && $_REQUEST["oauth_init"] == 1) || (isset($_REQUEST['oauth_token']) && isset($_REQUEST['oauth_verifier']))){
            $client = new oauth_client_class;
            $client->client_id = $this->config->item('linkedin_api_key');
            $client->client_secret = $this->config->item('linkedin_api_secret');
            $client->redirect_uri = 'http://localhost/profile-import-share/index.php/'.$this->config->item('linkedin_redirect_url');
            $client->scope = $this->config->item('linkedin_scope');
            $client->debug = false;
            $client->debug_http = true;
            $application_line = __LINE__;
            
            //If authentication returns success
            if($success = $client->Initialize()){
                if(($success = $client->Process())){
                    if(strlen($client->authorization_error)){
                        $client->error = $client->authorization_error;
                        $success = false;
                    }elseif(strlen($client->access_token)){
                        $profile_fileds = array(
                            'id',
                            'firstName',
                            'maiden-name',
                            'lastName',
                            'picture-url',
                            'email-address',
                            'location',
                            'industry',
                            'summary',
                            'specialties',
                            'positions',
                            'public-profile-url',
                            'last-modified-timestamp',
                            'num-recommenders',
                            'date-of-birth',
                        );
                        $success = $client->CallAPI('http://api.linkedin.com/v1/people/~:('.implode(',',$profile_fileds).')', 
                        'GET',
                        array('format'=>'json'),
                        array('FailOnAccessError'=>true), $userInfo);
                    }
                }
                $success = $client->Finalize($success);
            }
            
            if($client->exit) exit;
    
            if($success){
                //Preparing data for database insertion
                $first_name = !empty($userInfo->firstName)?$userInfo->firstName:'';
                $last_name = !empty($userInfo->lastName)?$userInfo->lastName:'';
                $summary = !empty($userInfo->summary)?$userInfo->summary:'';
                $specialties = !empty($userInfo->specialties)?$userInfo->specialties:'';
                $title = !empty($userInfo->positions->values)?$userInfo->positions->values[0]->title:'';
                $position_summary = !empty($userInfo->positions->values) && !empty($userInfo->positions->values[0]->summary)?$userInfo->positions->values[0]->summary:'';
                $startDate = !empty($userInfo->positions->values)?$userInfo->positions->values[0]->startDate:null;
                $start = $startDate == null ? '' : $startDate->month.'/'.$startDate->year;
                $endDate = !empty($userInfo->positions->values) && !empty($userInfo->positions->values[0]->endDate)?$userInfo->positions->values[0]->endDate:null;
                $end= $endDate == null ? '' : $endDate->month.'/'.$endDate->year;
                $company = !empty($userInfo->positions->values)?$userInfo->positions->values[0]->company->name:'';

                $userData = array(
                    'oauth_provider'=> 'linkedin',
                    'oauth_uid'     => $userInfo->id,
                    'first_name'     => $first_name,
                    'last_name'     => $last_name,
                    'email'         => $userInfo->emailAddress,
                    'locale'         => $userInfo->location->country->code,
                    'current_location' => $userInfo->location->name,
                    'industry'         =>$userInfo->industry,
                    'summary'          => $summary,
                    'specialties'      => $specialties,
                    'profile_url'     => $userInfo->publicProfileUrl,
                    'picture_url'     => $userInfo->pictureUrl
                );
                $position = array(
                    'title'         => $title,
                    'summary'  => $position_summary,
                    'start_date'    => $start,
                    'end_date'    => $end,
                    'company'    => $company
                );
                
                //Insert or update user data
                $userID = $this->user->checkUser($userData);
                $positionID = $this->user->checkPosition($userID, $position);
                
                //Store status and user profile info into session
                $this->session->set_userdata('oauth_status','verified');
                $this->session->set_userdata('userData',$userData);
                $this->session->set_userdata('position',$position);
                
                //Redirect the user back to the same page
                redirect('/auth');

            }else{
                 $data['error_msg'] = 'Some problem occurred, please try again later!';
            }
        }elseif(isset($_REQUEST["oauth_problem"]) && $_REQUEST["oauth_problem"] <> ""){
            $data['error_msg'] = $_GET["oauth_problem"];
        }else{
            $data['oauthURL'] = 'http://localhost/profile-import-share/index.php/'.$this->config->item('linkedin_redirect_url').'?oauth_init=1';
        }
        
        $data['userData'] = $userData;
        $data['position'] = $position;
        
        // Load login & profile view
        $this->load->view('auth/index',$data);
    }

    public function logout() {
        //Unset token and user data from session
        $this->session->unset_userdata('oauth_status');
        $this->session->unset_userdata('userData');
        $this->session->unset_userdata('position');
        
        //Destroy entire session
        $this->session->sess_destroy();
        
        // Redirect to login page
        redirect('/auth');
    }
}