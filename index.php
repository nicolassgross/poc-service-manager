<?php
include_once "vendor/autoload.php";

use Laminas\ServiceManager\Factory\InvokableFactory;
use Laminas\ServiceManager\ServiceManager;
use Psr\Container\ContainerInterface;

class CarroService
{
    public $modelo;
    public $cor;
    public $valor;

    public function __construct(
        $modelo = 'celta turbo',
        $cor = 'azul',
        $valor = 1500
    )
    {
        $this->modelo = $modelo;
        $this->cor = $cor;
        $this->valor = $valor;
    }
}

class CarroFactory
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $modelo = $options['modelo'] ?? null;
        $cor = $options['cor'] ?? null;
        $valor = $options['valor'] ?? null;

        return new $requestedName(
            $modelo,
            $cor,
            $valor
        );
    }
}

//Instância do service manager utilizando InvokableFactory
$serviceManagerInvokable = new ServiceManager([
    'factories' => [
        CarroService::class => InvokableFactory::class
    ]
]);

//instancia o service manager tento Factory própria e uso do parâmetro shared_by_default.
$serviceManager = new ServiceManager([
    'factories' => [
        CarroService::class => CarroFactory::class,
    ],
    //se alterar o valor desse parâmetro o resultado da comparação abaixo irá mudar
    'shared_by_default' => false,
]);

//COMPARA DUAS DOIS OBJETOS E VERIFICA SE SÃO A MESMA INSTÂNCIA
$t1 = $serviceManager->get(CarroService::class);
$t2 = $serviceManager->get(CarroService::class);

if ($t1 === $t2) { 
    echo "Os serviços são a mesma instância." . PHP_EOL; 
} else { 
    echo "Os serviços são instâncias diferentes." . PHP_EOL; 
}

//Método get para instanciar o serviço pelo service manager com InvokableFactory
$objCarroServiceInvokable = $serviceManagerInvokable->get(CarroService::class);

//Método get para instanciar o serviço pelo service manager
$objCarroService1 = $serviceManager->get(CarroService::class);

//Método build para instanciar o serviços passando parâmetros, pelo service manager
$objCarroService2 = $serviceManager->build(
    CarroService::class, 
    [
        'modelo' => 'ferrari turbo',
        'cor' => 'vermelho',
        'valor' => 1000000
    ]
);

print_r($objCarroServiceInvokable);
print_r($objCarroService1);
print_r($objCarroService2);