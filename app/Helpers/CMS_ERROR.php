<?php

namespace App\Helpers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CMS_ERROR
{
    public static function handle(\Exception $ex, $table = "unknown")
    {
        $errorCode = $ex->getCode();
        $errorCode = empty($errorCode) ? Response::HTTP_BAD_REQUEST : $errorCode;

        //        if (env('APP_DEBUG', false) == true) {
        //            $request = Request::capture();
        //            $param = $request->all();
        //            $data = [
        //                'server'     => ETC_POS::urlBase(),
        //                'time'       => date("Y-m-d H:i:s", time()),
        //
        //                'param'      => json_encode($param),
        //                'file'       => $ex->getFile(),
        //                'line'       => $ex->getLine(),
        //                'error'      => $ex->getMessage(),
        //            ];
        //
        //            //Write Log
        //        }
        $user_id = 0;

        $request = Request::capture();
        $param = $request->all();
        $data = [
            'action'  => 'ERROR',
            'target'  => "Table: $table",
            'ip'      => $_SERVER['REMOTE_ADDR'],
            //            'browser' => "Parent: $parent - Platform: $platform - Browser: $browser",
            'link'    => url()->current(),
            'server'  => CMS::urlBase(),
            'time'    => date("Y-m-d H:i:s", time()),
            'user_id' => $user_id,
            'param'   => json_encode($param),
            'file'    => $ex->getFile(),
            'line'    => $ex->getLine(),
            'error'   => $ex->getMessage(),
        ];

        \Illuminate\Support\Facades\Log::info($data);
        return ['message' => $ex->getMessage(), 'code' => (int)$errorCode];

    }
}
