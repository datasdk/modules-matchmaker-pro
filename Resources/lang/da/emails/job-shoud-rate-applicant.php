<?php

return [
    'subject' => 'Vi vil gerne høre din mening om samarbejdet',

    'html_template' => '
        <p>Kære {{ applicant.user.first_name }},</p>
        <p><br></p>
        <p>Tak fordi du har været en del af rekrutteringsprocessen hos {{ applicant.company.name }}.</p>
        <p>Vi vil gerne høre, hvordan du har oplevet samarbejdet, og om der er noget, vi kan gøre bedre i fremtiden.</p>
        <p><br></p>
        <p>Vi sætter stor pris på din feedback og håber, du vil bruge et par minutter på at vurdere din oplevelse.</p>
        <p>Klik på knappen nedenfor for at afgive din vurdering:</p>
        <p><br></p>
        <p>Din feedback hjælper os med at forbedre vores processer og give en bedre oplevelse for alle fremtidige ansøgere.</p>
        <p>Tak for din tid, og vi ser frem til at høre fra dig!</p>
        <p><br></p>
        <p>Venlig hilsen,</p>
        <p>{{ applicant.company.name }}</p>
    ',

    'text_template' => "
        Kære {{ applicant.user.first_name }},

        Tak fordi du har været en del af rekrutteringsprocessen hos {{ applicant.company.name }}.

        Vi vil gerne høre, hvordan du har oplevet samarbejdet, og om der er noget, vi kan gøre bedre i fremtiden.

        Vi sætter stor pris på din feedback og håber, du vil bruge et par minutter på at vurdere din oplevelse.

        Klik på knappen nedenfor for at afgive din vurdering:

        Din feedback hjælper os med at forbedre vores processer og give en bedre oplevelse for alle fremtidige ansøgere.

        Tak for din tid, og vi ser frem til at høre fra dig!

        Venlig hilsen,
        {{ applicant.company.name }}
    ",
];
