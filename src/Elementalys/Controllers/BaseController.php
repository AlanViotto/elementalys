<?php

namespace Elementalys\Controllers;

abstract class BaseController
{
    protected function sanitizeString(?string $value): string
    {
        return trim(filter_var($value ?? '', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    }

    protected function sanitizeFloat(?string $value): float
    {
        $normalized = str_replace(',', '.', $value ?? '0');
        return (float) filter_var($normalized, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    }

    protected function sanitizeInt(?string $value): int
    {
        return (int) filter_var($value ?? '0', FILTER_SANITIZE_NUMBER_INT);
    }
}
