<p align="center">
  <a href="https://github.com/solital/core/releases"><img alt="GitHub release (latest by date)" src="https://img.shields.io/github/v/release/solital/core?style=flat-square"></a>
  <a href="https://github.com/solital/core/blob/master/LICENSE"><img alt="GitHub" src="https://img.shields.io/github/license/solital/core?style=flat-square"></a>
</p>

## Sobre

API customizada para receber pagamentos da PagarMe por cartão de crédito.

## Instalação via Composer

```
composer brenno-duarte/pagarme-creditcard
```

## Inicializando

```php
require_once 'vendor/autoload.php';

use PagarMeAPICreditCard\PagarMeAPICreditCard;

$pagarme = new PagarMeAPICreditCard("API_KEY");
```

## Como usar

### Criando um cartão de crédito

```php
$pagarme->createCard("5528 7219 6502 9146", "YOUR_NAME", "07", "2022" ,"849");

var_dump($pagarme->getResponse());
```

### Criando uma transação

```php
$pagarme->transactions(1000, "CARD_HASH");

var_dump($pagarme->getResponse());
```

## License

[MIT](https://github.com/solital/solital/blob/master/LICENSE)
