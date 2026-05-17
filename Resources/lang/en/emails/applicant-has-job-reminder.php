<?php
return [
    'subject' => 'Reminder: Your upcoming appointment with {{ applicant.company.name }}',

    'html_template' => '
        <p>Dear {{ applicant.contact.first_name }},</p>
        <p><br></p>
        <p>This is a friendly reminder that you have an appointment with {{ applicant.company.name }} on <strong>{{ applicant.available.from }}</strong> at <strong>{{ applicant.available.from }}</strong>.</p>
        <p><br></p>
        <p>If the meeting is taking place in person, the location is: 
        <strong>{{applicant.address.street}}, {{applicant.address.post_code}} {{applicant.address.city}}</strong>
        </p>
        <p>If you have any questions or are unable to attend, please let us know as soon as possible.</p>
        <p><br></p>
        <p>We look forward to meeting you.</p>
        <p><br></p>
        <p>Best regards,</p>
        <p>{{ applicant.company.name }}</p>
    ',

    'text_template' => "
        Dear {{ applicant.contact.first_name }},

        This is a friendly reminder that you have an appointment with {{ applicant.company.name }} on {{ applicant.available.from }} at {{ applicant.available.from }}.

        If the meeting is taking place in person, the location is:
        {{applicant.address.street}}, {{applicant.address.post_code}} {{applicant.address.city}}

        If you have any questions or are unable to attend, please let us know as soon as possible.

        We look forward to meeting you.

        Best regards,
        {{ applicant.company.name }}
    ",
];
