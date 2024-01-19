<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ExpenseResource;
use App\Http\Requests\ExpenseStoreRequest;
use App\Http\Requests\ExpenseUpdateRequest;
use App\Notifications\ExpenseNewNotification;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Auth\Access\AuthorizationException;

class ExpenseController extends Controller
{
    public function index()
    {
        try{
            $this->authorize('viewAny', Expense::class);

            $user = Auth::user();
            $expenses = Expense::where('user_id', $user->id)->paginate(5);
            return ExpenseResource::collection($expenses);
        }catch(\Exception $e){
            return response()->json(['success'   => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function store(ExpenseStoreRequest $request)
    {
        try{
            $this->authorize('create', Expense::class);

            $validatedValues = $request->validated();

            $expense = Expense::create($validatedValues);

            $expense->user->notify(new ExpenseNewNotification($expense));

            return new ExpenseResource($expense);
        } catch (ValidationException $e) {
            return response()->json(['success'   => false, 'message' => $e->getMessage()], 422);
        } catch (\Exception $e) {
            return response()->json(['success'   => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function show(Request $request, $id)
    {
        try{
            
            $expense = Expense::firstOrFail($id);

            $this->authorize('view', $expense);

            return new ExpenseResource($expense);
        } catch (ModelNotFoundException $e) {
            return response()->json(['success'   => false, 'message' => 'Despesa não encontrada.'], 404);
        } catch (\Exception $e) {
            return response()->json(['success'   => false, 'message' => 'Erro interno do servidor'], 500);
        }
    }

    public function update(ExpenseUpdateRequest $request, $id)
    {
        try {
            $expense = Expense::where('id', $id)->firstOrFail();
            $this->authorize('update', $expense);
            
            $validatedValues = $request->validated();

            $expense->update($validatedValues);
            return new ExpenseResource($expense);
        } catch (AuthorizationException $e) {
            return response()->json(['success'   => false, 'message' => $e->getMessage()], 404);
        } catch (ModelNotFoundException $e) {
            return response()->json(['success'   => false, 'message' => 'Despesa não encontrada.'], 404);
        } catch (ValidationException $e) {
            return response()->json(['success'   => false, 'message' => $e->getMessage()], 422);
        } catch (\Exception $e) {
            return response()->json(['success'   => false, 'message' => 'Erro interno do servidor'], 500);
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            $expense = Expense::findOrFail($id);

            // Autoriza a exclusão da despesa
            $this->authorize('delete', $expense);

            $expense->delete();
            return new ExpenseResource($expense);
        } catch (ModelNotFoundException $e) {
            return response()->json(['success'   => false, 'message' => 'Despesa não encontrada'], 404);
        } catch (\Exception $e) {
            return response()->json(['success'   => false, 'message' => 'Erro interno do servidor'], 500);
        }
    }
}
