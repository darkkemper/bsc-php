<h1 align="center">BSC-PHP</h1>

<p align="center">
  <a href="https://github.com/darkkemper/bsc-php/releases"><img src="https://poser.pugx.org/darkkemper/bsc-php/v/stable" alt="Stable Version"></a>
  <a href="https://www.php.net"><img src="https://img.shields.io/badge/php-%3E=7.2-brightgreen.svg?maxAge=2592000" alt="Php Version"></a>
  <a href="https://github.com/darkkemper/bsc-php/blob/master/LICENSE"><img src="https://img.shields.io/github/license/darkkemper/bsc-php.svg?maxAge=2592000" alt="bsc-php License"></a>
  <a href="https://packagist.org/packages/darkkemper/bsc-php"><img src="https://poser.pugx.org/darkkemper/bsc-php/downloads" alt="Total Downloads"></a>
</p>

## Introduction

Fork of [BSC-PHP by Fenguoz](https://github.com/Fenguoz/bsc-php)

## Advantage

1. Methods `eth_getTransactionReceipt` and `eth_sendRawTransaction` in BscscanApi now have error messages instead of `null` to debug the response from the network
2. Fixed a bug in BscscanApi with the `send` method when the `params` variable is empty