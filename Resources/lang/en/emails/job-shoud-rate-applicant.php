<?php

return [
    'subject' => 'We would like to hear your opinion about the collaboration',

    'html_template' => '
        <p>Dear {{ applicant.user.first_name }},</p>
        <p><br></p>
        <p>Thank you for being part of the recruitment process at {{ applicant.company.name }}.</p>
        <p>We would like to hear how you experienced the collaboration and if there is anything we can improve in the future.</p>
        <p><br></p>
        <p>We greatly appreciate your feedback and hope you will take a few minutes to evaluate your experience.</p>
        <p>Click the button below to submit your review:</p>
        <p><br></p>
        <p>Your feedback helps us improve our processes and provide a better experience for all future applicants.</p>
        <p>Thank you for your time, and we look forward to hearing from you!</p>
        <p><br></p>
        <p>Best regards,</p>
        <p>{{ applicant.company.name }}</p>
    ',

    'text_template' => "
        Dear {{ applicant.user.first_name }},

        Thank you for being part of the recruitment process at {{ applicant.company.name }}.

        We would like to hear how you experienced the collaboration and if there is anything we can improve in the future.

        We greatly appreciate your feedback and hope you will take a few minutes to evaluate your experience.

        Click the button below to submit your review:

        Your feedback helps us improve our processes and provide a better experience for all future applicants.

        Thank you for your time, and we look forward to hearing from you!

        Best regards,
        {{ applicant.company.name }}
    ",
];
