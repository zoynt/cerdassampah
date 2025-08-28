<!DOCTYPE html>
<html>
<head>
    <title>Leaderboard</title>
    </head>
<body>
    <h1>🏆 Leaderboard Top 10 🏆</h1>
    <table>
        <thead>
            <tr>
                <th>Peringkat</th>
                <th>Nama User</th>
                <th>Poin</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $index => $user)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->points }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">Belum ada data poin.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>