<?php

namespace App\Service;

class SlotService
{
    const BET_AMOUNT = 100;

    private array $symbols = ['9', '10', 'J', 'Q', 'K', 'A', 'cat', 'dog', 'monkey', 'bird'];

    private array $exampleBoard = ['J', 'cat', 'bird', 'J', 'J', 'bird', 'J', 'Q', 'J', 'Q', 'monkey', 'Q', 'K', 'bird', 'A'];

    private array $paylines = [
        [0, 3, 6, 9, 12],
        [1, 4, 7, 10, 13],
        [2, 5, 8, 11, 14],
        [0, 4, 8, 10, 12],
        [2, 4, 6, 10, 14],
    ];

    private function generateRandomBoard(): array
    {
        $board = [];
        for ($i = 0; $i < 15; $i++) {
            $board[] = $this->symbols[array_rand($this->symbols)];
        }
        return $board;
    }

    private function processPaylines(array $board): array
    {
        $paylines = [];
        foreach ($this->paylines as $payline) {
            $sequenceCount = $this->processSinglePayline($board, $payline);
            if ($sequenceCount >= 3) {
                $key = join(' ', $payline);
                $paylines[$key] = $sequenceCount;
            }
        }
        return $paylines;
    }

    private function processSinglePayline(array $board, array $payline): int
    {
        $firstSymbol = '';
        $sequenceCount = 1;
        foreach ($payline as $symbolNumber) {
            $symbol = $board[$symbolNumber];
            if ($symbol === $firstSymbol) {
                $sequenceCount++;
            } elseif ($sequenceCount < 3) {
                $firstSymbol = $symbol;
                $sequenceCount = 1;
            } else {
                break;
            }
        }
        return $sequenceCount;
    }

    private function calculateTotalWin(array $paylines): int
    {
        $totalWin = 0;
        foreach ($paylines as $sequenceCount) {
            switch ($sequenceCount) {
                case 3:
                    $totalWin += self::BET_AMOUNT * 0.2;
                    break;
                case 4:
                    $totalWin += self::BET_AMOUNT * 2;
                    break;
                case 5:
                    $totalWin += self::BET_AMOUNT * 10;
                    break;
                deafult:
                    break;
            }
        }
        return $totalWin;
    }

    public function bet(bool $isTestingBoard): string
    {
        if ($isTestingBoard) {
            $board = $this->exampleBoard;
        } else {
            $board = $this->generateRandomBoard();
        }

        $paylines = $this->processPaylines($board);
        $totalWin = $this->calculateTotalWin($paylines);

        $result = [
            'board' => join(' ', $board),
            'paylines' => $paylines,
            'bet_amount' => self::BET_AMOUNT,
            'total_win' => $totalWin,
        ];

        return json_encode($result, JSON_PRETTY_PRINT);
    }
}
