<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $titulo }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #0d0d0d;
            color: #f0f0f0;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .email-container {
            width: 90%;
            max-width: 600px;
            background: linear-gradient(135deg, #1c1c1c, #323232);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.7);
        }
        h1 {
            text-align: center;
            color: #00e0ff;
            font-size: 2.5rem;
            text-transform: uppercase;
            letter-spacing: 3px;
            margin-bottom: 20px;
        }
        p {
            font-size: 1.1rem;
            line-height: 1.6;
            margin: 10px 0;
        }
        .highlight {
            color: #00e0ff;
            font-weight: bold;
        }
        .credentials {
            background-color: #141414;
            padding: 15px;
            border-radius: 5px;
            border: 1px solid #00e0ff;
            text-align: center;
            margin-top: 20px;
        }
        .credentials p {
            margin: 5px 0;
            font-size: 1.2rem;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            color: #888;
            font-size: 0.9rem;
        }
        .footer a {
            color: #00e0ff;
            text-decoration: none;
        }
    </style>
</head>
<body>
<div class="email-container">
    <h1>{{ $titulo }}</h1>
    <p>Olá <span class="highlight">{{ $name }}</span>,</p>
    <p>{{ $mensagem }}</p>

    <div class="credentials">
        <p><strong>Email:</strong> <span class="highlight">{{ $email }}</span></p>
        <p><strong>Senha:</strong> <span class="highlight">{{ $password }}</span></p>
    </div>

    <p>Mantenha suas credenciais seguras e use-as para acessar o sistema.</p>
    <p>Atenciosamente,</p>
    <p><span class="highlight">Equipe CodeGus</span></p>

    <div class="footer">
        <p>© 2024 CodeGus | <a href="#">Política de Privacidade</a></p>
    </div>
</div>
</body>
</html>
