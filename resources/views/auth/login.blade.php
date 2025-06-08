@extends('layouts.app')

@section('content')
<div>
    <div>
        <div>
            <div>
                <H2>SISTEMA DE CONTROL DE ASISTENCIAS</H2>
                <div>
                    Iniciar Sesión
                </div>
                <div>
                    @if (session('error'))
                        <div>
                            {{ session('error') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login.submit') }}">
                        @csrf
                        <div>
                            <label for="user_gmail">Correo Electrónico</label>
                            <input type="email"
                                id="user_gmail"
                                name="user_gmail"
                                required
                                placeholder="ejemplo@correo.com">
                            @error('user_gmail')
                                <span>{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label for="user_password">Contraseña</label>
                            <input type="password"
                                id="user_password"
                                name="user_password"
                                required>
                            @error('user_password')
                                <span>{{ $message }}</span>
                            @enderror
                        </div>

                        <button type="submit">Iniciar Sesión</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection