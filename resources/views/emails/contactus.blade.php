@component('mail::message')
<h3>As-salāmu ‘alaykum wa-rahmatullāhi wa-barakātuh</h3>
<div style="padding:20px 0;">
	{{ $message }}
</div>

Jazak'Allah Khair,<br>
{{ $fullname }}
@endcomponent
