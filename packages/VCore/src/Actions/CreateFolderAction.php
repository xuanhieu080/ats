<?php

namespace Packages\VCore\Actions;

use Closure;

class CreateFolderAction
{
    public function handle($payload, Closure $next)
    {
        $pathRoot = __DIR__ . '/../../../../packages/' . $payload['name'];

        if (is_dir($pathRoot)) {
            return ['error' => true, 'message' => "Module {$payload['name']} already exists"];
        }

        mkdir($pathRoot, 0755, true);

        $fullFolders = [
            'configs', 'configs/' . strtolower((string) $payload['name']) . '.php',
            'database', 'database/migrations',
            'public',
            'resources', 'resources/views', 'resources/js', 'resources/css', 'resources/lang',
            'routes', 'routes/api.php', 'routes/web.php',
            'src',
                'src/DTOs', 'src/DTOs/' . $payload['name'] . 'DTO.php',
                'src/Http', 'src/Http/Controllers', 'src/Http/Requests', 'src/Jobs',
                'src/Models', 'src/Providers', 'src/Providers/'.$payload['name'].'ServiceProvider.php',
                'src/Repositories', 'src/Repositories/'.$payload['name'].'Repository.php',
                'src/Repositories/'.$payload['name'].'RepositoryInterface.php',
                'src/Services', 'src/Services/'.$payload['name'].'Service.php',
            'composer.json',
            'Readme.md'
        ];

        #Options
        foreach ($payload['options'] as $key) {
            switch ($key) {
                case 'm':
                    $fullFolders[] = 'src/Models/' . $payload['name'].'.php';
                    break;

                case 'c':
                    $fullFolders[] = 'src/Http/Controllers/' . $payload['name'].'Controller.php';
                    break;

                case 'r':
                    $fullFolders[] = 'src/Http/Requests/' . $payload['name'].'Request.php';
                    break;

                case 's':
                    $payload['schema'] = date('Y_m_d_h_i_s') . '_' . $payload['name'];
                    $payload['schema'] = 'database/migrations/' . $payload['schema'] .'.php';

                    $fullFolders[] = $payload['schema'];
                    break;
            }
        }

        foreach ($fullFolders as $folder) {
            $folderOrLink = $pathRoot . '/' . $folder;
            if (in_array(substr($folder,-4, 4), ['.php', 'json', 'e.md'])) {
                fopen($folderOrLink, 'w');
            } else {
                mkdir($folderOrLink, 0755, true);
            }
        }


        $payload['fullFolders'] = $fullFolders;
        $payload['pathRoot']    = $pathRoot;

        return $next($payload);
    }
}
