<?php

namespace Laravelir\Paymentable\Contracts;

interface PaymentResultContract
{
    public function successful(): bool;
    public function verified(): bool;
    public function failed(): bool;
    public function transactionId(): string;
    public function referenceId(): ?string;
    public function messages(): array;
    public function status();
}
