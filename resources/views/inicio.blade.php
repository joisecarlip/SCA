@extends('layouts.menu')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h2 class="card-title">Bienvenido, {{ $user->user_nombre }}</h2>
                    <p class="card-text">
                        <strong>Nombre:</strong> {{ $user->user_nombre }}<br>
                        <strong>Apellido:</strong> {{ $user->user_apellido }}<br>
                        <strong>Email:</strong> {{ $user->user_gmail }}<br>
                        <strong>Tipo:</strong> 
                        <span class="badge {{ $user->user_tipo == '0' ? 'bg-success' : 'bg-primary' }}">
                            {{ $user->user_tipo == '0' ? 'Administrador' : 'Docente' }}
                        </span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection