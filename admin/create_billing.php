<?php
header('Content-Type: application/json; charset=utf-8');

require '../vendor/autoload.php';

use AbacatePay\Clients\BillingClient;
use AbacatePay\Resources\Billing;
use AbacatePay\Clients\Client as AbacateClient;

$apiKey = 'abc_dev_YdtYUZBSh1p4GEsK1GLZKGe6';

// Função para validar CPF
function validarCPF($cpf) {
    // Remover caracteres não numéricos
    $cpf = preg_replace('/\D/', '', $cpf);
    
    // Verificar se o CPF tem 11 dígitos
    if (strlen($cpf) != 11) return false;

    // Realizar a validação do CPF com base nos cálculos de dígitos verificadores
    $soma = 0;
    $resto = 0;

    // Validação 1
    for ($i = 0; $i < 9; $i++) {
        $soma += $cpf[$i] * (10 - $i);
    }
    $resto = $soma % 11;
    if ($resto < 2) {
        $resto = 0;
    } else {
        $resto = 11 - $resto;
    }
    if ($cpf[9] != $resto) return false;

    // Validação 2
    $soma = 0;
    for ($i = 0; $i < 10; $i++) {
        $soma += $cpf[$i] * (11 - $i);
    }
    $resto = $soma % 11;
    if ($resto < 2) {
        $resto = 0;
    } else {
        $resto = 11 - $resto;
    }
    if ($cpf[10] != $resto) return false;

    return true;
}

// Função para validar CNPJ
function validarCNPJ($cnpj) {
    // Remover caracteres não numéricos
    $cnpj = preg_replace('/\D/', '', $cnpj);

    // Verificar se o CNPJ tem 14 dígitos
    if (strlen($cnpj) != 14) return false;

    // Cálculo dos dígitos verificadores do CNPJ
    $soma = 0;
    $multiplicadores = [5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
    
    for ($i = 0; $i < 12; $i++) {
        $soma += $cnpj[$i] * $multiplicadores[$i];
    }
    $resto = $soma % 11;
    $digito1 = ($resto < 2) ? 0 : 11 - $resto;

    $soma = 0;
    $multiplicadores = [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
    for ($i = 0; $i < 13; $i++) {
        $soma += $cnpj[$i] * $multiplicadores[$i];
    }
    $resto = $soma % 11;
    $digito2 = ($resto < 2) ? 0 : 11 - $resto;

    return ($cnpj[12] == $digito1 && $cnpj[13] == $digito2);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Método inválido.']);
    exit;
}

if (!isset($_POST['amount']) || $_POST['amount'] <= 0) {
    echo json_encode(['success' => false, 'error' => 'Valor inválido.']);
    exit;
}

// Sanitiza as entradas
$amount    = isset($_POST['amount']) ? intval($_POST['amount']) * 100 : 0;
$name      = trim($_POST['name'] ?? 'Doador Anônimo');
$email     = trim($_POST['email'] ?? 'anonimo@email.com');
$cellphone = preg_replace('/\D/', '', $_POST['cellphone'] ?? '');
$tax_id    = preg_replace('/\D/', '', $_POST['tax_id'] ?? '');

// Log temporário para ver o que chega
error_log("POST recebido: cellphone={$cellphone}, tax_id={$tax_id}");

// Validação do CPF/CNPJ
if (!validarCPF($tax_id) && !validarCNPJ($tax_id)) {
    echo json_encode(['success' => false, 'error' => 'Invalid taxId']);
    exit;
}

$returnUrl     = "http://localhost/ProjetoTech-GitHub-11-10/index.php";
$completionUrl = "http://localhost/ProjetoTech-GitHub-11-10/transacao_concluida.php";

try {

    // Autenticação com a API do AbacatePay
    AbacateClient::setToken($apiKey);
    $billingClient = new BillingClient();

    $billingData = new Billing([
        'frequency' => 'ONE_TIME',
        'methods'   => ['PIX'],
        'metadata' => [
            'name'           => $name,
            'return_url'     => $returnUrl,
            'completion_url' => $completionUrl
        ],
        'products' => [
            [
                'external_id' => 'doacao-ong',
                'name'        => 'Doação ProjetoTech',
                'quantity'    => 1,
                'price'       => $amount,
                'description' => 'Doação ao projeto'
            ]
        ],
        'customer' => [
            'metadata' => [
                'name'      => $name,
                'email'     => $email,
                'cellphone' => $cellphone,
                'tax_id'    => $tax_id,
            ]
        ],

        'returnUrl'     => $returnUrl,
        'completionUrl' => $completionUrl
    ]);

    // Chamada da API do AbacatePay
    $billing = $billingClient->create($billingData);

    // Tratamento da resposta da API
    $paymentUrl = null;

    if (isset($billing->data->url)) {
        $paymentUrl = $billing->url;
    } else if (isset($billing->url)) {
        $paymentUrl = $billing->url;
    }

    echo json_encode([
        'success'    => $paymentUrl !== null,
        'paymentUrl' => $paymentUrl,
        'raw'        => $billing // Para debug
    ]);
} catch (Throwable $e) {

    // Log de erro
    error_log("ERRO BILLING: " . $e->getMessage());

    echo json_encode([
        'success' => false,
        'error'   => $e->getMessage()
    ]);
}
