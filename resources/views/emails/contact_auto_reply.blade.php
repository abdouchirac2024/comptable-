<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation de contact</title>
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
            background-color: #f8f9fa;
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
        .message-box {
            background-color: #f8f9fa;
            padding: 15px;
            border-left: 4px solid #007bff;
            margin: 20px 0;
        }
        .highlight {
            color: #007bff;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2 style="color: #007bff; margin: 0;">Confirmation de contact</h2>
    </div>
    
    <div class="content">
        <p>Bonjour <span class="highlight">{{ $nom }}</span>,</p>
        
        <p>Nous vous remercions de nous avoir contactés. Nous avons bien reçu votre message et nous vous répondrons dans les plus brefs délais.</p>
        
        @if($message)
        <div class="message-box">
            <p><strong>Votre message :</strong></p>
            <p>{{ $message }}</p>
        </div>
        @endif
        
        <p>Notre équipe va examiner votre demande et vous contactera rapidement.</p>
        
        <p>En attendant, n'hésitez pas à nous contacter si vous avez des questions urgentes.</p>
        
        <p>Cordialement,<br>
        <strong>L'équipe de support</strong></p>
    </div>
    
    <div class="footer">
        <p>Cet email a été envoyé automatiquement. Merci de ne pas y répondre directement.</p>
        <p>© {{ date('Y') }} - Tous droits réservés</p>
    </div>
</body>
</html> 