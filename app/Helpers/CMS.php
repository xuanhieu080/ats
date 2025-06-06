<?php


namespace App\Helpers;


use App\Models\Config;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CMS
{
    public static final function urlBase($url = null)
    {
        $base = env("APP_URL");
        if (!empty($_SERVER['HTTP_REFERER'])) {
            $base = $_SERVER['HTTP_REFERER'];
        } elseif (!empty($_SERVER['HTTP_ORIGIN'])) {
            $base = $_SERVER['HTTP_ORIGIN'];
        }
        $base = $base . $url;
        $base = str_replace(" ", "", $base);
        $base = str_replace("\\", "/", $base);
        $base = str_replace("//", "/", $base);
        $base = str_replace(":/", "://", $base);

        return $base;
    }

    public static final function genCode($table, $column, $length = 10, $isNumber = false)
    {
        $key = Str::random($length);
        if ($isNumber) {
            $key = sprintf("%" . $length . "d", mt_rand(1, 999999));
        }
        $item = DB::table("$table")->where("$column", $key)->first();
        if ($item) {
            self::genCode($table, $column, $length);
        }
        return $key;
    }

    public static final function allImageMimeTypes(): array
    {
        return [
            'image/apng',
            'image/avif',
            'image/bmp',
            'image/cgm',
            'image/dicom-rle',
            'image/emf',
            'image/fits',
            'image/g3fax',
            'image/gif',
            'image/heic',
            'image/heic-sequence',
            'image/heif',
            'image/heif-sequence',
            'image/hej2k',
            'image/hsj2k',
            'image/ief',
            'image/jls',
            'image/jp2',
            'image/jpeg',
            'image/jph',
            'image/jphc',
            'image/jpm',
            'image/jpx',
            'image/jxr',
            'image/jxra',
            'image/jxrs',
            'image/jxs',
            'image/jxsc',
            'image/jxsi',
            'image/jxss',
            'image/ktx',
            'image/png',
            'image/sgi',
            'image/svg+xml',
            'image/t38',
            'image/tiff',
            'image/tiff-fx',
            'image/vnd.adobe.photoshop',
            'image/vnd.airzip.accelerator.azv',
            'image/vnd.dece.graphic',
            'image/vnd.djvu',
            'image/vnd.dvb.subtitle',
            'image/vnd.dwg',
            'image/vnd.dxf',
            'image/vnd.fastbidsheet',
            'image/vnd.fpx',
            'image/vnd.fst',
            'image/vnd.fujixerox.edmics-mmr',
            'image/vnd.fujixerox.edmics-rlc',
            'image/vnd.globalgraphics.pgb',
            'image/vnd.microsoft.icon',
            'image/vnd.mix',
            'image/vnd.ms-modi',
            'image/vnd.mozilla.apng',
            'image/vnd.net-fpx',
            'image/vnd.radiance',
            'image/vnd.sealed.png',
            'image/vnd.sealedmedia.softseal.gif',
            'image/vnd.sealedmedia.softseal.jpg',
            'image/vnd.svf',
            'image/vnd.tencent.tap',
            'image/vnd.valve.source.texture',
            'image/vnd.wap.wbmp',
            'image/vnd.xiff',
            'image/vnd.zbrush.pcx',
            'image/webp',
            'image/wmf',
            'image/x-3ds',
            'image/x-cmu-raster',
            'image/x-cmx',
            'image/x-freehand',
            'image/x-icon',
            'image/x-jng',
            'image/x-mrsid-image',
            'image/x-nikon.nef',
            'image/x-pcx',
            'image/x-pict',
            'image/x-portable-anymap',
            'image/x-portable-bitmap',
            'image/x-portable-graymap',
            'image/x-portable-pixmap',
            'image/x-rgb',
            'image/x-tga',
            'image/x-xbitmap',
            'image/x-xpixmap',
            'image/x-xwindowdump'
        ];
    }

    public static final function allImageMimeTypeString(): string
    {
        return implode(',', self::allImageMimeTypes());
    }

    public static final function uploadApi(UploadedFile $image)
    {
//        if (env('APP_ENV', 'local') == 'local') {
//            try {
//                $dt = Carbon::now();
//                $dt->format('m-y');
//                $path ='images/' . $dt->format('m-Y');
//                $fileName = $image->storePublicly(
//                    "$path", ['disk' =>isset($_ENV['VAPOR_ARTIFACT_NAME']) ? 's3' : config('jetstream.profile_photo_disk', 'public')]
//                );
//            } catch (\Exception $ex) {
//                DB::rollBack();
//                FORUM_CMS_ERROR::handle($ex, 'images');
//            }
//            return env('APP_URL', 'https://api.hegka.com') ."/$fileName";
//        } else {
        $options = [];
        try {
            $filePath = $image->getRealPath();
            $fileMime = $image->getMimeType();
            $fileUploadName = $image->getClientOriginalName();

            $client = new Client();
            if (empty($token)) {
                self::loginApi();
                $token = Cache::get('cloud_token');
            }

            $options = [
                'headers'   => [
                    'Authorization' => "Bearer $token",
                    'Accept'        => "application/json",
                ],
                'multipart' => [
                    [
                        'name'      => 'file',
                        'contents'  => fopen($filePath, 'r'),
                        'filename'  => $fileUploadName,
                        'Mime-Type' => $fileMime,
                    ],
                    [
                        'name'     => 'key',
                        'contents' => time()
                    ],
                    //                    [
                    //                        'name'     => 'resize',
                    //                        'contents' => 1
                    //                    ],
                    //                    [
                    //                        'name'     => 'resize_width',
                    //                        'contents' => 600
                    //                    ],
                    [
                        'name'     => 'now',
                        'contents' => 1
                    ]
                ]
            ];

            $response = $client->post(env('CLOUD_SERVER', 'https://cloud.hegka.com') . '/api/uploads', $options);

            return json_decode($response->getBody()->getContents(), true)['uuid'];
        } catch (\Exception $exception) {
            if ($exception->getCode() == 401) ;
            {
                self::loginApi();
                $token = Cache::get('cloud_token');
                $options['headers'] = [
                    'Authorization' => "Bearer $token",
                    'Accept'        => "application/json",
                ];

                $response = $client->post(env('CLOUD_SERVER', 'https://cloud.hegka.com') . '/api/uploads', $options);
                return json_decode($response->getBody()->getContents(), true)['uuid'];
            }
        }
//        }
    }

    public static final function loginApi()
    {
        $response = Http::post(env('CLOUD_SERVER', 'https://cloud.hegka.com') . '/api/users/login', [
            'email'    => env('CLOUD_EMAIL', 'hegka@gmail.com'),
            'password' => env('CLOUD_PASSWORD', 'hegka@gmail.com123@@')
        ]);

        if (!$response->json()['error']) {
            Cache::forever('cloud_token', $response->json()['token']);
            return true;
        } else {
            \Illuminate\Support\Facades\Log::info(['message' => 'Login cloud: ' . $response->json()['message']]);
            return false;
        }

    }
}
