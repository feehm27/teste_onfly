<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status do Pedido Atualizado</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #007bff;
            font-size: 24px;
        }

        p {
            font-size: 16px;
            line-height: 1.5;
        }

        .status {
            font-weight: bold;
            color: #28a745;
        }

        .footer {
            font-size: 14px;
            text-align: center;
            margin-top: 20px;
            color: #777;
        }

        .footer a {
            color: #007bff;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Olá!</h1>

    <p>Estamos te avisando que o status da sua solicitação de viagem <strong>#{{ $orderTravel->id }}</strong> foi atualizado.</p>

    <p><strong>O novo status é:</strong> <span class="status">{{ $status }}</span></p>

    <p>Para mais detalhes, acesse sua conta em nosso sistema.</p>

    <p>Obrigado por escolher nossa plataforma!</p>

    <div class="footer">
        <p>Atenciosamente,</p>
        <p><strong>Equipe Onfly</strong></p>
    </div>
</div>

</body>
</html>
