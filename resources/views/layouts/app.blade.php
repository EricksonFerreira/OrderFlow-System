<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OrderFlow System - @yield('title', 'Sistema de Pedidos')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { padding-top: 56px; } /* Ajuste para a navbar fixa */
        .footer {
            width: 100%;
            background-color: #f8f9fa;
            padding: 20px 0;
            text-align: center;
            position: relative;
            bottom: 0;
            left: 0;
        }
        .container-main {
            min-height: calc(100vh - 120px); /* Altura mínima para o conteúdo, considerando header e footer */
            padding-bottom: 40px; /* Espaçamento para o footer */
        }
    </style>
    @yield('head_extra') {{-- Para CSS ou meta tags específicas de uma página --}}
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">OrderFlow</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('produtos.index') }}">Produtos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('carrinho.ver') }}">
                            Carrinho
                            @if(session('carrinho'))
                                <span class="badge bg-primary rounded-pill">{{ count(session('carrinho')) }}</span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('cupons.index') }}">Cupons (Admin)</a>
                    </li>
                    {{-- Adicione links de login/registro se estiver usando Auth --}}
                </ul>
            </div>
        </div>
    </nav>

    <div class="container container-main mt-4">
        {{-- Exibe mensagens de sucesso/erro --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @yield('content') {{-- Aqui o conteúdo específico de cada página será injetado --}}
    </div>

    <footer class="footer mt-auto py-3 bg-light">
        <div class="container">
            <span class="text-muted">&copy; {{ date('Y') }} OrderFlow System. Todos os direitos reservados.</span>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts_extra') {{-- Para scripts específicos de uma página --}}
</body>
</html>