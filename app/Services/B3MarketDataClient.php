<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class B3MarketDataClient
{
    /**
     * @param  array<int, string>  $tickers
     * @return array<string, array{ticker:string,name:string|null,as_of:string|null,current_price:float|null,previous_close:float|null,change_pct:float|null}>
     */
    public function quoteMany(array $tickers): array
    {
        $quotes = [];

        foreach ($tickers as $ticker) {
            $ticker = $this->normalizeTicker($ticker);

            $quotes[$ticker] = Cache::remember(
                'b3.quote.' . $ticker,
                now()->addSeconds(60),
                fn() => $this->quoteOne($ticker),
            );
        }

        return $quotes;
    }

    /**
     * @return array{ticker:string,name:string|null,as_of:string|null,current_price:float|null,previous_close:float|null,change_pct:float|null}
     */
    private function quoteOne(string $ticker): array
    {
        $baseUrl = config('services.b3.base_url', 'https://cotacao.b3.com.br/mds/api/v1');

        $payload = null;

        try {
            $response = Http::acceptJson()
                ->timeout(10)
                ->get(rtrim($baseUrl, '/') . '/instrumentQuotation/' . $ticker);

            if ($response->successful()) {
                $payload = $response->json();
            }
        } catch (\Throwable) {
            $payload = null;
        }

        $bizOk = is_array($payload) && ($payload['BizSts']['cd'] ?? null) === 'OK';
        $trade = $bizOk && isset($payload['Trad'][0]) && is_array($payload['Trad'][0]) ? $payload['Trad'][0] : null;

        $security = is_array($trade) ? ($trade['scty'] ?? null) : null;
        $quote = is_array($security) ? ($security['SctyQtn'] ?? null) : null;

        $symbol = is_array($security) ? ($security['symb'] ?? $ticker) : $ticker;
        $asOf = is_array($payload) ? ($payload['Msg']['dtTm'] ?? null) : null;

        $current = is_array($quote) ? $this->toFloat($quote['curPrc'] ?? null) : null;
        $previousClose = is_array($quote) ? $this->toFloat($quote['prvsClsgPric'] ?? null) : null;

        $changePct = is_array($quote) ? $this->toFloat($quote['oscgPct'] ?? ($quote['oscgPctg'] ?? null)) : null;
        if ($changePct === null && $current !== null && $previousClose !== null && $previousClose > 0) {
            $changePct = (($current / $previousClose) - 1) * 100;
        }

        return [
            'ticker' => $this->normalizeTicker((string) $symbol),
            'name' => is_array($security) ? (($security['desc'] ?? null) ?: null) : null,
            'as_of' => is_string($asOf) ? $asOf : null,
            'current_price' => $current,
            'previous_close' => $previousClose,
            'change_pct' => $changePct,
        ];
    }

    private function normalizeTicker(string $ticker): string
    {
        $ticker = strtoupper(trim($ticker));

        if ($ticker === '') {
            return $ticker;
        }

        // Common user input: "CMIG" -> treat as preferred stock "CMIG4"
        if (preg_match('/^[A-Z]{4}$/', $ticker) === 1) {
            return $ticker . '4';
        }

        return $ticker;
    }

    private function toFloat(mixed $value): ?float
    {
        if ($value === null) {
            return null;
        }

        if (is_int($value) || is_float($value)) {
            return (float) $value;
        }

        if (is_string($value)) {
            $normalized = $value;
            if (str_contains($normalized, ',')) {
                $normalized = str_replace('.', '', $normalized);
                $normalized = str_replace(',', '.', $normalized);
            }

            if (is_numeric($normalized)) {
                return (float) $normalized;
            }
        }

        return null;
    }
}
