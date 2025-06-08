@extends('layouts.menu')

@section('content')
<div class="container">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#userModal" onclick="openModal(null)">Usuario nuevo</button>
    <div class="row mb-3">
        <div class="col">
            <form method="GET" action="{{ route('usuarios.index') }}" id="rolForm">
                <select class="form-select" id="rol" name="rol" onchange="document.getElementById('rolForm').submit()">
                    <option value="">Todos los roles</option>
                    <option value="administrador" {{ request('rol') == 'administrador' ? 'selected' : '' }}>Administrador</option>
                    <option value="docente" {{ request('rol') == 'docente' ? 'selected' : '' }}>Docente</option>
                    <option value="estudiante" {{ request('rol') == 'estudiante' ? 'selected' : '' }}>Estudiante</option>
                </select>
            </form>
        </div>
        <div class="col">
            <form method="GET" action="{{ route('usuarios.index') }}" id="searchForm">
                <input type="hidden" name="rol" value="{{ request('rol') }}">
                <div class="input-group">
                    <input type="text" class="form-control" name="nombre" placeholder="Buscar por nombre" value="{{ request('nombre') }}">
                    <button class="btn btn-outline-secondary" type="submit">
                        <i class="fa fa-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

        <table class="table table-hover text-center">
            <thead>
                <tr>
                    <th scope="col">Nombre Completo</th>
                    <th scope="col">Rol</th>
                    <th scope="col">Correo electrónico</th>
                    <th scope="col">Contraseña</th>
                    <th scope="col">Editar</th>
                    <th scope="col">Eliminar</th>
                </tr>
            </thead>
            <tbody>
                @forelse($usuarios as $usuario)
                    <tr>
                        <td>{{ $usuario->nombre }}</td>
                        <td>{{ $usuario->rol }}</td>
                        <td>{{ $usuario->email }}</td>
                        <td>******</td>
                        <td>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#userModal" onclick="openModal({{ $usuario->id_usuario }})">
                                <i class="fa fa-pencil-alt fs-5" style="color: #007bff;"></i>
                            </a>
                        </td>
                        <td>
                            <button type="button" class="btn btn-link p-0 m-0" data-bs-toggle="modal" data-bs-target="#deleteModal" onclick="confirmDelete('{{ route('usuarios.destroy', $usuario->id_usuario) }}')">
                                <i class="bx bx-trash-alt fs-4" style="color: #FE0000;"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">No se encontraron usuarios para los filtros aplicados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="d-flex justify-content-center">
            {{ $usuarios->links() }}
        </div>
    </div>

<!-- Modal para agregar o editar usuario -->
    <div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userModalLabel">Nuevo Usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="userForm" method="POST">
                        @csrf
                        <input type="hidden" id="method" name="_method" value="POST">
                        
                        <div class="mb-3">
                            <label for="user_nombre" class="form-label">Nombre <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="user_nombre" name="user_nombre" required>
                        </div>

                        <div class="mb-3">
                            <label for="user_apellido" class="form-label">Apellido <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="user_apellido" name="user_apellido" required>
                        </div>

                        <div class="mb-3">
                            <label for="user_tipo" class="form-label">Tipo de Usuario <span class="text-danger">*</span></label>
                            <select class="form-select" id="user_tipo" name="user_tipo" required>
                                <option value="">Seleccione un tipo</option>
                                <option value="0">Administrador</option>
                                <option value="1">Docente</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="user_gmail" class="form-label">Correo electrónico <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="user_gmail" name="user_gmail" required>
                        </div>

                        <div class="mb-3">
                            <label for="user_password" class="form-label">Contraseña <span class="text-danger" id="password-required">*</span></label>
                            <input type="password" class="form-control" id="user_password" name="user_password">
                            <div class="form-text">Mínimo 8 caracteres</div>
                        </div>

                        <div class="mb-3">
                            <label for="user_password_confirmation" class="form-label">Confirmar contraseña <span class="text-danger" id="confirm-password-required">*</span></label>
                            <input type="password" class="form-control" id="user_password_confirmation" name="user_password_confirmation">
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Guardar Usuario</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<!-- Modal de confirmación de eliminación -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirmar eliminación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ¿Está seguro de que desea eliminar este usuario? Esta acción no se puede deshacer.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <form id="deleteForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Eliminar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openModal(usuarioId) {
            const form = document.getElementById('userForm');
            const methodInput = document.getElementById('method');
            const modalTitle = document.getElementById('userModalLabel');
            const passwordRequired = document.getElementById('password-required');
            const confirmPasswordRequired = document.getElementById('confirm-password-required');
            const passwordInput = document.getElementById('user_password');
            const confirmPasswordInput = document.getElementById('user_password_confirmation');
            
            if (usuarioId) {
                fetch(`/usuarios/${usuarioId}/edit`)
                    .then(response => response.json())
                    .then(usuario => {
                        form.action = `/usuarios/${usuarioId}`;
                        methodInput.value = 'PUT';
                        modalTitle.innerText = 'Editar Usuario';
                        
                        document.getElementById('user_nombre').value = usuario.user_nombre;
                        document.getElementById('user_apellido').value = usuario.user_apellido;
                        document.getElementById('user_tipo').value = usuario.user_tipo;
                        document.getElementById('user_gmail').value = usuario.user_gmail;
                        
                        passwordInput.required = false;
                        confirmPasswordInput.required = false;
                        passwordRequired.style.display = 'none';
                        confirmPasswordRequired.style.display = 'none';
                        passwordInput.placeholder = 'Dejar en blanco para mantener la actual';
                        
                        passwordInput.value = '';
                        confirmPasswordInput.value = '';
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error al cargar los datos del usuario');
                    });
            } else {
                form.action = `/usuarios`;
                methodInput.value = 'POST';
                modalTitle.innerText = 'Nuevo Usuario';
                
                document.getElementById('user_nombre').value = '';
                document.getElementById('user_apellido').value = '';
                document.getElementById('user_tipo').value = '';
                document.getElementById('user_gmail').value = '';
                passwordInput.value = '';
                confirmPasswordInput.value = '';
                
                passwordInput.required = true;
                confirmPasswordInput.required = true;
                passwordRequired.style.display = 'inline';
                confirmPasswordRequired.style.display = 'inline';
                passwordInput.placeholder = '';
            }
        }

        function confirmDelete(actionUrl) {
            const deleteForm = document.getElementById('deleteForm');
            deleteForm.action = actionUrl;
        }

        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    </script>
@endsection