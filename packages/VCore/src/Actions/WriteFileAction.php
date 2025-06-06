<?php

namespace Packages\VCore\Actions;

use Closure;

class WriteFileAction
{
    public function handle($payload, Closure $next): mixed
    {
        $replaces = [
            '$NAME' => $payload['name'],
            '$name' => strtolower((string) $payload['name']),
        ];

        foreach ($payload['fullFolders'] as $file) {
            if (in_array(substr((string) $file, -4, 4), ['.php', 'json', 'e.md'])) {
                $folderOrLink = $payload['pathRoot'] . '/' . $file;

                switch ($file) {
                    case 'configs/' . strtolower((string) $payload['name']) . '.php':
                        $this->write($folderOrLink, 'config');
                        break;

                    case 'routes/api.php':
                        $this->write($folderOrLink, 'api');
                        break;

                    case 'routes/web.php':
                        $this->write($folderOrLink, 'web');
                        break;

                    case 'src/DTOs/' . $payload['name'] . 'DTO.php':
                        $this->write($folderOrLink, 'dto', $replaces);
                        break;

                    case 'src/Providers/' . $payload['name'] . 'ServiceProvider.php':
                        $this->write($folderOrLink, 'serviceprovider', $replaces);
                        break;

                    case 'src/Repositories/' . $payload['name'] . 'Repository.php':
                        $this->write($folderOrLink, 'repository', $replaces);
                        break;

                    case 'src/Repositories/' . $payload['name'] . 'RepositoryInterface.php':
                        $this->write($folderOrLink, 'repositoryinterface', $replaces);
                        break;

                    case 'composer.json':
                        $this->write($folderOrLink, 'composer', $replaces);
                        break;

                    case 'src/Models/' . $payload['name'] . '.php':
                        $this->write($folderOrLink, 'model', $replaces);
                        break;

                    case 'src/Http/Controllers/' . $payload['name'] . 'Controller.php':
                        $this->write($folderOrLink, 'controller', $replaces);
                        break;

                    case 'src/Http/Requests/' . $payload['name'] . 'Request.php':
                        $this->write($folderOrLink, 'request', $replaces);
                        break;
                }
            }
        }

        if (isset($payload['schema'])) {
            $this->write($payload['schema'], 'schema', $replaces);
        }

        return $next($payload);
    }

    protected function write($folderOrLink = '', $file = '', $replaces = []): void
    {
        $content = file_get_contents(__DIR__ . '/WriteFileTemplate/' . $file . '.hv');
        if ($content !== false) {
            $content = str_replace(array_keys($replaces), array_values($replaces), $content);

            file_put_contents($folderOrLink, $content, LOCK_EX);
        }
    }
}
