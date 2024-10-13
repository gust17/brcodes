<?php


namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class CompetidorService
{
    /**
     * Cria um novo competidor e envia as credenciais por e-mail.
     *
     * @param array $data
     * @return array
     */
    public function criarCompetidor(array $data): array
    {
        $password = $this->gerarSenhaAleatoria();

        $user = $this->criarUsuario($data, $password);

        $this->enviarCredenciaisPorEmail($user, $password);

        return $this->prepararDadosDashboard($user);
    }

    /**
     * Gera uma senha aleatória de 8 caracteres.
     *
     * @return string
     */
    private function gerarSenhaAleatoria(): string
    {
        return Str::random(8);
    }

    /**
     * Cria um novo usuário competidor no sistema.
     *
     * @param array $data
     * @param string $password
     * @return User
     */
    private function criarUsuario(array $data, string $password): User
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($password),
            'role' => 'competidor',
        ]);
    }

    /**
     * Envia as credenciais do usuário por e-mail.
     *
     * @param User $user
     * @param string $password
     * @return void
     */
    private function enviarCredenciaisPorEmail(User $user, string $password): void
    {
        $emailData = [
            'name' => $user->name,
            'email' => $user->email,
            'password' => $password,
            'titulo' => 'Suas credenciais de acesso',  // Notou que é 'titulo' aqui?
            'mensagem' => 'Aqui estão suas credenciais para acessar o sistema.',
        ];

        Mail::send('emails.credenciais', $emailData, function ($message) use ($user) {
            $message->to($user->email)
                ->subject('Credenciais');
        });
    }

    /**
     * Prepara os dados para exibição no dashboard.
     *
     * @param User $user
     * @return array
     */
    private function prepararDadosDashboard(User $user): array
    {
        return [
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
        ];
    }
}
