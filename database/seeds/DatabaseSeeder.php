<?php

use App\User;
use App\Account;
use App\Invoice;
use App\Customer;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $account = Account::create(['name' => 'Waste Management']);

        factory(User::class)->create([
            'account_id' => $account->id,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'johndoe@paystack.com',
            'owner' => true,
        ]);

        factory(User::class, 5)->create(['account_id' => $account->id]);

        // $customers = factory(Customer::class, 100)
        //     ->create(['account_id' => $account->id]);

        // factory(Invoice::class, 100)
        //     ->create(['account_id' => $account->id])
        //     ->each(function ($invoice) use ($customers) {
        //         $invoice->update(['customer_id' => $customers->random()->id]);
        //     });
    }
}
