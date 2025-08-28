import React, { useState, useEffect } from 'react';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faStar, faTrophy, faUser } from '@fortawesome/free-solid-svg-icons';

export default function LeaderboardSection() {
    const [leaderboardData, setLeaderboardData] = useState([]);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        // Ambil data dari API Laravel saat komponen pertama kali dimuat
        const fetchLeaderboard = async () => {
            try {
                const response = await fetch('/api/leaderboard');
                if (!response.ok) {
                    throw new Error(`Server responded with status: ${response.status}`);
                }
                const data = await response.json();
                setLeaderboardData(data);
            } catch (error) {
                console.error('Error fetching leaderboard data:', error);
                // Menampilkan data dummy jika API gagal
                setLeaderboardData([
                    { username: 'User 1', total_points: 1000 },
                    { username: 'User 2', total_points: 900 },
                    { username: 'User 3', total_points: 800 },
                    { username: 'User 4', total_points: 750 },
                    { username: 'User 5', total_points: 700 },
                    { username: 'User 6', total_points: 650 },
                    { username: 'User 7', total_points: 600 },
                    { username: 'User 8', total_points: 550 },
                    { username: 'User 9', total_points: 500 },
                    { username: 'User 10', total_points: 450 },
                ]);
            } finally {
                setLoading(false);
            }
        };

        fetchLeaderboard();
    }, []);

    // Menambahkan rank ke data untuk ditampilkan
    const rankedData = leaderboardData.map((item, index) => ({
        ...item,
        rank: index + 1
    }));

    // Mengatur ulang urutan top 3 menjadi 2-1-3
    const top3Rankings = rankedData.length > 0
        ? [rankedData[1], rankedData[0], rankedData[2]]
        : [];
    const otherRanks = rankedData.slice(3);

    return (
        <div style={leaderboardSectionStyle}>
            <h2 style={leaderboardTitleStyle}>Leaderboard</h2>

            {loading ? (
                <p>Memuat Leaderboard...</p>
            ) : (
                <>
                    <div style={top3ContainerStyle}>
                        {top3Rankings.map((player) => (
                            <div key={player.username} style={top3CardStyle(player.rank)}>
                                <FontAwesomeIcon icon={faTrophy} style={trophyStyle(player.rank)} />
                                <h3 style={rankNumberTop3Style}>{player.rank}</h3>
                                <div style={usernameIconContainerStyle}>
                                    <FontAwesomeIcon icon={faUser} style={usernameIconStyle} />
                                </div>
                                <p style={usernameTop3Style}>{player.username}</p>
                                <p style={scoreTop3Style}>{player.total_points} Points</p>
                            </div>
                        ))}
                    </div>

                    <div style={otherRanksContainerStyle}>
                        {otherRanks.map((player) => (
                            <div key={player.username} style={rankItemStyle}>
                                <span style={rankNumberStyle}>{player.rank}</span>
                                <div style={{display: 'flex', alignItems: 'center', gap: '10px', flex: 1}}>
                                    <FontAwesomeIcon icon={faUser} style={rankIconStyle} />
                                    <span style={usernameStyle}>{player.username}</span>
                                </div>
                                <span style={scoreStyle}>
                                    <FontAwesomeIcon icon={faStar} style={{ color: '#facc15', marginRight: '5px' }} />
                                    {player.total_points} Pts
                                </span>
                            </div>
                        ))}
                    </div>
                </>
            )}
        </div>
    );
}

// --- STYLING (CSS-in-JS) ---
const leaderboardSectionStyle = {
    // Mengubah background menjadi putih solid
    backgroundColor: 'white',
    width: '100%',
    maxWidth: '960px',
    borderRadius: '20px',
    padding: '50px 70px',
    marginBottom: '20px',
    boxShadow: '0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1)',
    textAlign: 'center',
};
const leaderboardTitleStyle = {
    marginBottom: '40px',
    color: '#16a34a',
    fontSize: '2em',
    fontWeight: '800',
    textShadow: '2px 2px 4px rgba(0,0,0,0.1)'
};
const top3ContainerStyle = {
    display: 'flex',
    justifyContent: 'center',
    alignItems: 'flex-end',
    gap: '20px',
    marginBottom: '20px',
};
const top3CardStyle = (rank) => ({
    width: '120px',
    padding: '16px 12px',
    background: rank === 1 ? '#b91c1c' : rank === 2 ? '#a16207' : '#57534e',
    color: 'white',
    borderRadius: '12px',
    position: 'relative',
    display: 'flex',
    flexDirection: 'column',
    alignItems: 'center',
    minHeight: rank === 1 ? '220px' : rank === 2 ? '200px' : '180px',
    justifyContent: 'space-between',
    boxShadow: `0 10px 15px -3px rgba(0,0,0,${rank === 1 ? 0.2 : 0.1})`,
    transition: 'all 0.3s ease',
});
const rankNumberTop3Style = {
    margin: '0',
    fontSize: '2.5em',
    fontWeight: '800'
};
const usernameIconContainerStyle = {
    width: '50px',
    height: '50px',
    borderRadius: '50%',
    background: 'rgba(255,255,255,0.2)',
    display: 'flex',
    alignItems: 'center',
    justifyContent: 'center',
};
const usernameIconStyle = {
    fontSize: '2em',
    color: 'white',
};
const usernameTop3Style = {
    margin: '0',
    fontWeight: 'bold',
    fontSize: '1em'
};
const scoreTop3Style = {
    margin: '4px 0 0',
    fontSize: '0.8em',
    color: '#facc15'
};
const trophyStyle = (rank) => ({
    fontSize: '3em',
    color: rank === 1 ? '#facc15' : rank === 2 ? '#e5e7eb' : '#a16207',
    position: 'absolute',
    top: '-20px',
    left: '50%',
    transform: 'translateX(-50%)',
    filter: 'drop-shadow(0 2px 2px rgba(0,0,0,0.2))'
});
const otherRanksContainerStyle = {
    // Mengubah background menjadi putih solid
    backgroundColor: 'white',
    padding: '10px',
    borderRadius: '12px',
    border: '1px solid rgba(0,0,0,0.1)',
};
const rankItemStyle = {
    display: 'flex',
    alignItems: 'center',
    background: 'rgba(243, 244, 246, 0.5)', // Warna latar belakang item
    borderRadius: '8px',
    padding: '10px 15px',
    justifyContent: 'space-between',
    marginBottom: '5px'
};
const rankNumberStyle = {
    fontWeight: 'bold',
    color: '#1f2937',
    width: '20px',
    textAlign: 'center'
};
const usernameStyle = {
    flex: 1,
    textAlign: 'left',
    color: '#1f2937',
    fontWeight: 'bold'
};
const rankIconStyle = {
    fontSize: '1em',
    color: '#1f2937'
};
const scoreStyle = {
    fontWeight: 'bold',
    color: '#facc15',
    fontSize: '0.9em'
};
