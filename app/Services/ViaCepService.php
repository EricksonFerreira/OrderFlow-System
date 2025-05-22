<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ViaCepService
{
    const BASE_URL = 'https://viacep.com.br/ws/';

    /**
     * Consulta um CEP na API do ViaCEP.
     * @param string $cep O CEP a ser consultado (apenas números).
     * @return array|null Dados do endereço ou null se não encontrado/erro.
     * @throws \Exception Em caso de erro na requisição ou CEP inválido.
     */
    public function consultarCep(string $cep): ?array
    {
        // Remove caracteres não numéricos do CEP
        $cep = preg_replace('/[^0-9]/', '', $cep);

        if (strlen($cep) !== 8) {
            throw new \Exception('CEP inválido. Deve conter 8 dígitos numéricos.');
        }

        try {
            // Disable SSL verification since ViaCEP uses self-signed certificates
            $response = Http::withOptions([
                'verify' => false
            ])->get(self::BASE_URL . $cep . '/json/');

            if ($response->failed()) {
                throw new \Exception('Erro ao consultar CEP na API do ViaCEP: ' . $response->status());
            }
            

            $data = $response->json();

            // Verifica se a resposta indica erro (ex: CEP não encontrado)
            if (isset($data['erro']) && $data['erro'] === true) {
                return null;
            }

            // Retorna apenas os campos relevantes
            return [
                'cep' => $data['cep'] ?? null,
                'logradouro' => $data['logradouro'] ?? null,
                'bairro' => $data['bairro'] ?? null,
                'localidade' => $data['localidade'] ?? null,
                'uf' => $data['uf'] ?? null,
                'ibge' => $data['ibge'] ?? null,
                'ddd' => $data['ddd'] ?? null,
            ];
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Erro ao consultar CEP: ' . $e->getMessage());
            return null;
        }
    }
}