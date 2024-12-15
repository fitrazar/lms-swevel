<div class="favorite-list-item">
    @if ($user)
        @php
            $displayName = '';
            if ($user->participant && $user->participant->name) {
                $displayName = $user->participant->name;
            } elseif ($user->instructor && $user->instructor->name) {
                $displayName = $user->instructor->name;
            } else {
                $displayName = $user->email;
            }
        @endphp
        <div data-id="{{ $user->id }}" data-action="0" class="avatar av-m"
            style="background-image: url('{{ Chatify::getUserWithAvatar($user)->avatar }}');">
        </div>
        <p>{{ strlen($displayName) > 5 ? substr($displayName, 0, 6) . '..' : $displayName }}</p>
    @endif
</div>
