<?php

return [

    'subject' => 'Your crew has been accepted ("{{ mytask.name }}")',

    'html_template' => '
    <p><strong>Dear {{ mytask.user.first_name }}</strong>,</p>

    <p>Your crew has been <strong>accepted by a company</strong>.</p>

    <p>The company has selected you for the project <strong>{{ task.name }}</strong>.</p>

    <p>Open the app to accept the company, form a match, and start the collaboration.</p>

    <p>[RedirectButton user_id="{{ task.user.id }}" url="/tasks/show?task_id={{ task.id }}" text="Open the project in the app"/]</p>

    <p><strong>Company</strong></p>
    <p>Name: {{ task.company.name }}</p>
    <p>VAT: {{ task.company.vat }}</p>

    <p><strong>Company Representative</strong></p>
    <p>{{ task.company.user.first_name }} {{ task.company.user.last_name }}</p>

    <p><strong>Contact Info</strong></p>
    <p>Phone: {{ task.company.user.contact.phone }}</p>
    <p>Email: {{ task.company.user.contact.email }}</p>

    <p><strong>Project</strong></p>
    <p>{{ task.description }}</p>

    <p><strong>Address</strong></p>
    <p>{{ task.address.street }}, {{ task.address.post_code }} {{ task.address.city }}</p>

    <p><strong>Period</strong></p>
    <p>From: {{ task.available.from }}</p>
    <p>To: {{ task.available.to }}</p>

    <p><em>Log in to the app to see more details and get in touch with the company.</em></p>
    ',

    'text_template' => '
    Dear {{ mytask.user.first_name }},

    Your crew has been accepted by a company.

    The company has selected you for the project: {{ task.name }}.

    Open the app to accept the company, form a match, and start the collaboration.

    Company:
    Name: {{ task.company.name }}
    VAT: {{ task.company.vat }}

    Company Representative:
    {{ task.company.user.first_name }} {{ task.company.user.last_name }}

    Contact Info:
    Phone: {{ task.company.user.contact.phone }}
    Email: {{ task.company.user.contact.email }}

    Project:
    {{ task.description }}

    Address:
    {{ task.address.street }}, {{ task.address.post_code }} {{ task.address.city }}

    Period:
    From: {{ task.available.from }}
    To: {{ task.available.to }}

    Log in to the app to see more details and get in touch with the company.
    ',

];
