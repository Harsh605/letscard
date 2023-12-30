<?php
class ColorPrediction1Min extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(['ColorPrediction1Min_model','Users_model']);
    }

    public function index()
    {
        $AllGames = $this->ColorPrediction1Min_model->AllGames();
        $RandomFlag = $this->ColorPrediction1Min_model->getRandomFlag('color_prediction_1_min_random');
        // foreach ($AllGames as $key => $value) {
        //     $AllGames[$key]->details=$this->ColorPrediction1Min_model->ViewBet('',$value->id);
        // }
        // echo '<pre>';print_r($AllGames);die;
        $data = [
            'title' => 'Color Predection 1 Min History',
            'AllGames' => $AllGames,
            'RandomFlag'=>$RandomFlag->color_prediction_1_min_random
        ];
        template('color_prediction_1_min/index', $data);
    }

    public function color_prediction_bet($id){

        $AllUsers = $this->ColorPrediction1Min_model->ViewBet('',$id);
        foreach ($AllUsers as $key => $value) {
            $user_details= $this->Users_model->UserProfile($value->user_id);
            if($user_details){
                $AllUsers[$key]->user_name=$user_details[0]->name;
            }else{
                $AllUsers[$key]->user_name='';
            }
        }
        $data = [
            'title' => 'Game History',
            'AllUsers' => $AllUsers
        ];
        template('color_prediction_1_min/show_details', $data);
    }
    public function ChangeStatus() {
        
        $Change = $this->ColorPrediction1Min_model->ChangeStatus();
        if ( $Change ) {
            echo 'true';
        } else {
            echo 'false';
        }
       
    }
}
