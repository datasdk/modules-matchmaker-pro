<?php

return [
    'subject' => 'Congratulations on the job at {{applicant.company.name}}',

    'html_template' => '
        <p><strong>Dear {{applicant.user.first_name}}</strong></p>
        <p><br></p>
        
        <p>We hereby confirm that you have entered into an agreement with {{applicant.company.name}}.</p>
        <p><br></p>
        
        <p><strong>Project</strong></p>
        <p>Name: {{applicant.name}}</p>
        <p><br></p>
        
        <p><strong>Address:</strong></p>
        <p>{{applicant.address.street}}, {{applicant.address.post_code}} {{applicant.address.city}}</p>
        <p><br></p>
        
        <p><strong>Time:</strong></p>
        <p>From: {{applicant.available.from}}</p>
        <p>To: {{applicant.available.to}}</p>
        <p><br></p>
        
        <p><strong>Trade group</strong></p>
        <p>{{applicant.categories.0.name}}</p>
        <p><br></p>
        
        <p><strong>Number of people</strong></p>
        <p>{{applicant.amount}}</p>
        <p><br></p>
        
        <p><strong>Applicant contact info</strong></p>
        <p>Email: {{applicant.user.contact.email}}</p>
        <p>Phone: {{applicant.user.contact.phone}}</p>
        <p><br></p>
        
        <p>Best regards,</p>
        <p>Workbizz</p>
        <p><br></p>
    ',

    'text_template' => "
        Dear {{applicant.user.first_name}},

        We hereby confirm that you have entered into an agreement with {{applicant.company.name}}.

        Project:
        Name: {{applicant.name}}

        Address:
        {{applicant.address.street}}, {{applicant.address.post_code}} {{applicant.address.city}}

        Time:
        From: {{applicant.available.from}}
        To: {{applicant.available.to}}

        Trade group:
        {{applicant.categories.0.name}}

        Number of people:
        {{applicant.amount}}

        Applicant contact info:
        Email: {{applicant.user.contact.email}}
        Phone: {{applicant.user.contact.phone}}

        Best regards,
        Workbizz
    ",
];
