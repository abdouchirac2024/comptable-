<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réponse à votre message</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #28a745;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background-color: #ffffff;
            padding: 30px;
            border: 1px solid #e9ecef;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            border-radius: 0 0 8px 8px;
            font-size: 14px;
            color: #6c757d;
        }
        .response-box {
            background-color: #f8f9fa;
            padding: 20px;
            border-left: 4px solid #28a745;
            margin: 20px 0;
            border-radius: 4px;
        }
        .highlight {
            color: #28a745;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2 style="color: #ffffff; margin: 0;">Réponse à votre message</h2>
    </div>
    
    <div class="content">
        <p>Bonjour <span class="highlight">{{ $nom }}</span>,</p>
        
        <p>Nous vous remercions de nous avoir contactés. Voici notre réponse à votre message :</p>
        
        <div class="response-box">
            <p><strong>Notre réponse :</strong></p>
            <p>{{ $reponse }}</p>
        </div>
        
        <p>Si vous avez d'autres questions, n'hésitez pas à nous contacter à nouveau.</p>
        
        <p>Cordialement,<br>
        <strong>L'équipe de support</strong></p>
    </div>
    
    <div class="footer">
        <p>Cet email a été envoyé en réponse à votre demande de contact.</p>
        <p>© {{ date('Y') }} - Tous droits réservés</p>
    </div>
</body>
</html> 