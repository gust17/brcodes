<?php

namespace App\Services;

use App\Models\CodigoPromocional;
use App\Models\Resgate;
use Illuminate\Support\Facades\Auth;

class CodigoPromocionalService
{
    /**
     * Cria um novo código promocional.
     *
     * @param array $data
     * @return CodigoPromocional
     */
    public function createCodigo(array $data)
    {
        // Adiciona o ID do usuário autenticado que criou o código
        $data['user_id'] = Auth::id();



        // Verifica se o valor descrescente é ativado, define valor inicial
        if (isset($data['decrescimo']) && $data['decrescimo']) {
            if (!isset($data['valor_decrescimo']) || $data['valor_decrescimo'] < 1) {
                throw new \Exception('Valor de decrescimo inválido.');
            }
        }
        if (isset($data['limite_resgate']) && $data['limite_resgate']) {
            if (!isset($data['valor_decrescimo']) || $data['valor_decrescimo'] < 1) {
                throw new \Exception('Valor de decrescimo inválido.');
            }
        }


        return CodigoPromocional::create($data);
    }

    /**
     * Atualiza um código promocional existente.
     *
     * @param CodigoPromocional $codigoPromocional
     * @param array $data
     * @return CodigoPromocional
     */
    public function updateCodigo(CodigoPromocional $codigoPromocional, array $data)
    {
        // Atualiza as informações do código
        if (isset($data['decrescimo']) && $data['decrescimo'] && $data['pontuacao'] <= 0) {
            throw new \Exception('A pontuação deve ser maior que zero para resgate com decrescimo.');
        }

        $codigoPromocional->update($data);

        return $codigoPromocional;
    }

    /**
     * Exclui um código promocional (Soft Delete).
     *
     * @param CodigoPromocional $codigoPromocional
     * @return void
     */
    public function deleteCodigo(CodigoPromocional $codigoPromocional)
    {
        $codigoPromocional->delete();
    }

    /**
     * Verifica se o código pode ser resgatado (baseado no decrescimo e pontuação).
     *
     * @param CodigoPromocional $codigoPromocional
     * @return bool
     */
    public function isCodigoResgatavel(CodigoPromocional $codigoPromocional)
    {
        if ($codigoPromocional->decrescimo && $codigoPromocional->pontuacao <= 0) {
            return false;
        }

        return true;
    }

    public function generateMultipleCodigos(int $quantidade, array $data)
    {
        $codigosGerados = [];

        for ($i = 0; $i < $quantidade; $i++) {
            // Gera o código aleatório
            $codigo = $this->generateRandomCode();

            // Adiciona o ID do usuário autenticado e o código gerado
            $codigoData = array_merge($data, [
                'codigo' => $codigo,
                'user_id' => Auth::id(),
            ]);

            // Cria o código no banco
            $novoCodigo = CodigoPromocional::create($codigoData);
            $codigosGerados[] = $novoCodigo;
        }

        return $codigosGerados;
    }



    private function generateRandomCode($length = 6)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }

    // public function resgatarCodigo(CodigoPromocional $codigoPromocional)
    // {
    //     $user = Auth::user();

    //     // Verifica se o usuário já resgatou este código
    //     $resgateExistente = Resgate::where('user_id', $user->id)
    //                                 ->where('codigo_promocional_id', $codigoPromocional->id)
    //                                 ->first();

    //     //return($resgateExistente);


    //     if ($resgateExistente) {
    //         throw new \Exception('Você já resgatou este código anteriormente.');
    //     }
    //     //return $codigoPromocional;
    //     // Verifica se o código ainda pode ser resgatado (limite de resgate)
    //     if ($codigoPromocional->limite_resgate <= 0 && !$codigoPromocional->resgate_unico ) {
    //         throw new \Exception('Este código já atingiu o limite de resgates.');
    //     }

    //     // Verifica se a pontuação ainda permite o resgate no caso de decrescimo
    //     if ($codigoPromocional->decrescimo && $codigoPromocional->pontuacao <= 0) {
    //         throw new \Exception('A pontuação deste código já esgotou.');
    //     }

    //     // Calcula a pontuação obtida no resgate
    //     $pontuacaoObtida = $codigoPromocional->pontuacao;

    //     // Atualiza a pontuação do código se houver decrescimo
    //     if ($codigoPromocional->decrescimo) {
    //         $codigoPromocional->pontuacao -= $codigoPromocional->valor_decrescimo;
    //         if ($codigoPromocional->pontuacao < 0) {
    //             $codigoPromocional->pontuacao = 0;
    //         }
    //         $codigoPromocional->save();
    //     }

    //     // Diminui o limite de resgates se aplicável
    //     if (!$codigoPromocional->resgate_unico) {
    //         $codigoPromocional->limite_resgate--;
    //         $codigoPromocional->save();
    //     }

    //     // Cria o registro de resgate
    //     $resgate = Resgate::create([
    //         'user_id' => $user->id,
    //         'codigo_promocional_id' => $codigoPromocional->id,
    //         'pontuacao' => $pontuacaoObtida,
    //     ]);

    //     $user->increment('pontuacao', $pontuacaoObtida);

    //     return $resgate;
    // }

    //     public function resgatarCodigo(CodigoPromocional $codigoPromocional)
    // {
    //     $user = Auth::user();

    //     // Verifica se o usuário já resgatou este código anteriormente
    //     $resgateExistente = Resgate::where('user_id', $user->id)
    //                                 ->where('codigo_promocional_id', $codigoPromocional->id)
    //                                 ->first();

    //     if ($resgateExistente) {
    //         throw new \Exception('Você já resgatou este código anteriormente.');
    //     }

    //     // Verifica se o código ainda pode ser resgatado (limite de resgate)
    //     if ($codigoPromocional->limite_resgate <= 0 && !$codigoPromocional->resgate_unico) {
    //         throw new \Exception('Este código já atingiu o limite de resgates.');
    //     }

    //     // Verifica se a pontuação ainda permite o resgate no caso de decrescimo
    //     if ($codigoPromocional->decrescimo && $codigoPromocional->pontuacao <= 0) {
    //         throw new \Exception('A pontuação deste código já esgotou.');
    //     }

    //     // Calcula a pontuação obtida no resgate
    //     $pontuacaoObtida = $codigoPromocional->pontuacao;

    //     // Atualiza a pontuação do código se houver decrescimo
    //     if ($codigoPromocional->decrescimo) {
    //         $codigoPromocional->pontuacao -= $codigoPromocional->valor_decrescimo;

    //         // Certifica que a pontuação não fique negativa
    //         if ($codigoPromocional->pontuacao < 0) {
    //             $codigoPromocional->pontuacao = 0;
    //         }
    //         $codigoPromocional->save();
    //     }

    //     // Diminui o limite de resgates se aplicável (quando não for resgate único)
    //     if (!$codigoPromocional->resgate_unico) {
    //         $codigoPromocional->limite_resgate--;
    //         $codigoPromocional->save();
    //     }

    //     // Cria o registro de resgate
    //     $resgate = Resgate::create([
    //         'user_id' => $user->id,
    //         'codigo_promocional_id' => $codigoPromocional->id,
    //         'pontuacao' => $pontuacaoObtida,
    //     ]);

    //     // Incrementa a pontuação do usuário
    //     $user->increment('pontuacao', $pontuacaoObtida);

    //     return $resgate;
    // }

    public function resgatarCodigo(CodigoPromocional $codigoPromocional)
    {
        $user = Auth::user();

        // Verifica se o usuário já resgatou este código
        $resgateExistente = Resgate::where('user_id', $user->id)
            ->where('codigo_promocional_id', $codigoPromocional->id)
            ->first();

        if ($resgateExistente) {
            throw new \Exception('Você já resgatou este código anteriormente.');
        }

        // Verifica se o código ainda pode ser resgatado (limite de resgate), exceto se tiver decrescimo
        if (!$codigoPromocional->decrescimo && !$codigoPromocional->resgate_unico && $codigoPromocional->limite_resgate <= 0) {
            throw new \Exception('Este código já atingiu o limite de resgates.');
        }

        // Verifica se a pontuação ainda permite o resgate no caso de decrescimo
        if ($codigoPromocional->decrescimo && $codigoPromocional->pontuacao <= 0) {
            throw new \Exception('A pontuação deste código já esgotou.');
        }

        // Calcula a pontuação obtida no resgate
        $pontuacaoObtida = $codigoPromocional->pontuacao;

        // Atualiza a pontuação do código se houver decrescimo
        if ($codigoPromocional->decrescimo) {
            $codigoPromocional->pontuacao -= $codigoPromocional->valor_decrescimo;

            // Certifica que a pontuação não fique negativa
            if ($codigoPromocional->pontuacao < 0) {
                $codigoPromocional->pontuacao = 0;
            }
            $codigoPromocional->save();
        }

        // Diminui o limite de resgates se aplicável (quando não for resgate único e não houver decrescimo)
        if (!$codigoPromocional->resgate_unico && !$codigoPromocional->decrescimo) {
            $codigoPromocional->limite_resgate--;
            $codigoPromocional->save();
        }

        // Cria o registro de resgate
        $resgate = Resgate::create([
            'user_id' => $user->id,
            'codigo_promocional_id' => $codigoPromocional->id,
            'pontuacao' => $pontuacaoObtida,
        ]);

        // Incrementa a pontuação do usuário
        $user->increment('pontuacao', $pontuacaoObtida);

        return $resgate;
    }



    public function createManualCodigo($data)
    {
        $codigoPromocional = new CodigoPromocional();

        $codigoPromocional->codigo = $data['codigo'];
        $codigoPromocional->user_id = Auth::id();
        $codigoPromocional->pontuacao = $data['pontuacao'];
        $codigoPromocional->resgate_unico = $data['resgate_unico'];

        // Se não for resgate único, processa limite de resgate e decrescimo
        if (!$data['resgate_unico']) {
            $codigoPromocional->limite_resgate = $data['limite_resgate'] ?? null;
            $codigoPromocional->decrescimo = $data['decrescimo'] ?? false;
            $codigoPromocional->valor_decrescimo = $data['valor_decrescimo'] ?? null;
        }

        $codigoPromocional->save();

        return $codigoPromocional;
    }
}
