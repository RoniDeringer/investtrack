<?php

namespace App\Services;

class PortfolioService
{
    public function __construct(private readonly B3MarketDataClient $b3) {}

    /**
     * Temporary, backend-only example portfolio.
     *
     * @return array{
     *   as_of:string|null,
     *   totals: array{patrimony:float,invested:float,profit:float,profit_pct:float|null},
     *   holdings: array<int, array{
     *     ticker:string,name:string|null,quantity:int,
     *     average_price:float|null,current_price:float|null,change_pct:float|null,
     *     position_value:float,profit_value:float,profit_pct:float|null
     *   }>,
     *   allocation: array<int, array{ticker:string,value:float,pct:float}>
     * }
     */
    public function buildExamplePortfolio(): array
    {
        /** @var array<int, array{ticker:string,quantity:int}> $example */
        $example = config('portfolio.example_holdings', []);

        $tickers = array_values(array_map(
            fn(array $row) => (string) ($row['ticker'] ?? ''),
            $example
        ));

        $quotes = $this->b3->quoteMany($tickers);

        $holdings = [];
        $asOf = null;

        foreach ($example as $row) {
            $tickerRaw = (string) ($row['ticker'] ?? '');
            $quantity = (int) ($row['quantity'] ?? 0);

            $ticker = strtoupper(trim($tickerRaw));
            $quote = $quotes[$this->normalizeTicker($ticker)] ?? null;

            $current = is_array($quote) ? ($quote['current_price'] ?? null) : null;
            $average = $current !== null ? round($current * 0.92, 2) : null;

            $positionValue = $current !== null ? round($current * $quantity, 2) : 0.0;
            $invested = $average !== null ? round($average * $quantity, 2) : 0.0;
            $profitValue = round($positionValue - $invested, 2);
            $profitPct = $invested > 0 ? round(($profitValue / $invested) * 100, 4) : null;

            $asOf = $asOf ?? (is_array($quote) ? ($quote['as_of'] ?? null) : null);

            $holdings[] = [
                'ticker' => $this->normalizeTicker($ticker),
                'name' => is_array($quote) ? ($quote['name'] ?? null) : null,
                'logo_url' => $this->logoUrl($this->normalizeTicker($ticker)),
                'quantity' => $quantity,
                'average_price' => $average,
                'current_price' => $current,
                'change_pct' => is_array($quote) ? ($quote['change_pct'] ?? null) : null,
                'position_value' => $positionValue,
                'profit_value' => $profitValue,
                'profit_pct' => $profitPct,
            ];
        }



        usort($holdings, fn(array $a, array $b) => ($b['position_value'] ?? 0) <=> ($a['position_value'] ?? 0));

        $totalPatrimony = array_sum(array_map(fn(array $h) => (float) ($h['position_value'] ?? 0), $holdings));
        $totalInvested = array_sum(array_map(fn(array $h) => (float) (($h['average_price'] ?? 0) * ($h['quantity'] ?? 0)), $holdings));

        $totalPatrimony = round($totalPatrimony, 2);
        $totalInvested = round($totalInvested, 2);

        $profit = round($totalPatrimony - $totalInvested, 2);
        $profitPct = $totalInvested > 0 ? round(($profit / $totalInvested) * 100, 4) : null;

        $allocation = [];
        foreach ($holdings as $holding) {
            $value = (float) ($holding['position_value'] ?? 0);
            $allocation[] = [
                'ticker' => (string) ($holding['ticker'] ?? ''),
                'value' => round($value, 2),
                'pct' => $totalPatrimony > 0 ? round(($value / $totalPatrimony) * 100, 4) : 0.0,
            ];
        }

        return [
            'as_of' => is_string($asOf) ? $asOf : null,
            'totals' => [
                'patrimony' => $totalPatrimony,
                'invested' => $totalInvested,
                'profit' => $profit,
                'profit_pct' => $profitPct,
            ],
            'holdings' => $holdings,
            'allocation' => $allocation,
        ];
    }

    private function normalizeTicker(string $ticker): string
    {
        $ticker = strtoupper(trim($ticker));

        if ($ticker === '') {
            return $ticker;
        }

        if (preg_match('/^[A-Z]{4}$/', $ticker) === 1) {
            return $ticker . '4';
        }

        return $ticker;
    }

    private function logoUrl(string $ticker): string
    {
        $ticker = $this->normalizeTicker($ticker);

        // Public logo CDN (same style most dashboards use; close to what Investidor10 shows).
        return 'https://icons.brapi.dev/icons/' . rawurlencode($ticker) . '.svg';
    }
}
