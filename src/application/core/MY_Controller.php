<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

// superclass aplikasi
class MY_Controller extends CI_Controller {
    
    /** Constructor */
    public function __construct(){
        parent::__construct();
        
        // default timezone
        date_default_timezone_set('Asia/Jakarta');
    }
}

// untuk subclass dengan pengecekan login
class Management_Controller extends MY_Controller {

    protected $user_session = null;

    /** Constructor */
    public function __construct(){
        parent::__construct();

        if(! $this->session->userdata(APP_SESSION_NAME)){
            redirect('auth');
        }else{
            $this->user_session = $this->session->userdata(APP_SESSION_NAME);
        }
    }
}

// untuk subclass tanpa pengecekan login
class Landing_Controller extends MY_Controller {

    /** Cnstructor */
    public function __construct(){
        parent::__construct();
    }
}