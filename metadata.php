<?php

$sMetadataVersion = '2.1';
$aModule = [
    'id'          => 'devutils-mails',
    'title'       => '[devutils] email preview',
    'description' => 'easy design and debugging for oxid eshop email templates',
    'version'     => '1.0.0',
    'author'      => 'OXID Community',
    'email'       => '',
    'url'         => 'https://github.com/OXIDprojects/oxid-devutils-mail',
    'extend'      => [
        \OxidEsales\Eshop\Core\Email::class              => OxidCommunity\DevutilsMails\Core\Email::class,
        \OxidEsales\Eshop\Application\Model\Order::class => OxidCommunity\DevutilsMails\Model\Order::class
    ],
    'controllers' => [
        'dev_mails' => OxidCommunity\DevutilsMails\Controller\Mails::class
    ],
    'templates'   => [
        'dev_mails.tpl' => 'oxid-community/devutils-mails/views/dev_mails.tpl'
    ],
];
