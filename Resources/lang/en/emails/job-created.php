<?php

return [
    
    'subject' => 'A new project has been created: id: {{ task.id }}',

    'html_template' => '
    <p><strong>Dear Admin</strong>,</p>

    <p>A new project has just been created in the system.</p>

    <p><strong>Company</strong></p>
    <p>Name: {{ task.company.name }}</p>

    <p><strong>VAT</strong></p>
    <p>{{ task.company.vat }}</p>

    <p><strong>Company Representative</strong></p>
    <p>{{ task.user.first_name }} {{ task.user.last_name }}</p>

    <p><strong>Contact Info</strong></p>
    <p>Phone: {{ task.user.contact.phone }}</p>
    <p>Email: {{ task.user.contact.email }}</p>

    <p><strong>Project Name</strong></p>
    <p>{{ task.name }}</p>

    <p><strong>Project Description</strong></p>
    <p>{{ task.description }}</p>

    <p><strong>Project Type</strong></p>
    <p>Project</p>

    <p><strong>Category</strong></p>
    <p>{{ task.categories.0.name }}</p>

    <p><strong>Address</strong></p>
    <p>{{ task.address.street }}, {{ task.address.post_code }} {{ task.address.city }}</p>

    <p><strong>Period</strong></p>
    <p>From: {{ task.available.from }}</p>
    <p>To: {{ task.available.to }}</p>
    ',

    'text_template' => '
    Dear Admin,

    A new project has just been created in the system.

    Company:
    Name: {{ task.company.name }}

    VAT:
    {{ task.company.vat }}

    Company Representative:
    {{ task.user.first_name }} {{ task.user.last_name }}

    Contact Info:
    Phone: {{ task.user.contact.phone }}
    Email: {{ task.user.contact.email }}

    Project Name:
    {{ task.name }}

    Project Description:
    {{ task.description }}

    Project Type:
    Project

    Category:
    {{ task.categories.0.name }}

    Address:
    {{ task.address.street }}, {{ task.address.post_code }} {{ task.address.city }}

    Period:
    From: {{ task.available.from }}
    To: {{ task.available.to }}
    ',
    
];
