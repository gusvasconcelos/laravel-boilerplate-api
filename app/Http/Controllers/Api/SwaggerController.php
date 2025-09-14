<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\InternalServerErrorException;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class SwaggerController extends Controller
{
    public function get(): JsonResponse
    {
        $filePath = base_path('openapi.json');

        if (! file_exists($filePath)) {
            $currentDir = getcwd();

            chdir(base_path());

            exec('composer generate:docs', $output, $resultCode);

            ($resultCode !== 0) && throw new InternalServerErrorException('Falha ao gerar documentação da API');

            chdir($currentDir);
        }

        $doc = file_get_contents($filePath);

        ($doc === false) && throw new InternalServerErrorException('Erro ao ler arquivo de documentação');

        $docToArray = json_decode($doc, true);

        return response()->json($docToArray);
    }
}
