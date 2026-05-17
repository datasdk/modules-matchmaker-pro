<?php

return [
    'subject' => 'Husk: {{applicant.user.first_name}} starter på projektet {{job.name}} den {{ job.available.from }}',

    'html_template' => '
        <p>Kære {{job.user.first_name}},</p>
        <p><br></p>
        <p>Dette er en venlig påmindelse om, at {{applicant.user.first_name}} starter samarbejdet med {{job.company.name}} på projektet <strong>{{job.name}}</strong> den <strong>{{job.available.from}}</strong> kl. <strong>{{job.available.from}}</strong>.</p>
        <p><br></p>
        <p><strong>Arbejdssted:</strong><br>
        {{job.address.street}}, {{job.address.post_code}} {{job.address.city}}</p>
        <p><br></p>
        <p><strong>Faggruppe:</strong><br>
        {{applicant.categories.0.name}}</p>
        <p><br></p>
        <p><strong>Ansøgers kontaktinfo:</strong><br>
        Email: {{applicant.user.contact.email}}<br>
        Telefon: {{applicant.user.contact.phone}}</p>
        <p><br></p>
        <p>Husk at tage godt imod vedkommende og sikre, at alt er klart til opstarten.</p>
        <p><br></p>
        <p>Venlig hilsen,</p>
        <p>{{job.company.name}}</p>
    ',

    'text_template' => "
        Kære {{job.user.first_name}},

        Dette er en venlig påmindelse om, at {{applicant.user.first_name}} starter samarbejdet med {{job.company.name}} på projektet {{job.name}} den {{job.available.from}} kl. {{job.available.from}}.

        Arbejdssted:
        {{job.address.street}}, {{job.address.post_code}} {{job.address.city}}

        Faggruppe:
        {{applicant.categories.0.name}}

        Ansøgers kontaktinfo:
        Email: {{applicant.user.contact.email}}
        Telefon: {{applicant.user.contact.phone}}

        Husk at tage godt imod vedkommende og sikre, at alt er klart til opstarten.

        Venlig hilsen,
        {{job.company.name}}
    ",
];
