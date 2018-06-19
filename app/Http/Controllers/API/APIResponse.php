<?php

namespace App\Http\Controllers\API;


trait APIResponse
{


    public  $paginate_number = 10 ;


    public function fmtResponse($data=null,$error=null,$code=200)
    {
        $response_array = [

            'data'=> $data,
            'state'=> in_array($code,$this->successCodes()) ?true:false,
            'error'=> $error,
        ];

        return response($response_array,$code);
    }


   public function successCodes(){
        return [200,201,202];
    }

    public  function notFound(){
        return $this->fmtResponse(null, 'object not found', 404);

    }
}
