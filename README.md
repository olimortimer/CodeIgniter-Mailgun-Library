# CodeIgniter Mailgun Library
Drop in email library to send via the Mailgun API.

I created this as I wanted to start sending via the Mailgun API, but didn't want to change all the CI `$this->email->send()` instances within my project.  This is intended to be saved as `application/libraries/MY_Email.php` and should allow existing email sending to seamlessly start sending via Mailgun.

This is very much a WIP (Work In Progress), so some things may be missing.
