@component('mail::message')
<h3>As-salāmu ‘alaykum wa-rahmatullāhi wa-barakātuh</h3>
<div style="padding:20px 0;">
	<h4>Your resource with title " {{ $title }} " has been rejected because of following reason(s)</h4>
	<h4>Rejection Reason : {{ $reason }}</h4>
	<p>Additional Details: {{ $details ?? 'N/A' }}</p>
</div>

Jazak'Allah Khair,<br>
Admin - Islamic Resource Hub
@endcomponent
