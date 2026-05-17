<?php
return [
    'subject' => 'Husk din kommende aftale med {{ applicant.company.name }}',

    'html_template' => '
        <p>Kære {{ applicant.contact.first_name }},</p>
        <p><br></p>
        <p>Dette er en venlig påmindelse om, at du har en aftale med {{ applicant.company.name }} den <strong>{{ applicant.available.from }}</strong> kl. <strong>{{ applicant.available.from }}</strong>.</p>
        <p><br></p>
        <p>Hvis mødet foregår fysisk, finder det sted her: 
        <strong>{{applicant.address.street}}, {{applicant.address.post_code}} {{applicant.address.city}}</strong>
        </p>
        <p>Hvis du har spørgsmål eller er forhindret, hører vi meget gerne fra dig så hurtigt som muligt.</p>
        <p><br></p>
        <p>Vi glæder os til at møde dig.</p>
        <p><br></p>
        <p>Venlig hilsen,</p>
        <p>{{ applicant.company.name }}</p>
    ',

    'text_template' => "
        Kære {{ applicant.contact.first_name }},

        Dette er en venlig påmindelse om, at du har en aftale med {{ applicant.company.name }} den {{ applicant.available.from }} kl. {{ applicant.available.from }}.

        Hvis mødet foregår fysisk, finder det sted her: 
        {{applicant.address.street}}, {{applicant.address.post_code}} {{applicant.address.city}}

        Hvis du har spørgsmål eller er forhindret, hører vi meget gerne fra dig så hurtigt som muligt.

        Vi glæder os til at møde dig.

        Venlig hilsen,
        {{ applicant.company.name }}
    ",
];
