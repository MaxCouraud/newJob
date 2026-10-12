<?php

namespace App\Service;

/**
 * Reads recruiter codes and their associated profile types from env.
 *
 * Format in .env: RECRUITER_CODES='{"CODE1":"dev","CODE2":"chef-projet"}'
 */
class RecruiterCodeService
{
    /** @var array<string, string> */
    private array $codes;

    public function __construct(string $recruiterCodes)
    {
        $this->codes = json_decode($recruiterCodes, true) ?? [];
    }

    public function isValid(string $code): bool
    {
        return isset($this->codes[strtoupper($code)]);
    }

    public function getProfileType(string $code): ?string
    {
        return $this->codes[strtoupper($code)] ?? null;
    }

    /** @return array<string, string> */
    public function all(): array
    {
        return $this->codes;
    }
}
