<?php

namespace App\Helpers;

use App\Models\Media;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class HasImage
{

    public static function getFileToMedia($media): ?UploadedFile
    {
        if (!$media || empty($media->path)) {
            return null;
        }

        $disk = Storage::disk(self::disk());
        $path = $media->path;

        if (!$disk->exists($path)) {
            return null;
        }

        $mimeType = $media->mime_type ?? 'application/octet-stream';
        $fileName = basename($path);

        if (method_exists($disk, 'path')) {
            $realPath = $disk->path($path);
            if (file_exists($realPath)) {
                return new UploadedFile($realPath, $fileName, $mimeType, null, true);
            }
        }

        try {
            $stream = $disk->readStream($path);
            if (!$stream) {
                return null;
            }

            $tempPath = tempnam(sys_get_temp_dir(), 'upload_' . uniqid());

            file_put_contents($tempPath, stream_get_contents($stream), LOCK_EX);
            fclose($stream);

            return new UploadedFile($tempPath, $fileName, $mimeType, null, true);
        } catch (\Exception $e) {
            return null;
        }
    }

    public static final function getImage($path)
    {
        if (empty($path)) {
            return null;
        }

        if (Storage::disk(self::disk())->exists($path)) {
            return Storage::disk(self::disk())->url($path);
        }

        return null;
    }

    public static final function addImage(UploadedFile $file, $subdir = 'uploads')
    {
        return self::store($file, $subdir);
    }

    public static final function updateImage(UploadedFile $file, $path, $subdir = 'uploads')
    {
        if (!empty($path)) {
            $fileCurrent = Storage::disk(self::disk());
            if ($fileCurrent->exists($path)) {
                $fileCurrent->delete($path);
            }
        }

        return self::store($file, $subdir);
    }

    /**
     * Deletes a file
     * @param   $path
     * @return bool
     */
    public static final function deleteImage($path)
    {
        if (!empty($path)) {
            $file = Storage::disk(self::disk());
            if ($file->exists($path)) {
                $file->delete($path);
            }
        }
    }

    public static final function store(UploadedFile $file, $subdir, $disk = null)
    {
        $name = $file->hashName();
        $dir = "{$subdir}";
        if (empty($disk)) {
            $disk = self::disk();
        }

        return $file->storeAs($dir, $name, ['disk' => $disk]);
    }

    public static final function disk()
    {
        return config('filesystems.default', 'public');
    }

    public static final function createMedia(UploadedFile $file, $subdir = 'uploads')
    {
        $path = self::store($file, $subdir);
        $disk = self::disk();
        $userId = Auth::id();

        return Media::query()->create([
            'name'       => $file->getClientOriginalName(),
            'code'       => CMS::genCode('media', 'code', 16),
            'link'       => parse_url(Storage::disk($disk)->url($path), PHP_URL_PATH),
            'path'       => $path,
            'status'     => 0,
            'created_by' => $userId,
            'updated_by' => $userId,
            'size'       => $file->getSize(),
            'mime_type'  => $file->getMimeType(),
            'extension'  => $file->getClientOriginalExtension(),
        ]);
    }

    public static final function updateMedia(UploadedFile $file, $subdir = 'uploads', Media $media = null)
    {
        $disk = self::disk();
        $userId = Auth::id();

        $data = [
            'name'       => $file->getClientOriginalName(),
            'status'     => 0,
            'created_by' => $userId,
            'updated_by' => $userId,
            'size'       => $file->getSize(),
            'mime_type'  => $file->getMimeType(),
            'extension'  => $file->getClientOriginalExtension(),
        ];

        if ($media) {
            $path = self::updateImage($file, $media->path, $subdir);
            $data['path'] = $path;
            $data['link'] = parse_url(Storage::disk($disk)->url($path), PHP_URL_PATH);
            $media->update($data);
        } else {
            $path = self::store($file, $subdir);
            $data['path'] = $path;
            $data['link'] = parse_url(Storage::disk($disk)->url($path), PHP_URL_PATH);
            $data['code'] = CMS::genCode('media', 'code', 16);
            $media = Media::query()->create($data);
        }

        return $media;
    }
}
