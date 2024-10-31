<?php
namespace Backend\Api;
use Backend\Api\Rotas\Router;
use Backend\Api\Http\HttpHeader;
use Backend\Api\Rotas\AttributeRouter;

require_once '../vendor/autoload.php';

HttpHeader::setDefaultHeaders();

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit();
}


$roteador = new AttributeRouter();

$metodoHttp = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];


$caminhoControladores = __DIR__ . '/../src/Controllers';
$namespaceBase = 'Backend\\Api\\Controllers';

$classesControladoras = obterClassesControladoras($caminhoControladores, $namespaceBase);

foreach ($classesControladoras as $classeControladora) {
    $roteador->passaControlador($classeControladora);
}

$roteador->resolve($metodoHttp, $uri);

/**
 * Função para obter todas as classes de controladores no diretório Controllers
 * 
 * @param string $caminhoControladores Caminho para o diretório de controladores
 * @param string $namespaceBase Namespace base dos controladores
 * @return array Lista de nomes completos das classes de controladores
 */
    function obterClassesControladoras($caminhoControladores, $namespaceBase) {
        $classesControladoras = [];

        $iterador = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($caminhoControladores)
        );

        foreach ($iterador as $arquivo) {
            if ($arquivo->isFile() && $arquivo->getExtension() === 'php') {
                $caminhoRelativo = substr($arquivo->getPathname(), strlen($caminhoControladores));
                $caminhoRelativo = ltrim($caminhoRelativo, DIRECTORY_SEPARATOR);
                $caminhoRelativo = substr($caminhoRelativo, 0, -4);
                $parteNomeClasse = str_replace(DIRECTORY_SEPARATOR, '\\', $caminhoRelativo);
                $nomeClasse = $namespaceBase . '\\' . $parteNomeClasse;

            if (!class_exists($nomeClasse)) {
                require_once $arquivo->getPathname();
            }
            if (class_exists($nomeClasse)) {
                $classesControladoras[] = $nomeClasse;
            }
        }
    }

    return $classesControladoras;
}
