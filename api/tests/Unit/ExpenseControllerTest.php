<?php

namespace Tests\Unit;

use App\Models\Expense;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ExpenseControllerTest extends TestCase
{
    use DatabaseMigrations;


    /** @test */

    public function insert_expenses_user()
    {

        $user = User::factory()->create([
            'name' => 'User teste',
            'email' => 'teste@teste.com',
            'password' => 'teste123'
        ]);


        $expense = Expense::create([
          'user_id' => $user->id,
          'name' => 'Teste name',
          'date' => now()->format('Y-m-d'),
          'description' => 'Teste description ',
          'amount' => 99.99,
        ]);

        $this->assertNotNull($expense->id);
        $this->assertTrue($expense->user()->exists());

    }

    /** @test */

    public function update_expenses_user()
    {

        $user = User::factory()->create([
            'name' => 'User teste',
            'email' => 'teste@teste.com',
            'password' => 'teste123'
        ]);


        $expense = Expense::create([
          'user_id' => $user->id,
          'name' => 'Teste name',
          'date' => now()->format('Y-m-d'),
          'description' => 'Teste description ',
          'amount' => 99.99,
        ]);


        $expense->update([
          'amount' => 29.55
        ]);

        $this->assertNotNull($expense->updated_at);
        $this->assertEquals(29.55, $expense->amount);

    }

    /** @test */

    public function delete_expenses_user()
    {

        $user = User::factory()->create([
            'name' => 'User teste',
            'email' => 'teste@teste.com',
            'password' => 'teste123'
        ]);


        $expense = Expense::create([
          'user_id' => $user->id,
          'name' => 'Teste name',
          'date' => now()->format('Y-m-d'),
          'description' => 'Teste description ',
          'amount' => 99.99,
        ]);

        $expense->delete();

        $this->assertNotNull($expense->id);


    }

}
