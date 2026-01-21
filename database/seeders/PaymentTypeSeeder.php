<?php

namespace Database\Seeders;

use App\Models\PaymentType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $paymentTypes = [
            ['nama' => 'Transfer Bank'],
            ['nama' => 'E-Wallet (GoPay, OVO, Dana)'],
            ['nama' => 'Kartu Kredit/Debit'],
            ['nama' => 'Cash'],
            ['nama' => 'QRIS'],
        ];

        foreach ($paymentTypes as $type) {
            PaymentType::create($type);
        }
    }
}
