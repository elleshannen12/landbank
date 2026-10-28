<h1>ADMIN Dashboard (placeholder)</h1>
<p>Logged in as: {{ auth()->user()->username }} ({{ auth()->user()->role }})</p>
<form method="POST" action="{{ route('logout') }}">@csrf<button type="submit">Logout</button></form>
