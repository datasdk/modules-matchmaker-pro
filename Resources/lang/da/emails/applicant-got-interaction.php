<?php

return [

    'subject' => 'Dit mandskab er blevet accepteret ( "{{ mytask.name }}" )',

    'html_template' => '
    <p><strong>Kære {{ mytask.user.first_name }}</strong>,</p>

    <p>Dit mandskab er blevet <strong>accepteret af en virksomhed</strong>.</p>

    <p>Virksomheden har valgt jer til projektet <strong>{{ task.name }}</strong>.</p>

    <p>Åbn appen og accepter virksomheden for at danne et match, og starte samarbejdet</p>

    <p>[RedirectButton user_id="{{ task.user.id }}" url="/tasks/show?task_id={{ task.id }}" text="Åbn projektet i appen"/]</p>

    <p><strong>Virksomhed</strong></p>
    <p>Navn: {{ task.company.name }}</p>
    <p>CVR: {{ task.company.vat }}</p>

    <p><strong>Virksomhedsrepræsentant</strong></p>
    <p>{{ task.company.user.first_name }} {{ task.company.user.last_name }}</p>

    <p><strong>Kontaktinfo</strong></p>
    <p>Telefon: {{ task.company.user.contact.phone }}</p>
    <p>Email: {{ task.company.user.contact.email }}</p>

    <p><strong>Projekt</strong></p>
    <p>{{ task.description }}</p>

    <p><strong>Adresse</strong></p>
    <p>{{ task.address.street }}, {{ task.address.post_code }} {{ task.address.city }}</p>

    <p><strong>Periode</strong></p>
    <p>Fra: {{ task.available.from }}</p>
    <p>Til: {{ task.available.to }}</p>

    <p><em>Log ind i appen for at se mere og komme i kontakt med virksomheden.</em></p>
    ',

    'text_template' => '
    Kære {{ mytask.user.first_name }},

    Dit mandskab er blevet accepteret af en virksomhed.

    Virksomheden har valgt jer til projektet: {{ task.name }}.

    Åbn appen og accepter virksomheden for at danne et match og starte samarbejdet.

    Virksomhed:
    Navn: {{ task.company.name }}
    CVR: {{ task.company.vat }}

    Virksomhedsrepræsentant:
    {{ task.company.user.first_name }} {{ task.company.user.last_name }}

    Kontaktinfo:
    Telefon: {{ task.company.user.contact.phone }}
    Email: {{ task.company.user.contact.email }}

    Projekt:
    {{ task.description }}

    Adresse:
    {{ task.address.street }}, {{ task.address.post_code }} {{ task.address.city }}

    Periode:
    Fra: {{ task.available.from }}
    Til: {{ task.available.to }}

    Log ind i appen for at se mere og komme i kontakt med virksomheden.
    ',
];
