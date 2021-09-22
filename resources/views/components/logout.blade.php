<div style="display: flex; flex-direction: row; justify-content: end; margin-right: 1rem;">
    <a href="{{ route('xlogout') }}">
        Log out
    </a>

    <!-- onclick="event.preventDefault(); document.getElementById('logout-form').submit();" -->
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>

</div>
