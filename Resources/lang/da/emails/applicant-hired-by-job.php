<?php

return [
    'subject' => 'Tillykke med jobbet hos {{applicant.company.name}}',

    'html_template' => '
        <p><strong>Kære {{applicant.user.first_name}}</strong></p>
        <p><br></p>
        
        <p>Vi bekræfter hermed at du har indgået en aftale med {{applicant.company.name}}.</p>
        <p><br></p>
        
        <p><strong>Projekt</strong></p>
        <p>Navn: {{applicant.name}}</p>
        <p><br></p>
        
        <p><strong>Adresse: </strong></p>
        <p>{{applicant.address.street}}, {{applicant.address.post_code}} {{applicant.address.city}}</p>
        <p><br></p>
        
        <p><strong>Tid:</strong></p>
        <p>Fra: {{applicant.available.from}}</p>
        <p>Til: {{applicant.available.to}}</p>
        <p><br></p>
        
        <p><strong>Faggruppe</strong></p>
        <p>{{applicant.categories.0.name}}</p>
        <p><br></p>
        
        <p><strong>Antal mand</strong></p>
        <p>{{applicant.amount}}</p>
        <p><br></p>
        
        <p><strong>Ansøgers kontaktinfo</strong></p>
        <p>Email: {{applicant.user.contact.email}}</p>
        <p>Telefon: {{applicant.user.contact.phone}}</p>
        <p><br></p>
        
        <p>mvh</p>
        <p>Workbizz</p>
        <p><br></p>
    ',

    'text_template' => "
        Kære {{applicant.user.first_name}},

        Vi bekræfter hermed at du har indgået en aftale med {{applicant.company.name}}.

        Projekt:
        Navn: {{applicant.name}}

        Adresse:
        {{applicant.address.street}}, {{applicant.address.post_code}} {{applicant.address.city}}

        Tid:
        Fra: {{applicant.available.from}}
        Til: {{applicant.available.to}}

        Faggruppe:
        {{applicant.categories.0.name}}

        Antal mand:
        {{applicant.amount}}

        Ansøgers kontaktinfo:
        Email: {{applicant.user.contact.email}}
        Telefon: {{applicant.user.contact.phone}}
    ",
];
