<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\CupomController; // Se for usar o gerenciamento de cupons

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Rota inicial (página de listagem de produtos ou home)
Route::get('/', [ProdutoController::class, 'index'])->name('home');

// Rotas de Produtos
Route::resource('produtos', ProdutoController::class);

// Rotas específicas para ações do carrinho de compras
Route::post('/carrinho/adicionar', [ProdutoController::class, 'adicionarAoCarrinho'])->name('carrinho.adicionar');
Route::post('/carrinho/remover', [PedidoController::class, 'removerDoCarrinho'])->name('carrinho.remover');

// Rotas de Pedidos e Carrinho
Route::get('/carrinho', [PedidoController::class, 'verCarrinho'])->name('carrinho.ver');
Route::post('/pedido/finalizar', [PedidoController::class, 'finalizarPedido'])->name('pedido.finalizar');
Route::get('/pedido/confirmacao/{id}', [PedidoController::class, 'confirmacao'])->name('pedido.confirmacao');
Route::post('/pedido/cupom-valido', [PedidoController::class, 'cupomValido'])->name('pedido.cupom-valido');

// Rota para consulta de CEP via AJAX (usada pelo front-end)
Route::post('/cep/consultar', [PedidoController::class, 'consultarCep'])->name('cep.consultar');

// Rotas de Webhook (para sistemas externos notificarem sobre pedidos)
Route::post('/webhook/pedido', [PedidoController::class, 'webhook'])->name('pedido.webhook');

// Rotas de Cupons (opcional, para uma área administrativa de cupons)
Route::resource('cupons', CupomController::class);
