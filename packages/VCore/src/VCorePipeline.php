<?php

namespace Packages\VCore;

use Illuminate\Pipeline\Pipeline;
use Packages\VCore\Actions\CreateFolderAction;
use Packages\VCore\Actions\ValidateInputAction;
use Packages\VCore\Actions\WriteFileAction;

class VCorePipeline
{
    protected array $pipes = [
        ValidateInputAction::class,
        CreateFolderAction::class,
        WriteFileAction::class,
    ];

    /**
     * @param array $payload
     * @return mixed
     */
    public function handle(array $payload): mixed
    {
        return app(Pipeline::class)
            ->send($payload)
            ->through($this->pipes)
            ->thenReturn();
    }
}
