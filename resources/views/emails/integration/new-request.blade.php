<x-mail::message>

A new integration request has been submitted: <br>

The details are shown below:

<x-mail::panel>

**User:** {{ $userName }}<br><br>
**Company:** {{ $userCompany }}<br><br>
**Application Name:** {{ $integrationRequest['application_name'] }} <br><br>
**Application Website:** {{ $integrationRequest['application_website'] }}<br><br>
**Reason:** {{ $integrationRequest['request_reason'] }}<br><br>

</x-mail::panel>

</x-mail::message>
