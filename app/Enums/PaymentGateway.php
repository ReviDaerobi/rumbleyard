<?php

namespace App\Enums;

enum PaymentGateway: string
{
    case Mock = 'mock';
    case Midtrans = 'midtrans';
    case Xendit = 'xendit';
}
