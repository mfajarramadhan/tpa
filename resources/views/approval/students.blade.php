@foreach ($students as $student)
    <div>
        <p>{{ $student->name }} - {{ $student->parent->name }}</p>

        <form action="{{ route('approval.students.approve', $student->id) }}" method="POST">
            @csrf

            <select name="classroom_id" required>
                @foreach ($classrooms as $class)
                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                @endforeach
            </select>

            <button type="submit">Approve</button>
        </form>

        <form action="{{ route('approval.students.reject', $student->id) }}" method="POST">
            @csrf
            <button type="submit">Reject</button>
        </form>
    </div>
@endforeach