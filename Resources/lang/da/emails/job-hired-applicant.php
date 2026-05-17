<?php

return [
    'subject' => 'Tillykke med hyringen af {{ job.user.first_name }}',

    'html_template' => '
        <p><strong>Kære {{ job.user.first_name }}</strong>,</p>
        <p><br></p>

        <p>Tillykke med, at du er blevet hyret af {{ job.company.name }} til en opgave.</p>
        <p>Vi glæder os over, at I har indgået et samarbejde, og vi håber, det bliver en god oplevelse for jer begge.</p>
        <p><br></p>

        <p><strong>Projekt</strong></p>
        <p>Navn: {{ job.name }}</p>
        <p><br></p>

        <p><strong>Adresse</strong></p>
        <p>{{ job.address.street }}, {{ job.address.post_code }} {{ job.address.city }}</p>
        <p><br></p>

        <p><strong>Periode</strong></p>
        <p>Fra: {{ job.available.from }}</p>
        <p>Til: {{ job.available.to }}</p>
        <p><br></p>

        <p><strong>Faggruppe</strong></p>
        <p>{{ job.categories.0.name }}</p>
        <p><br></p>

        <p>Hvis du har spørgsmål eller brug for hjælp, er du altid velkommen til at kontakte os.</p>
        <p><br></p>

        <p>Held og lykke med opgaven!</p>
        <p><br></p>

        <p>Venlig hilsen,</p>
        <p>{{ job.company.name }}</p>
    ',

    'text_template' => "
        Kære {{ job.user.first_name }},

        Tillykke med, at du er blevet hyret af {{ job.company.name }} til en opgave.
        Vi glæder os over, at I har indgået et samarbejde, og vi håber, det bliver en god oplevelse for jer begge.

        Projekt:
        Navn: {{ job.name }}

        Adresse:
        {{ job.address.street }}, {{ job.address.post_code }} {{ job.address.city }}

        Periode:
        Fra: {{ job.available.from }}
        Til: {{ job.available.to }}

        Faggruppe:
        {{ job.categories.0.name }}

        Hvis du har spørgsmål eller brug for hjælp, er du altid velkommen til at kontakte os.

        Held og lykke med opgaven!

        Venlig hilsen,
        {{ job.company.name }}
    ",
];
