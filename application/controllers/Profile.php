<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Profile extends CI_Controller
{
    function __construct() {
        parent::__construct();
        $this->load->model('user');
    }
    
    public function index(){
        // $userData = array();
        // $position = array();

        $oauthStatus = $this->session->oauth_status;
        $sessUserData = $this->session->userData;
        $sessPosition = $this->session->position;

        if(isset($oauthStatus) && $oauthStatus == 'verified'){
            $userData = $sessUserData;
            $position = $sessPosition;
            $data['title']="Profile Page";
        }elseif(isset($_REQUEST["oauth_problem"]) && $_REQUEST["oauth_problem"] <> ""){
            $data['error_msg'] = $_GET["oauth_problem"];
        }
        
        $this->load->helper('form');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('firstName', 'First Name', 'required');
        $this->form_validation->set_rules('to', 'To', 'required');

        $data['userData'] = $userData;
        $data['position'] = $position;
        $data['success_message'] = $this->session->success_message;
        $this->session->success_message = null;
        
        // Load login & profile view
        $this->load->view('templates/header',$data);
        $this->load->view('profile/index',$data);
        $this->load->view('templates/footer');
    }

    public function create(){
        $this->load->helper('form');
        $this->load->library('form_validation');
        //$this->form_validation->set_rules('firstName', 'First Name', 'required');
        //$this->form_validation->set_rules('to', 'To', 'required');
        // if($this->form_validation->run() == FALSE){
        //     $data['title'] = "Add Profile";
        //     $this->load->view('templates/header',$data);
        //     $this->load->view('profile/index');
        //     $this->load->view('templates/footer');
        // }
        // else{
            //Insert or update user data
            $sessUserData = $this->session->userData;
            // $sessPosition = $this->session->position;
            // $sessUserData['first_name'] = $this->input->
            $userData = array(
                'oauth_provider'=> 'linkedin',
                'oauth_uid'     => $sessUserData['oauth_uid'],
                'first_name'     => $this->input->post('first_name'),
                'last_name'     => $this->input->post('last_name'),
                'email'         => $this->input->post('email'),
                'current_location' => $this->input->post('current_location'),
                'industry'         =>$this->input->post('industry'),
                'profile_url'     => $sessUserData['profile_url'],
                'picture_url'     => $sessUserData['picture_url']
            );
            $position = array(
                'title'         => $this->input->post('title'),
                'start_date'    => $this->input->post('start_date'),
                'end_date'    => $this->input->post('end_date'),
                'company'    => $this->input->post('company')
            );
            $userID = $this->user->checkUser($userData);
            $positionID = $this->user->checkPosition($userID, $position);
            $data['success_message'] = "Profile save successfully!";


            $this->session->userData = $userData;
            $this->session->position = $position;
            $this->session->success_message = "Profile save successfully!";
            redirect('/profile');
            //$this->load->view('profile/index');
        // }

        
    }

    public function share(){
        $this->session->success_message = "Profile shared successfully!";
        $sessUserData = $this->session->userData;
        $key = "key-8440bdd840f7d95e1fc63b488bbbaa23";
		//$domain = "mg.repllcglobal.com";	
		$userAndPosition = $this->user->getUserAndPosition($sessUserData['oauth_uid']);
	
        $body = "Name : ".$userAndPosition[0]['first_name']." ".$userAndPosition[0]['last_name']."\n"
                ."Email : ".$userAndPosition[0]['email']."\n"
                ."Location : ".$userAndPosition[0]['current_location']."\n"
                ."Industry : ".$userAndPosition[0]['industry']."\n"
                ."Title : ".$userAndPosition[1]['title']."\n"
                ."Company : ".$userAndPosition[1]['company']."\n"
                ."Start Date : ".$userAndPosition[1]['start_date']."\n"
                ."End Date : ".$userAndPosition[1]['end_date']."\n";
		$message = array(
				'text' => $body,
				'subject' => $userAndPosition[0]['first_name']." shared their profile with you",
				'from' => 'Piyush Khera <piyush.khera88@gmail.com>',
				'to' => 'Piyush Khera <piyush.khera88@gmail.com>'
            );
            $config = array();
 
	$config['api_key'] = $key;
 
	$config['api_url'] = "https://api.mailgun.net/v3/sandbox593bda53ca2a45998e94b5261a2cb460.mailgun.org/messages";

 
	$ch = curl_init();
 
	curl_setopt($ch, CURLOPT_URL, $config['api_url']);
 
	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
 
	curl_setopt($ch, CURLOPT_USERPWD, "api:{$config['api_key']}");
 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
 
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
 
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
 
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
 
	curl_setopt($ch, CURLOPT_POST, true); 
 
	curl_setopt($ch, CURLOPT_POSTFIELDS,$message);
 
	$result = curl_exec($ch);
	//echo $result;
	curl_close($ch);
        redirect('/profile');
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