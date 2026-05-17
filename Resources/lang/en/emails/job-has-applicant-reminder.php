<?php

return [
    'subject' => 'Reminder: {{applicant.user.first_name}} starts on the project {{job.name}} on {{job.available.from}}',

    'html_template' => '
        <p>Dear {{job.user.first_name}},</p>
        <p><br></p>
        <p>This is a friendly reminder that {{applicant.user.first_name}} will start working with {{job.company.name}} on the project <strong>{{job.name}}</strong> on <strong>{{job.available.from}}</strong> at <strong>{{job.available.from}}</strong>.</p>
        <p><br></p>
        <p><strong>Workplace:</strong><br>
        {{job.address.street}}, {{job.address.post_code}} {{job.address.city}}</p>
        <p><br></p>
        <p><strong>Trade group:</strong><br>
        {{applicant.categories.0.name}}</p>
        <p><br></p>
        <p><strong>Applicant contact info:</strong><br>
        Email: {{applicant.user.contact.email}}<br>
        Phone: {{applicant.user.contact.phone}}</p>
        <p><br></p>
        <p>Please remember to welcome the person warmly and ensure everything is ready for their start.</p>
        <p><br></p>
        <p>Best regards,</p>
        <p>{{job.company.name}}</p>
    ',

    'text_template' => "
        Dear {{job.user.first_name}},

        This is a friendly reminder that {{applicant.user.first_name}} will start working with {{job.company.name}} on the project {{job.name}} on {{job.available.from}} at {{job.available.from}}.

        Workplace:
        {{job.address.street}}, {{job.address.post_code}} {{job.address.city}}

        Trade group:
        {{applicant.categories.0.name}}

        Applicant contact info:
        Email: {{applicant.user.contact.email}}
        Phone: {{applicant.user.contact.phone}}

        Please remember to welcome the person warmly and ensure everything is ready for their start.

        Best regards,
        {{job.company.name}}
    ",
];
