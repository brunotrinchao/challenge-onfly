<!-- resources/views/emails/expense-created.blade.php -->

@extends('layouts.mail')

@section('content')
    <h1>Nova Despesa Criada</h1>

    <p>Uma nova despesa foi criada:</p>

    <ul>
        <li><strong>Descrição:</strong> {{ $expense->description }}</li>
        <li><strong>Data:</strong> {{ date('d/m/y', strtotime($expense->date)) }}</li>
        <li><strong>Valor:</strong> R$ {{ number_format($expense->amount, 2, ',', '.') }}</li>
    </ul>

@endsection
