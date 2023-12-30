<?php
class Purchase extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(['Users_model']);
    }

    public function index()
    {
        $data = [
            'title' => 'Purchase History Management',
            'AllPurchase' => $this->Users_model->Purchase_History()
        ];
        template('Purchase/index', $data);
    }
    public function offline(){
        $data = [
            'title' => 'Purchase History Offline',
            'AllPurchase' => $this->Users_model->Purchase_History_Offline()
        ];
        template('Purchase/offline', $data);
    }
    public function robot(){
        $data = [
            'title' => 'Purchase History robot',
            'AllPurchase' => $this->Users_model->Purchase_History_Robot()
        ];
        template('Purchase/robot', $data);
    }
   

}
