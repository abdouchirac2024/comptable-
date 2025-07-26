<p>Bonjour {{ $nom }},</p>
<p>Merci de nous avoir contactés. Nous avons bien reçu votre message et nous vous répondrons dans les plus brefs délais.</p>
@if($message)
<p><strong>Votre message :</strong></p>
<p>{{ $message }}</p>
@endif
<p>Cordialement,<br>L'équipe</p> 