@component('mail::message')
# User Import Summary

The user import process has been completed successfully.

## Summary:
- **Passed:** {{ $userImport->passed }}
- **Failed:** {{ $userImport->failed }}

@if($failures->isNotEmpty())
## Failure Details:

@foreach($failures->take(5) as $failure)
- **Data:** {{ implode(', ', $failure['row']) ?: 'No data available' }}

@foreach($failure['errors'] as $field => $messages)
    - **{{ ucfirst($field) }} Error:** {{ implode(', ', $messages) }}
@endforeach

@endforeach

@if($failures->count() > 5)
...and {{ $failures->count() - 5 }} more failures.
@endif
@endif

Thanks,
{{ config('app.name') }}
@endcomponent
