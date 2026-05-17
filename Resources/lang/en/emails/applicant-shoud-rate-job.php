<?php

return [
    'subject' => 'Share your experience – Review your employer',

    'html_template' => '
        <p>Dear {{ applicant.contact.first_name }},</p>
        <p><br></p>
        <p>We hope you have had a positive experience at {{ applicant.company.name }}, and we would like to hear your honest feedback about your time with us.</p>
        <p>Your feedback is important to us and helps us improve the working conditions for our employees.</p>
        <p>We encourage you to share your experience by reviewing your employer.</p>
        <p><br></p>
        <p>Click the button below to submit your review:</p>
        <p><br></p>
        <p>Thank you for your time and for helping us create a better work environment.</p>
        <p>Best regards,</p>
        <p><br></p>
        <p>{{ applicant.company.name }}</p>
    ',

    'text_template' => "
        Dear {{ applicant.contact.first_name }},

        We hope you have had a positive experience at {{ applicant.company.name }}, and we would like to hear your honest feedback about your time with us.

        Your feedback is important to us and helps us improve the working conditions for our employees.

        We encourage you to share your experience by reviewing your employer.

        Click the button below to submit your review:

        Thank you for your time and for helping us create a better work environment.

        Best regards,
        {{ applicant.company.name }}
    ",
];
