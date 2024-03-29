@props([
    'eventversion',
    'teachers',
])
<div >

    @foreach($teachers AS $teacher)
        <div style="display: flex; flex-direction: row;">
            <input type="checkbox"
                   style="margin-top: 0.4rem;"
                   name="checkboxes[{{ $teacher->user_id }}]"
                   id="checkbox_"{{ $teacher->user_id }}"
                   value="{{ $teacher->user_id }}">
            <label style="text-align: left; margin-left: .5rem;">
                {{ $teacher['person']->fullNameAlpha() }}
            </label>
        </div>
    @endforeach
    </ul>
</div>
