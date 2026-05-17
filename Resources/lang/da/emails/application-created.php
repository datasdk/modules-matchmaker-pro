<?php

return [
    
    'subject' => 'Der er oprettet et nyt mandskab: id: {{ task.id }}',

    'html_template' => '
    <p><strong>Kære Admin</strong>,</p>

    <p>Der er netop blevet oprettet et nyt mandskab i systemet.</p>

    <p><strong>Virksomhed</strong></p>
    <p>Navn: {{ task.company.name }}</p>

    <p><strong>CVR</strong></p>
    <p>{{ task.company.vat }}</p>

    <p><strong>Virksomhedsrepræsentant</strong></p>
    <p>{{ task.user.first_name }} {{ task.user.last_name }}</p>

    <p><strong>Kontaktinfo</strong></p>
    <p>Telefon: {{ task.user.contact.phone }}</p>
    <p>Email: {{ task.user.contact.email }}</p>

    <p><strong>Opslag navn</strong></p>
    <p>{{ task.name }}</p>

    <p><strong>Opslag beskrivelse</strong></p>
    <p>{{ task.description }}</p>

    <p><strong>Type opslag</strong></p>
    <p>Mandskab</p>

    <p><strong>Faggruppe</strong></p>
    <p>{{ task.categories.0.name }}</p>

    <p><strong>Adresse</strong></p>
    <p>{{ task.address.street }}, {{ task.address.post_code }} {{ task.address.city }}</p>

    <p><strong>Periode</strong></p>
    <p>Fra: {{ task.available.from }}</p>
    <p>Til: {{ task.available.to }}</p>
    ',


    'text_template' => '
    Kære Admin,

    Der er netop blevet oprettet et nyt mandskab i systemet.

    Virksomhed:
    Navn: {{ task.company.name }}

    CVR:
    {{ task.company.vat }}

    Virksomhedsrepræsentant:
    {{ task.user.first_name }} {{ task.user.last_name }}

    Kontaktinfo:
    Telefon: {{ task.user.contact.phone }}
    Email: {{ task.user.contact.email }}

    Opslag navn:
    {{ task.name }}

    Opslag beskrivelse:
    {{ task.description }}

    Type opslag:
    Mandskab

    Faggruppe:
    {{ task.categories.0.name }}

    Adresse:
    {{ task.address.street }}, {{ task.address.post_code }} {{ task.address.city }}

    Periode:
    Fra: {{ task.available.from }}
    Til: {{ task.available.to }}
    ',

    
];
