<?php declare(strict_types=1);

namespace App\Core;

use DateTimeImmutable;

final class Validator
{
    /**
     * @param array<string, mixed> $data
     * @param array<string, string|array<int, string>> $rules
     * @return array{valid: bool, errors: array<string, array<int, string>>}
     */
    public function validate(array $data, array $rules): array
    {
        $errors = [];

        foreach ($rules as $field => $fieldRules) {
            $value = $data[$field] ?? null;
            $ruleSet = is_array($fieldRules) ? $fieldRules : explode('|', (string)$fieldRules);

            foreach ($ruleSet as $ruleExpression) {
                [$rule, $parameter] = $this->parseRule((string)$ruleExpression);

                $error = match ($rule) {
                    'required' => $this->validateRequired($value),
                    'email' => $this->validateEmail($value),
                    'phone' => $this->validateKenyanPhone($value),
                    'min' => $this->validateMin($value, (int)$parameter),
                    'max' => $this->validateMax($value, (int)$parameter),
                    'numeric' => $this->validateNumeric($value),
                    'date' => $this->validateDate($value),
                    default => null,
                };

                if ($error !== null) {
                    $errors[$field][] = $error;
                }
            }
        }

        return [
            'valid' => $errors === [],
            'errors' => $errors,
        ];
    }

    private function parseRule(string $ruleExpression): array
    {
        if (!str_contains($ruleExpression, ':')) {
            return [$ruleExpression, null];
        }

        [$rule, $parameter] = explode(':', $ruleExpression, 2);
        return [trim($rule), trim($parameter)];
    }

    private function validateRequired(mixed $value): ?string
    {
        if ($value === null) {
            return 'This field is required.';
        }

        if (is_string($value) && trim($value) === '') {
            return 'This field is required.';
        }

        return null;
    }

    private function validateEmail(mixed $value): ?string
    {
        if ($this->isEmpty($value)) {
            return null;
        }

        return filter_var((string)$value, FILTER_VALIDATE_EMAIL) === false
            ? 'Enter a valid email address.'
            : null;
    }

    private function validateKenyanPhone(mixed $value): ?string
    {
        if ($this->isEmpty($value)) {
            return null;
        }

        $clean = preg_replace('/\D+/', '', (string)$value);

        $valid = preg_match('/^(?:254|0)(?:7\d{8}|1\d{8})$/', $clean) === 1;

        return $valid ? null : 'Enter a valid Kenyan phone number.';
    }

    private function validateMin(mixed $value, int $min): ?string
    {
        if ($this->isEmpty($value)) {
            return null;
        }

        return mb_strlen((string)$value) < $min
            ? "Must be at least {$min} characters."
            : null;
    }

    private function validateMax(mixed $value, int $max): ?string
    {
        if ($this->isEmpty($value)) {
            return null;
        }

        return mb_strlen((string)$value) > $max
            ? "Must be {$max} characters or fewer."
            : null;
    }

    private function validateNumeric(mixed $value): ?string
    {
        if ($this->isEmpty($value)) {
            return null;
        }

        return is_numeric($value) ? null : 'Must be a numeric value.';
    }

    private function validateDate(mixed $value): ?string
    {
        if ($this->isEmpty($value)) {
            return null;
        }

        try {
            $date = new DateTimeImmutable((string)$value);
            return $date === false ? 'Enter a valid date.' : null;
        } catch (\Throwable) {
            return 'Enter a valid date.';
        }
    }

    private function isEmpty(mixed $value): bool
    {
        return $value === null || (is_string($value) && trim($value) === '');
    }
}