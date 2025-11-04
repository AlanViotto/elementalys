<?php

namespace Elementalys\Controllers;

abstract class BaseController
{
    protected function sanitizeString(?string $value): string
    {
        $raw = $value ?? '';

        $decoded = htmlspecialchars_decode($raw, ENT_QUOTES | ENT_SUBSTITUTE);
        $stripped = strip_tags($decoded);

        return trim($stripped);
    }

    protected function sanitizeLongText(?string $value): string
    {
        $raw = $value ?? '';

        return trim((string) filter_var(
            $raw,
            FILTER_UNSAFE_RAW,
            FILTER_FLAG_STRIP_LOW
        ));
    }

    protected function sanitizeEmail(?string $value): string
    {
        return trim(filter_var($value ?? '', FILTER_SANITIZE_EMAIL));
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

    protected function sanitizeNullableInt(?string $value): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }

        return $this->sanitizeInt($value);
    }

    protected function sanitizeUrl(?string $value): string
    {
        $url = trim($value ?? '');

        if ($url === '') {
            return '';
        }

        $sanitized = filter_var($url, FILTER_SANITIZE_URL);

        if ($sanitized === false || ! filter_var($sanitized, FILTER_VALIDATE_URL)) {
            return '';
        }

        return $sanitized;
    }
}
