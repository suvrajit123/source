<h3>As-salāmu ‘alaykum wa-rahmatullāhi wa-barakātuh</h3>
<div style="padding:20px 0;">
	{{ url('/user/activate/' . encrypt($user->email)) }}
</div>

Jazak'Allah Khair,<br><br>
<div style="width: 90%; font-size: 12px; font-family: arial;">
	<p> {{ $mail_footer->value }} </p>
</div>
