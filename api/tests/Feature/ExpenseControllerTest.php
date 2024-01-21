<?php

namespace Tests\Feature;

use App\Models\Expense;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use App\Notifications;

class ExpenseControllerTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */

    public function list_expenses_for_authenticated_user()
    {

        // Simular um usuário autenticado
        $user = User::factory()->create();
        Auth::login($user);

        // Criar algumas despesas associadas ao usuário
        Expense::factory(5)->create(['user_id' => $user->id]);

        // Chamar a API para listar despesas
        $response = $this->actingAs($user)->get('/api/expense');

        // Verificar se a resposta está correta
        $response->assertStatus(200);
        // Verificar se há 5 despesas na resposta JSON

        $response->assertJsonCount(5, 'data');

    }

    /** @test */

    public function insert_expenses_for_authenticated_user()
    {

        Notification::fake();

        // Simular um usuário autenticado
        $user = User::factory()->create();
        $this->actingAs($user);

        // Criar uma nova despesa associada ao usuário
        $expenseData = Expense::factory()->make(['user_id' => $user->id])->toArray();
        $response = $this->postJson('/api/expense', $expenseData);

        Notification::assertSentTo($user, Notifications\ExpenseNewNotification::class);

        // Verificar se a resposta está correta
        $response->assertStatus(201);
        // Verificar se há 1 despesa na resposta JSON
        $response->assertJsonCount(7, 'data');

    }

    /** @test */

    public function update_expenses_for_authenticated_user()
    {

        // Simular um usuário autenticado
        $user = User::factory()->create();
        $this->actingAs($user);

        // Criar uma nova despesa associada ao usuário
        $expenseData = Expense::factory()->make(['user_id' => $user->id])->toArray();
        $response = $this->postJson('/api/expense', $expenseData);

        // Verificar se a resposta está correta
        $response->assertStatus(201);
        // Verificar se há 1 despesa na resposta JSON
        $response->assertJsonCount(7, 'data');


        // Obter o ID da despesa recém-inserida
        $expenseId = $response->json('data.id');

        // Criar novos dados para a despesa (dados de atualização)
        $updatedExpenseData = [
            'description' => 'Nova Descrição',
            'amount' => 50.00,
            'date' => now()->format('Y-m-d'),
        ];

        // Enviar uma requisição PUT para a rota de atualização da despesa com autenticação via token
        $updateResponse = $this->putJson("/api/expense/{$expenseId}", $updatedExpenseData);

        // verificar se a resposta está correta
        $updateResponse->assertStatus(200);

        $updateResponse->assertJson([
                'data' => $updatedExpenseData,
            ]);

        // Verificar se a despesa no banco de dados foi realmente atualizada
        $this->assertDatabaseHas('expense', $updatedExpenseData + ['id' => $expenseId]);

    }


    /** @test */

    public function delete_expense_for_authenticated_user()
    {

        // Simular um usuário autenticado
        $user = User::factory()->create();
        $this->actingAs($user);

        // Criar uma nova despesa associada ao usuário
        $expenseData = Expense::factory()->make(['user_id' => $user->id])->toArray();

        $response = $this->postJson('/api/expense', $expenseData);

        // Verificar se a resposta está correta
        $response->assertStatus(201);
        // Verificar se há 1 despesa na resposta JSON
        $response->assertJsonCount(7, 'data');

        // Obter o ID da despesa recém-inserida
        $expenseId = $response->json('data.id');

        $response = $this->deleteJson("/api/expense/{$expenseId}");

        // Verificar se a resposta está correta
        $response->assertStatus(200);

        // Verifica se a despesa foi removida do banco de dados
        $this->assertDatabaseMissing('expense', ['id' => $expenseId]);
    }
}
