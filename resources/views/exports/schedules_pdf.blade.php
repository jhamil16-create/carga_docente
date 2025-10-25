<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>Schedules PDF</title></head>
<body>
<h2>Schedules</h2>
<table border="1" cellpadding="4" cellspacing="0">
    <thead><tr><th>ID</th><th>Group</th><th>Classroom</th><th>Faculty</th><th>Day</th><th>Start</th><th>End</th></tr></thead>
    <tbody>
    @foreach($rows as $r)
        <tr>
            <td>{{ $r->id }}</td>
            <td>{{ $r->group_id }}</td>
            <td>{{ $r->classroom_id }}</td>
            <td>{{ $r->faculty_member_id }}</td>
            <td>{{ $r->day_of_week }}</td>
            <td>{{ $r->start_time }}</td>
            <td>{{ $r->end_time }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>