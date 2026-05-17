<?php

return [
    'subject' => 'Del din oplevelse – Anmeld din arbejdsgiver',

    'html_template' => '
        <p>Kære {{ applicant.contact.first_name }},</p>
        <p><br></p>
        <p>Vi håber, at du har haft en god oplevelse hos {{ applicant.company.name }}, og vi vil gerne høre din ærlige vurdering af din tid hos os.</p>
        <p>Din feedback er vigtig for os og hjælper os med at forbedre arbejdsforholdene for vores ansatte.</p>
        <p>Vi opfordrer dig til at dele din oplevelse ved at vurdere din arbejdsgiver.</p>
        <p><br></p>
        <p>Klik på knappen nedenfor for at afgive din anmeldelse:</p>
        <p><br></p>
        <p>Tak for din tid og for at hjælpe os med at skabe et bedre arbejdsmiljø.</p>
        <p>Venlig hilsen,</p>
        <p><br></p>
        <p>{{ applicant.company.name }}</p>
    ',

    'text_template' => "
        Kære {{ applicant.contact.first_name }},

        Vi håber, at du har haft en god oplevelse hos {{ applicant.company.name }}, og vi vil gerne høre din ærlige vurdering af din tid hos os.

        Din feedback er vigtig for os og hjælper os med at forbedre arbejdsforholdene for vores ansatte.

        Vi opfordrer dig til at dele din oplevelse ved at vurdere din arbejdsgiver.

        Klik på knappen nedenfor for at afgive din anmeldelse:

        Tak for din tid og for at hjælpe os med at skabe et bedre arbejdsmiljø.

        Venlig hilsen,
        {{ applicant.company.name }}
    ",
];
